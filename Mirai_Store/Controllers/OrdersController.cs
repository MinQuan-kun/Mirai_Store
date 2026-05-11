using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Bson;
using MongoDB.Driver;
using System.Security.Claims;
using System.Text.RegularExpressions;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/orders")]
    [Authorize]
    public class OrdersController : ControllerBase
    {
        private readonly IMongoCollection<Order> _orderCollection;
        private readonly IMongoCollection<OrderItem> _orderItemCollection;
        private readonly IMongoCollection<Cart> _cartCollection;
        private readonly IMongoCollection<User> _userCollection;
        private readonly IMongoCollection<Game> _gameCollection;
        private readonly IMongoCollection<Transaction> _transactionCollection;
        private readonly IMongoCollection<DiscountCode> _discountCollection;
        private readonly MongoDbContext _dbContext;

        private static readonly string[] DiscountCollectionCandidates =
        {
            "discountCodes",
            "discountcodes",
            "discount_codes",
            "discounts"
        };

        public OrdersController(MongoDbContext dbContext)
        {
            _dbContext = dbContext;
            _orderCollection = dbContext.Orders;
            _orderItemCollection = dbContext.OrderItems;
            _cartCollection = dbContext.Carts;
            _userCollection = dbContext.User;
            _gameCollection = dbContext.Games;
            _transactionCollection = dbContext.Transactions;
            _discountCollection = dbContext.DiscountCodes;
        }

        [HttpGet("checkout-data")]
        public async Task<IActionResult> GetCheckoutData()
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var cartItems = await _cartCollection.Find(x => x.UserId == userId).ToListAsync();
            if (!cartItems.Any()) return BadRequest(new { Success = false, Message = "Giỏ hàng trống" });

            double subtotal = 0;
            var itemsWithDetails = new List<object>();

            foreach (var item in cartItems)
            {
                var game = await _gameCollection.Find(x => x.Id == item.GameId).FirstOrDefaultAsync();
                if (game != null)
                {
                    subtotal += game.Price * item.Quantity;
                    itemsWithDetails.Add(new { Game = game, Quantity = item.Quantity, PriceAtTime = game.Price });
                }
            }

            var user = await _userCollection.Find(x => x.Id == userId).FirstOrDefaultAsync();

            return Ok(new
            {
                Success = true,
                Data = new
                {
                    Items = itemsWithDetails,
                    Subtotal = subtotal,
                    UserBalance = user?.Balance ?? 0
                }
            });
        }

        [HttpPost("process-checkout")]
        public async Task<IActionResult> ProcessCheckout([FromBody] CheckoutRequest request)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var user = await _userCollection.Find(x => x.Id == userId).FirstOrDefaultAsync();
            if (user == null) return NotFound(new { Success = false, Message = "User không tồn tại" });

            var cartItems = await _cartCollection.Find(x => x.UserId == userId).ToListAsync();
            if (!cartItems.Any()) return BadRequest(new { Success = false, Message = "Giỏ hàng trống" });

            double subtotal = 0;
            foreach (var item in cartItems)
            {
                var game = await _gameCollection.Find(x => x.Id == item.GameId).FirstOrDefaultAsync();
                if (game != null) subtotal += game.Price * item.Quantity;
            }

            double discountAmount = 0;
            if (!string.IsNullOrEmpty(request.DiscountCode))
            {
                var resolved = await ResolveDiscountAsync(request.DiscountCode);

                if (resolved == null)
                {
                    return BadRequest(new { Success = false, Message = "Mã giảm giá không hợp lệ" });
                }

                var (isDiscountValid, invalidReason) = ValidateDiscountEligibility(resolved);
                if (!isDiscountValid)
                {
                    return BadRequest(new { Success = false, Message = invalidReason });
                }

                discountAmount = CalculateDiscountAmount(subtotal, resolved);
            }

            double finalTotal = subtotal - discountAmount;
            if (user.Balance < finalTotal) return BadRequest(new { Success = false, Message = "Số dư không đủ" });

            // Thực hiện giao dịch (Vì MongoDB không hỗ trợ transaction đa document nếu không chạy Replica Set, chúng ta xử lý tuần tự)
            // 1. Trừ tiền User
            var update = Builders<User>.Update.Inc(x => x.Balance, -finalTotal);
            await _userCollection.UpdateOneAsync(x => x.Id == userId, update);

            // 2. Tạo Order
            var order = new Order
            {
                UserId = userId,
                OrderNumber = "ORD" + DateTime.Now.Ticks,
                TotalAmount = finalTotal,
                Status = "completed",
                PaymentMethod = "wallet",
                CreatedAt = DateTime.UtcNow
            };
            await _orderCollection.InsertOneAsync(order);

            // 3. Tạo OrderItems
            foreach (var item in cartItems)
            {
                var game = await _gameCollection.Find(x => x.Id == item.GameId).FirstOrDefaultAsync();
                if (game != null)
                {
                    await _orderItemCollection.InsertOneAsync(new OrderItem
                    {
                        OrderId = order.Id!,
                        GameId = item.GameId,
                        Price = game.Price
                    });
                }
            }

            // 4. Tạo Transaction
            await _transactionCollection.InsertOneAsync(new Transaction
            {
                UserId = userId,
                Type = "purchase",
                Amount = finalTotal,
                Status = "completed",
                Description = "Mua game - Đơn hàng " + order.OrderNumber,
                OrderId = order.Id,
                PaymentMethod = "wallet",
                CreatedAt = DateTime.UtcNow
            });

            // 5. Xóa giỏ hàng
            await _cartCollection.DeleteManyAsync(x => x.UserId == userId);

            return Ok(new { Success = true, Message = "Thanh toán thành công!", OrderId = order.Id });
        }

        [HttpPost("validate-discount")]
        public async Task<IActionResult> ValidateDiscount([FromBody] ValidateDiscountRequest request)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            if (string.IsNullOrWhiteSpace(request.Code))
            {
                return Ok(new
                {
                    valid = false,
                    message = "Mã giảm giá không được để trống.",
                    discount_amount = 0,
                    final_total = request.Total
                });
            }

            var resolved = await ResolveDiscountAsync(request.Code);
            if (resolved == null)
            {
                return Ok(new
                {
                    valid = false,
                    message = "Mã giảm giá không hợp lệ.",
                    discount_amount = 0,
                    final_total = request.Total
                });
            }

            var (isDiscountValid, invalidReason) = ValidateDiscountEligibility(resolved);
            if (!isDiscountValid)
            {
                return Ok(new
                {
                    valid = false,
                    message = invalidReason,
                    discount_amount = 0,
                    final_total = request.Total
                });
            }

            var total = request.Total < 0 ? 0 : request.Total;
            var discountAmount = CalculateDiscountAmount(total, resolved);
            var finalTotal = total - discountAmount;

            return Ok(new
            {
                valid = true,
                message = $"Áp dụng mã {resolved.Code} thành công.",
                discount_code = resolved.Code,
                discount_amount = discountAmount,
                final_total = finalTotal
            });
        }

        private async Task<ResolvedDiscount?> ResolveDiscountAsync(string rawCode)
        {
            var normalizedCode = NormalizeDiscountCode(rawCode);
            if (string.IsNullOrEmpty(normalizedCode))
            {
                return null;
            }

            var regex = new BsonRegularExpression($"^{Regex.Escape(normalizedCode)}$", "i");
            var typedFilter = Builders<DiscountCode>.Filter.Regex(x => x.Code, regex);
            var typedDiscount = await _discountCollection.Find(typedFilter).FirstOrDefaultAsync();

            if (typedDiscount != null)
            {
                return new ResolvedDiscount
                {
                    Id = typedDiscount.Id,
                    Code = NormalizeDiscountCode(typedDiscount.Code) ?? normalizedCode,
                    Type = typedDiscount.Type,
                    Value = typedDiscount.Value,
                    ExpiresAt = typedDiscount.ExpiresAt,
                    IsActive = typedDiscount.IsActive ?? true,
                    UsageLimit = typedDiscount.UsageLimit,
                    UsedCount = typedDiscount.UsedCount ?? 0
                };
            }

            var bsonFilter = Builders<BsonDocument>.Filter.Regex("code", regex);
            foreach (var collectionName in DiscountCollectionCandidates)
            {
                var collection = _dbContext.GetBsonCollection(collectionName);
                var doc = await collection.Find(bsonFilter).FirstOrDefaultAsync();
                if (doc == null)
                {
                    continue;
                }

                var code = NormalizeDiscountCode(GetString(doc, "code")) ?? normalizedCode;
                var type = GetString(doc, "type") ?? "fixed";
                var value = GetDouble(doc, "value") ?? 0;
                var expiresAt = GetDateTime(doc, "expires_at") ?? DateTime.MinValue;
                var isActive = GetBool(doc, "is_active") ?? true;
                var usageLimit = GetInt(doc, "usage_limit");
                var usedCount = GetInt(doc, "used_count") ?? 0;

                return new ResolvedDiscount
                {
                    Id = doc.GetValue("_id", BsonNull.Value)?.ToString(),
                    Code = code,
                    Type = type,
                    Value = value,
                    ExpiresAt = expiresAt,
                    IsActive = isActive,
                    UsageLimit = usageLimit,
                    UsedCount = usedCount
                };
            }

            return null;
        }

        private static string? NormalizeDiscountCode(string? code)
        {
            if (string.IsNullOrWhiteSpace(code))
            {
                return null;
            }

            return code.Trim().ToUpperInvariant();
        }

        private static (bool IsValid, string InvalidReason) ValidateDiscountEligibility(ResolvedDiscount discount)
        {
            if (!discount.IsActive)
            {
                return (false, "Mã giảm giá đã bị vô hiệu hóa.");
            }

            if (discount.ExpiresAt <= DateTime.UtcNow)
            {
                return (false, "Mã giảm giá đã hết hạn.");
            }

            if (discount.UsageLimit.HasValue && discount.UsageLimit.Value >= 0 && discount.UsedCount >= discount.UsageLimit.Value)
            {
                return (false, "Mã giảm giá đã hết lượt sử dụng.");
            }

            return (true, string.Empty);
        }

        private static double CalculateDiscountAmount(double total, ResolvedDiscount discount)
        {
            var safeTotal = total < 0 ? 0 : total;
            var type = discount.Type?.Trim().ToLowerInvariant();
            var amount = type switch
            {
                "percentage" or "percent" => safeTotal * discount.Value / 100,
                "fixed" => discount.Value,
                _ => discount.Value
            };

            if (amount < 0)
            {
                return 0;
            }

            return amount > safeTotal ? safeTotal : amount;
        }

        private static string? GetString(BsonDocument doc, string field)
        {
            if (!doc.TryGetValue(field, out var value) || value.IsBsonNull)
            {
                return null;
            }

            return value.BsonType == BsonType.String ? value.AsString : value.ToString();
        }

        private static double? GetDouble(BsonDocument doc, string field)
        {
            if (!doc.TryGetValue(field, out var value) || value.IsBsonNull)
            {
                return null;
            }

            if (value.IsNumeric)
            {
                return value.ToDouble();
            }

            if (double.TryParse(value.ToString(), out var parsed))
            {
                return parsed;
            }

            return null;
        }

        private static int? GetInt(BsonDocument doc, string field)
        {
            if (!doc.TryGetValue(field, out var value) || value.IsBsonNull)
            {
                return null;
            }

            if (value.IsInt32)
            {
                return value.AsInt32;
            }

            if (value.IsInt64)
            {
                var asLong = value.AsInt64;
                if (asLong > int.MaxValue)
                {
                    return int.MaxValue;
                }

                if (asLong < int.MinValue)
                {
                    return int.MinValue;
                }

                return (int)asLong;
            }

            if (int.TryParse(value.ToString(), out var parsed))
            {
                return parsed;
            }

            return null;
        }

        private static bool? GetBool(BsonDocument doc, string field)
        {
            if (!doc.TryGetValue(field, out var value) || value.IsBsonNull)
            {
                return null;
            }

            if (value.IsBoolean)
            {
                return value.AsBoolean;
            }

            if (bool.TryParse(value.ToString(), out var parsed))
            {
                return parsed;
            }

            if (int.TryParse(value.ToString(), out var numericBool))
            {
                return numericBool != 0;
            }

            return null;
        }

        private static DateTime? GetDateTime(BsonDocument doc, string field)
        {
            if (!doc.TryGetValue(field, out var value) || value.IsBsonNull)
            {
                return null;
            }

            if (value.BsonType == BsonType.DateTime)
            {
                return value.ToUniversalTime();
            }

            if (DateTime.TryParse(value.ToString(), out var parsed))
            {
                return parsed.Kind == DateTimeKind.Utc ? parsed : parsed.ToUniversalTime();
            }

            return null;
        }

        private class ResolvedDiscount
        {
            public string? Id { get; init; }
            public string Code { get; init; } = string.Empty;
            public string Type { get; init; } = "fixed";
            public double Value { get; init; }
            public DateTime ExpiresAt { get; init; }
            public bool IsActive { get; init; } = true;
            public int? UsageLimit { get; init; }
            public int UsedCount { get; init; }
        }

        [HttpGet("my-orders")]
        public async Task<IActionResult> MyOrders()
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var orders = await _orderCollection.Find(x => x.UserId == userId).SortByDescending(x => x.Id).ToListAsync();
            var orderSummaries = new List<object>();

            foreach (var order in orders)
            {
                var itemsCount = await _orderItemCollection.CountDocumentsAsync(x => x.OrderId == order.Id);

                orderSummaries.Add(new
                {
                    order.Id,
                    order.OrderNumber,
                    order.TotalAmount,
                    order.Status,
                    CreatedAt = order.CreatedAt ?? DateTime.UtcNow,
                    itemsCount
                });
            }

            return Ok(new { Success = true, Data = orderSummaries });
        }

        [HttpGet("{id}")]
        public async Task<IActionResult> GetOrderDetails(string id)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            var order = await _orderCollection.Find(x => x.Id == id && x.UserId == userId).FirstOrDefaultAsync();
            if (order == null) return NotFound();

            var items = await _orderItemCollection.Find(x => x.OrderId == id).ToListAsync();
            var itemsWithGames = new List<object>();

            foreach (var item in items)
            {
                var game = await _gameCollection.Find(x => x.Id == item.GameId).FirstOrDefaultAsync();
                itemsWithGames.Add(new { Item = item, Game = game });
            }

            return Ok(new { Success = true, Data = new { Order = order, Items = itemsWithGames } });
        }
    }

    public class ValidateDiscountRequest
    {
        public string? Code { get; set; }
        public double Total { get; set; }
    }
}
