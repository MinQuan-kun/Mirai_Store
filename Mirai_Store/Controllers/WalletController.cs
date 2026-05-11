using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;
using System.Globalization;
using System.Net.Http.Headers;
using System.Net.Http.Json;
using System.Security.Claims;
using System.Security.Cryptography;
using System.Text;
using System.Text.Json;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/wallet")]
    [Authorize]
    public class WalletController : ControllerBase
    {
        private const double PaypalExchangeRate = 25000;
        private readonly IMongoCollection<Transaction> _transactionCollection;
        private readonly IMongoCollection<User> _userCollection;
        private readonly IHttpClientFactory _httpClientFactory;

        public WalletController(MongoDbContext dbContext, IHttpClientFactory httpClientFactory)
        {
            _transactionCollection = dbContext.Transactions;
            _userCollection = dbContext.User;
            _httpClientFactory = httpClientFactory;
        }

        private async Task<List<Transaction>> FetchTransactions(string userId, string? filter)
        {
            var filterBuilder = Builders<Transaction>.Filter;
            var mongoFilter = filterBuilder.Eq(x => x.UserId, userId);

            if (!string.IsNullOrEmpty(filter) && filter != "all")
            {
                mongoFilter &= filterBuilder.Eq(x => x.Type, filter);
            }

            return await _transactionCollection.Find(mongoFilter)
                .SortByDescending(x => x.CreatedAt)
                .ToListAsync();
        }

        [HttpGet("balance")]
        public async Task<IActionResult> GetBalance()
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var user = await _userCollection.Find(x => x.Id == userId).FirstOrDefaultAsync();
            if (user == null) return NotFound(new { Success = false, Message = "User không tồn tại" });

            return Ok(new { Success = true, Balance = user.Balance });
        }

        [HttpGet("history")]
        public async Task<IActionResult> GetHistory([FromQuery] string? filter)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var transactions = await FetchTransactions(userId, filter);

            return Ok(new { Success = true, Data = transactions });
        }

        [HttpGet("transactions")]
        public async Task<IActionResult> GetTransactions([FromQuery] string? filter)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var transactions = await FetchTransactions(userId, filter);

            return Ok(new { Success = true, Data = transactions });
        }

        [HttpPost("deposit")]
        public async Task<IActionResult> Deposit([FromBody] DepositRequest request)
        {
            if (request.Amount < 1000) return BadRequest(new { Success = false, Message = "Số tiền tối thiểu là 1,000 VNĐ" });

            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var paymentMethod = string.IsNullOrWhiteSpace(request.PaymentMethod)
                ? "test_card"
                : request.PaymentMethod.Trim().ToLowerInvariant();

            if (paymentMethod == "paypal" && request.Amount < PaypalExchangeRate)
            {
                return BadRequest(new { Success = false, Message = "Số tiền tối thiểu cho PayPal là 25,000 VNĐ (tương đương $1)" });
            }

            if (paymentMethod == "momo" && request.Amount < 10000)
            {
                return BadRequest(new { Success = false, Message = "Số tiền tối thiểu cho MoMo là 10,000 VNĐ" });
            }

            if (paymentMethod != "test_card" && paymentMethod != "paypal" && paymentMethod != "momo")
            {
                return BadRequest(new { Success = false, Message = "Phương thức thanh toán không hợp lệ" });
            }

            var status = paymentMethod == "test_card" ? "completed" : "pending";

            var description = paymentMethod == "test_card"
                ? "Nạp tiền - Hệ thống"
                : $"Nạp tiền - {paymentMethod.ToUpper()}";

            var transaction = new Transaction
            {
                UserId = userId,
                Type = "deposit",
                Amount = request.Amount,
                Status = status,
                Description = description,
                ReferenceId = paymentMethod.ToUpper() + "_" + DateTime.UtcNow.Ticks,
                PaymentMethod = paymentMethod,
                CreatedAt = DateTime.UtcNow
            };

            await _transactionCollection.InsertOneAsync(transaction);

            double? newBalance = null;
            if (status == "completed")
            {
                var update = Builders<User>.Update.Inc(x => x.Balance, request.Amount);
                await _userCollection.UpdateOneAsync(x => x.Id == userId, update);
                newBalance = (await _userCollection.Find(x => x.Id == userId).FirstOrDefaultAsync())?.Balance;
            }

            if (paymentMethod == "paypal")
            {
                var paypalResult = await CreatePayPalOrderAsync(transaction);
                if (!paypalResult.Success)
                {
                    var failUpdate = Builders<Transaction>.Update
                        .Set(x => x.Status, "failed")
                        .Set(x => x.Metadata, new { gateway = "paypal", error = paypalResult.ErrorMessage });
                    await _transactionCollection.UpdateOneAsync(x => x.Id == transaction.Id, failUpdate);
                    return StatusCode(502, new { Success = false, Message = paypalResult.ErrorMessage ?? "Không thể tạo giao dịch PayPal." });
                }

                return Ok(new
                {
                    success = true,
                    message = "Đã tạo yêu cầu nạp tiền. Vui lòng thanh toán qua PayPal.",
                    status = status,
                    referenceId = transaction.ReferenceId,
                    paymentMethod = transaction.PaymentMethod,
                    paymentUrl = paypalResult.PaymentUrl
                });
            }

            if (paymentMethod == "momo")
            {
                var momoResult = await CreateMomoPaymentAsync(transaction);
                if (!momoResult.Success)
                {
                    var failUpdate = Builders<Transaction>.Update
                        .Set(x => x.Status, "failed")
                        .Set(x => x.Metadata, new { gateway = "momo", error = momoResult.ErrorMessage });
                    await _transactionCollection.UpdateOneAsync(x => x.Id == transaction.Id, failUpdate);
                    return StatusCode(502, new { Success = false, Message = momoResult.ErrorMessage ?? "Không thể tạo giao dịch MoMo." });
                }

                return Ok(new
                {
                    success = true,
                    message = "Đã tạo yêu cầu nạp tiền. Vui lòng thanh toán qua MoMo.",
                    status = status,
                    referenceId = transaction.ReferenceId,
                    paymentMethod = transaction.PaymentMethod,
                    paymentUrl = momoResult.PaymentUrl
                });
            }

            var message = status == "completed"
                ? "Nạp tiền thành công!"
                : "Đã tạo yêu cầu nạp tiền. Vui lòng chờ xác nhận thanh toán.";

            return Ok(new
            {
                success = true,
                message = message,
                status = status,
                referenceId = transaction.ReferenceId,
                paymentMethod = transaction.PaymentMethod,
                newBalance = newBalance
            });
        }

        [HttpPost("cancel/{id}")]
        public async Task<IActionResult> CancelTransaction(string id)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            var transaction = await _transactionCollection.Find(x => x.Id == id && x.UserId == userId).FirstOrDefaultAsync();
            
            if (transaction == null) return NotFound();
            if (transaction.Status != "pending") return BadRequest(new { Success = false, Message = "Chỉ có thể hủy giao dịch đang chờ" });

            var update = Builders<Transaction>.Update.Set(x => x.Status, "cancelled");
            await _transactionCollection.UpdateOneAsync(x => x.Id == id, update);

            return Ok(new { Success = true, Message = "Đã hủy giao dịch" });
        }

        private string GetBackendBaseUrl()
        {
            return $"{Request.Scheme}://{Request.Host}";
        }

        private static string ComputeHmacSha256(string data, string secret)
        {
            using var hmac = new HMACSHA256(Encoding.UTF8.GetBytes(secret));
            var hash = hmac.ComputeHash(Encoding.UTF8.GetBytes(data));
            return Convert.ToHexString(hash).ToLowerInvariant();
        }

        private async Task<(bool Success, string? PaymentUrl, string? ErrorMessage)> CreatePayPalOrderAsync(Transaction transaction)
        {
            var paypalBase = PaymentConst.PAYPAL_MODE == "live"
                ? "https://api-m.paypal.com"
                : "https://api-m.sandbox.paypal.com";

            var amountUsd = Math.Round(transaction.Amount / PaypalExchangeRate, 2, MidpointRounding.AwayFromZero);
            if (amountUsd < 1)
            {
                return (false, null, "Số tiền tối thiểu cho PayPal là $1.");
            }

            var client = _httpClientFactory.CreateClient();
            var credentials = Convert.ToBase64String(Encoding.ASCII.GetBytes($"{PaymentConst.PAYPAL_CLIENT_ID}:{PaymentConst.PAYPAL_CLIENT_SECRET}"));
            client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Basic", credentials);

            var tokenResponse = await client.PostAsync(
                $"{paypalBase}/v1/oauth2/token",
                new FormUrlEncodedContent(new[] { new KeyValuePair<string, string>("grant_type", "client_credentials") })
            );

            if (!tokenResponse.IsSuccessStatusCode)
            {
                return (false, null, "Không thể lấy access token từ PayPal.");
            }

            using var tokenDoc = JsonDocument.Parse(await tokenResponse.Content.ReadAsStringAsync());
            var accessToken = tokenDoc.RootElement.GetProperty("access_token").GetString();
            if (string.IsNullOrWhiteSpace(accessToken))
            {
                return (false, null, "Access token PayPal không hợp lệ.");
            }

            client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Bearer", accessToken);

            var backendBaseUrl = GetBackendBaseUrl();
            var referenceId = transaction.ReferenceId ?? string.Empty;
            var returnUrl = $"{backendBaseUrl}/api/payment/paypal/return?referenceId={Uri.EscapeDataString(referenceId)}";
            var cancelUrl = $"{backendBaseUrl}/api/payment/paypal/cancel?referenceId={Uri.EscapeDataString(referenceId)}";

            var createPayload = new
            {
                intent = "CAPTURE",
                purchase_units = new[]
                {
                    new
                    {
                        amount = new
                        {
                            currency_code = "USD",
                            value = amountUsd.ToString("0.00", CultureInfo.InvariantCulture)
                        },
                        custom_id = referenceId
                    }
                },
                application_context = new
                {
                    return_url = returnUrl,
                    cancel_url = cancelUrl,
                    brand_name = "Mirai Store",
                    user_action = "PAY_NOW"
                }
            };

            var createResponse = await client.PostAsJsonAsync($"{paypalBase}/v2/checkout/orders", createPayload);
            if (!createResponse.IsSuccessStatusCode)
            {
                return (false, null, "Không thể tạo đơn PayPal.");
            }

            using var orderDoc = JsonDocument.Parse(await createResponse.Content.ReadAsStringAsync());
            var orderId = orderDoc.RootElement.GetProperty("id").GetString();
            string? approveUrl = null;

            if (orderDoc.RootElement.TryGetProperty("links", out var links))
            {
                foreach (var link in links.EnumerateArray())
                {
                    if (link.TryGetProperty("rel", out var rel) && rel.GetString() == "approve")
                    {
                        approveUrl = link.GetProperty("href").GetString();
                        break;
                    }
                }
            }

            if (string.IsNullOrWhiteSpace(approveUrl))
            {
                return (false, null, "Không tìm thấy URL thanh toán PayPal.");
            }

            var metadataUpdate = Builders<Transaction>.Update.Set(x => x.Metadata, new
            {
                gateway = "paypal",
                orderId,
                amountUsd
            });
            await _transactionCollection.UpdateOneAsync(x => x.Id == transaction.Id, metadataUpdate);

            return (true, approveUrl, null);
        }

        private async Task<(bool Success, string? PaymentUrl, string? ErrorMessage)> CreateMomoPaymentAsync(Transaction transaction)
        {
            var client = _httpClientFactory.CreateClient();
            var momoBase = PaymentConst.MOMO_ENDPOINT.TrimEnd('/');
            var amount = Convert.ToInt64(Math.Round(transaction.Amount, 0, MidpointRounding.AwayFromZero));
            var requestId = Guid.NewGuid().ToString("N");
            var orderId = transaction.ReferenceId ?? string.Empty;
            var orderInfo = "Nap tien vi";
            var backendBaseUrl = GetBackendBaseUrl();
            var referenceId = transaction.ReferenceId ?? string.Empty;
            var redirectUrl = $"{backendBaseUrl}/api/payment/momo/return?referenceId={Uri.EscapeDataString(referenceId)}";
            var ipnUrl = $"{backendBaseUrl}/api/payment/momo/ipn?referenceId={Uri.EscapeDataString(referenceId)}";
            var extraData = string.Empty;
            var requestType = "captureWallet";

            var rawSignature =
                $"accessKey={PaymentConst.MOMO_ACCESS_KEY}" +
                $"&amount={amount}" +
                $"&extraData={extraData}" +
                $"&ipnUrl={ipnUrl}" +
                $"&orderId={orderId}" +
                $"&orderInfo={orderInfo}" +
                $"&partnerCode={PaymentConst.MOMO_PARTNER_CODE}" +
                $"&redirectUrl={redirectUrl}" +
                $"&requestId={requestId}" +
                $"&requestType={requestType}";

            var signature = ComputeHmacSha256(rawSignature, PaymentConst.MOMO_SECRET_KEY);

            var payload = new
            {
                partnerCode = PaymentConst.MOMO_PARTNER_CODE,
                accessKey = PaymentConst.MOMO_ACCESS_KEY,
                requestId,
                amount = amount.ToString(CultureInfo.InvariantCulture),
                orderId,
                orderInfo,
                redirectUrl,
                ipnUrl,
                requestType,
                extraData,
                signature,
                lang = "vi"
            };

            var response = await client.PostAsJsonAsync($"{momoBase}/v2/gateway/api/create", payload);
            if (!response.IsSuccessStatusCode)
            {
                return (false, null, "Không thể tạo giao dịch MoMo.");
            }

            using var momoDoc = JsonDocument.Parse(await response.Content.ReadAsStringAsync());
            var resultCode = momoDoc.RootElement.TryGetProperty("resultCode", out var codeEl) ? codeEl.GetInt32() : -1;
            var message = momoDoc.RootElement.TryGetProperty("message", out var messageEl) ? messageEl.GetString() : null;
            var payUrl = momoDoc.RootElement.TryGetProperty("payUrl", out var payUrlEl) ? payUrlEl.GetString() : null;

            if (resultCode != 0 || string.IsNullOrWhiteSpace(payUrl))
            {
                return (false, null, message ?? "MoMo trả về lỗi tạo giao dịch.");
            }

            var metadataUpdate = Builders<Transaction>.Update.Set(x => x.Metadata, new
            {
                gateway = "momo",
                orderId,
                requestId,
                amount
            });
            await _transactionCollection.UpdateOneAsync(x => x.Id == transaction.Id, metadataUpdate);

            return (true, payUrl, null);
        }
    }
}
