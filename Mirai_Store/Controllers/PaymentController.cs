using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;
using System.Globalization;
using System.Linq;
using System.Net.Http.Headers;
using System.Net.Http.Json;
using System.Security.Cryptography;
using System.Text;
using System.Text.Json;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/payment")]
    public class PaymentController : ControllerBase
    {
        private readonly IMongoCollection<Transaction> _transactionCollection;
        private readonly IMongoCollection<User> _userCollection;
        private readonly IHttpClientFactory _httpClientFactory;
        private readonly IConfiguration _configuration;

        public PaymentController(MongoDbContext dbContext, IHttpClientFactory httpClientFactory, IConfiguration configuration)
        {
            _transactionCollection = dbContext.Transactions;
            _userCollection = dbContext.User;
            _httpClientFactory = httpClientFactory;
            _configuration = configuration;
        }

        [AllowAnonymous]
        [HttpPost("momo/callback")]
        public async Task<IActionResult> MoMoCallback([FromBody] PaymentCallbackRequest request)
        {
            return await HandleCallback("momo", request);
        }

        [AllowAnonymous]
        [HttpPost("paypal/callback")]
        public async Task<IActionResult> PayPalCallback([FromBody] PaymentCallbackRequest request)
        {
            return await HandleCallback("paypal", request);
        }

        [AllowAnonymous]
        [HttpGet("paypal/return")]
        public async Task<IActionResult> PayPalReturn([FromQuery] string referenceId, [FromQuery] string token)
        {
            if (string.IsNullOrWhiteSpace(referenceId) || string.IsNullOrWhiteSpace(token))
            {
                return Redirect(BuildFrontendRedirectUrl(false, "Thiếu thông tin PayPal."));
            }

            var capture = await CapturePayPalOrderAsync(token);
            var callback = new PaymentCallbackRequest
            {
                ReferenceId = referenceId,
                Success = capture.Success,
                Amount = capture.Amount,
                Message = capture.Message,
                GatewayTransactionId = capture.CaptureId ?? token
            };

            var result = await ApplyGatewayResult("paypal", callback);
            var status = result.Status == "completed";
            return Redirect(BuildFrontendRedirectUrl(status, result.Message));
        }

        [AllowAnonymous]
        [HttpGet("paypal/cancel")]
        public async Task<IActionResult> PayPalCancel([FromQuery] string referenceId)
        {
            if (!string.IsNullOrWhiteSpace(referenceId))
            {
                var callback = new PaymentCallbackRequest
                {
                    ReferenceId = referenceId,
                    Success = false,
                    Message = "Người dùng hủy thanh toán PayPal."
                };

                await ApplyGatewayResult("paypal", callback);
            }

            return Redirect(BuildFrontendRedirectUrl(false, "Thanh toán PayPal đã bị hủy."));
        }

        [AllowAnonymous]
        [HttpGet("momo/return")]
        public async Task<IActionResult> MomoReturn()
        {
            var referenceId = Request.Query["referenceId"].ToString();
            var orderId = Request.Query["orderId"].ToString();
            var requestId = Request.Query["requestId"].ToString();
            var amountRaw = Request.Query["amount"].ToString();
            var message = Request.Query["message"].ToString();
            var resultCodeRaw = Request.Query["resultCode"].ToString();
            var transId = Request.Query["transId"].ToString();
            var extraData = Request.Query["extraData"].ToString();
            var partnerCode = Request.Query["partnerCode"].ToString();
            var orderInfo = Request.Query["orderInfo"].ToString();
            var orderType = Request.Query["orderType"].ToString();
            var payType = Request.Query["payType"].ToString();
            var responseTime = Request.Query["responseTime"].ToString();
            var signature = Request.Query["signature"].ToString();

            if (string.IsNullOrWhiteSpace(referenceId))
            {
                referenceId = orderId;
            }

            var signatureValid = VerifyMomoSignature(
                amountRaw,
                extraData,
                message,
                orderId,
                orderInfo,
                orderType,
                partnerCode,
                payType,
                requestId,
                responseTime,
                resultCodeRaw,
                transId,
                signature
            );

            if (!signatureValid)
            {
                return Redirect(BuildFrontendRedirectUrl(false, "Chữ ký MoMo không hợp lệ."));
            }

            var resultCode = int.TryParse(resultCodeRaw, out var code) ? code : -1;
            var isSuccess = resultCode == 0;
            var amount = double.TryParse(amountRaw, NumberStyles.Any, CultureInfo.InvariantCulture, out var parsedAmount)
                ? parsedAmount
                : (double?)null;

            var callback = new PaymentCallbackRequest
            {
                ReferenceId = referenceId,
                Success = isSuccess,
                Amount = amount,
                Message = message,
                GatewayTransactionId = transId
            };

            var result = await ApplyGatewayResult("momo", callback);
            var status = result.Status == "completed";
            return Redirect(BuildFrontendRedirectUrl(status, result.Message));
        }

        [AllowAnonymous]
        [HttpPost("momo/ipn")]
        public async Task<IActionResult> MomoIpn([FromBody] JsonElement payload)
        {
            var referenceId = payload.TryGetProperty("orderId", out var orderIdEl) ? orderIdEl.GetString() : null;
            var resultCode = payload.TryGetProperty("resultCode", out var resultCodeEl) ? resultCodeEl.GetInt32() : -1;
            var message = payload.TryGetProperty("message", out var messageEl) ? messageEl.GetString() : null;
            var transId = payload.TryGetProperty("transId", out var transIdEl) ? transIdEl.ToString() : null;
            var amount = payload.TryGetProperty("amount", out var amountEl) ? amountEl.GetDouble() : (double?)null;

            if (string.IsNullOrWhiteSpace(referenceId))
            {
                return BadRequest(new { Success = false, Message = "orderId không hợp lệ" });
            }

            var callback = new PaymentCallbackRequest
            {
                ReferenceId = referenceId,
                Success = resultCode == 0,
                Amount = amount,
                Message = message,
                GatewayTransactionId = transId
            };

            var result = await ApplyGatewayResult("momo", callback);
            return Ok(new { Success = true, Message = result.Message, Status = result.Status });
        }

        private async Task<IActionResult> HandleCallback(string paymentMethod, PaymentCallbackRequest request)
        {
            if (string.IsNullOrWhiteSpace(request.ReferenceId))
            {
                return BadRequest(new { Success = false, Message = "ReferenceId không hợp lệ" });
            }

            var result = await ApplyGatewayResult(paymentMethod, request);
            if (!result.Found)
            {
                return NotFound(new { Success = false, Message = result.Message });
            }

            return Ok(new { Success = true, Message = result.Message, Status = result.Status });
        }

        private async Task<(bool Found, string Status, string Message)> ApplyGatewayResult(string paymentMethod, PaymentCallbackRequest request)
        {
            var transaction = await _transactionCollection
                .Find(x => x.ReferenceId == request.ReferenceId && x.PaymentMethod == paymentMethod)
                .FirstOrDefaultAsync();

            if (transaction == null)
            {
                return (false, "not_found", "Không tìm thấy giao dịch");
            }

            if (transaction.Status != "pending")
            {
                return (true, transaction.Status, "Giao dịch đã được xử lý");
            }

            var status = request.Success ? "completed" : "failed";

            var metadata = new
            {
                gateway = paymentMethod,
                gatewayTransactionId = request.GatewayTransactionId,
                message = request.Message,
                amount = request.Amount,
                receivedAt = DateTime.UtcNow
            };

            var update = Builders<Transaction>.Update
                .Set(x => x.Status, status)
                .Set(x => x.Metadata, metadata);

            await _transactionCollection.UpdateOneAsync(x => x.Id == transaction.Id, update);

            if (status == "completed")
            {
                var balanceUpdate = Builders<User>.Update.Inc(x => x.Balance, transaction.Amount);
                await _userCollection.UpdateOneAsync(x => x.Id == transaction.UserId, balanceUpdate);
            }

            var responseMessage = status == "completed"
                ? "Thanh toán thành công."
                : "Thanh toán thất bại.";

            return (true, status, responseMessage);
        }

        private string BuildFrontendRedirectUrl(bool success, string? message)
        {
            var frontendUrl = _configuration["FrontendUrl"];
            var baseUrl = string.IsNullOrWhiteSpace(frontendUrl) ? "http://localhost:8000" : frontendUrl.TrimEnd('/');
            var status = success ? "success" : "failed";
            var redirectUrl = $"{baseUrl}/wallet?payment={status}";

            if (!string.IsNullOrWhiteSpace(message))
            {
                redirectUrl += $"&message={Uri.EscapeDataString(message)}";
            }

            return redirectUrl;
        }

        private static string ComputeHmacSha256(string data, string secret)
        {
            using var hmac = new HMACSHA256(Encoding.UTF8.GetBytes(secret));
            var hash = hmac.ComputeHash(Encoding.UTF8.GetBytes(data));
            return Convert.ToHexString(hash).ToLowerInvariant();
        }

        private bool VerifyMomoSignature(
            string amount,
            string extraData,
            string message,
            string orderId,
            string orderInfo,
            string orderType,
            string partnerCode,
            string payType,
            string requestId,
            string responseTime,
            string resultCode,
            string transId,
            string signature)
        {
            if (string.IsNullOrWhiteSpace(signature))
            {
                return true;
            }

            if (string.IsNullOrWhiteSpace(orderId))
            {
                return false;
            }

            var rawSignature =
                $"accessKey={PaymentConst.MOMO_ACCESS_KEY}" +
                $"&amount={amount}" +
                $"&extraData={extraData}" +
                $"&message={message}" +
                $"&orderId={orderId}" +
                $"&orderInfo={orderInfo}" +
                $"&orderType={orderType}" +
                $"&partnerCode={partnerCode}" +
                $"&payType={payType}" +
                $"&requestId={requestId}" +
                $"&responseTime={responseTime}" +
                $"&resultCode={resultCode}" +
                $"&transId={transId}";

            var computed = ComputeHmacSha256(rawSignature, PaymentConst.MOMO_SECRET_KEY);
            return string.Equals(computed, signature, StringComparison.OrdinalIgnoreCase);
        }

        private async Task<(bool Success, string? CaptureId, string? Message, double? Amount)> CapturePayPalOrderAsync(string orderId)
        {
            var paypalBase = PaymentConst.PAYPAL_MODE == "live"
                ? "https://api-m.paypal.com"
                : "https://api-m.sandbox.paypal.com";

            var client = _httpClientFactory.CreateClient();
            var credentials = Convert.ToBase64String(Encoding.ASCII.GetBytes($"{PaymentConst.PAYPAL_CLIENT_ID}:{PaymentConst.PAYPAL_CLIENT_SECRET}"));
            client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Basic", credentials);

            var tokenResponse = await client.PostAsync(
                $"{paypalBase}/v1/oauth2/token",
                new FormUrlEncodedContent(new[] { new KeyValuePair<string, string>("grant_type", "client_credentials") })
            );

            if (!tokenResponse.IsSuccessStatusCode)
            {
                return (false, null, "Không thể lấy access token PayPal.", null);
            }

            using var tokenDoc = JsonDocument.Parse(await tokenResponse.Content.ReadAsStringAsync());
            var accessToken = tokenDoc.RootElement.GetProperty("access_token").GetString();
            if (string.IsNullOrWhiteSpace(accessToken))
            {
                return (false, null, "Access token PayPal không hợp lệ.", null);
            }

            client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", accessToken);
            var captureResponse = await client.PostAsJsonAsync($"{paypalBase}/v2/checkout/orders/{orderId}/capture", new { });
            if (!captureResponse.IsSuccessStatusCode)
            {
                return (false, null, "Không thể capture giao dịch PayPal.", null);
            }

            using var captureDoc = JsonDocument.Parse(await captureResponse.Content.ReadAsStringAsync());
            var status = captureDoc.RootElement.TryGetProperty("status", out var statusEl)
                ? statusEl.GetString()
                : null;
            var captureId = captureDoc.RootElement.TryGetProperty("id", out var idEl) ? idEl.GetString() : null;

            double? amount = null;
            if (captureDoc.RootElement.TryGetProperty("purchase_units", out var purchaseUnits))
            {
                var firstUnit = purchaseUnits.EnumerateArray().FirstOrDefault();
                if (firstUnit.ValueKind != JsonValueKind.Undefined &&
                    firstUnit.TryGetProperty("payments", out var payments) &&
                    payments.TryGetProperty("captures", out var captures))
                {
                    var firstCapture = captures.EnumerateArray().FirstOrDefault();
                    if (firstCapture.ValueKind != JsonValueKind.Undefined &&
                        firstCapture.TryGetProperty("amount", out var captureAmount) &&
                        captureAmount.TryGetProperty("value", out var valueEl) &&
                        double.TryParse(valueEl.GetString(), NumberStyles.Any, CultureInfo.InvariantCulture, out var parsedAmount))
                    {
                        amount = parsedAmount;
                    }
                }
            }

            var success = string.Equals(status, "COMPLETED", StringComparison.OrdinalIgnoreCase);
            return (success, captureId, success ? "Thanh toán PayPal thành công." : "Thanh toán PayPal thất bại.", amount);
        }
    }
}
