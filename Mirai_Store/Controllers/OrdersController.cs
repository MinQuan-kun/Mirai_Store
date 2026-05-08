using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using Mirai_Store.Models;
using MongoDB.Driver;
using System.Security.Claims;

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

        public OrdersController(MongoDbContext dbContext)
        {
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
            string? discountId = null;

            if (!string.IsNullOrEmpty(request.DiscountCode))
            {
                var discount = await _discountCollection.Find(x => x.Code == request.DiscountCode.ToUpper()).FirstOrDefaultAsync();
                if (discount != null && discount.ExpiresAt > DateTime.UtcNow)
                {
                    discountAmount = discount.Type == "percentage" ? (subtotal * discount.Value / 100) : discount.Value;
                    discountId = discount.Id;
                }
                else
                {
                    return BadRequest(new { Success = false, Message = "Mã giảm giá không hợp lệ" });
                }
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
                PaymentMethod = "wallet"
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

        [HttpGet("my-orders")]
        public async Task<IActionResult> MyOrders()
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var orders = await _orderCollection.Find(x => x.UserId == userId).SortByDescending(x => x.Id).ToListAsync();
            return Ok(new { Success = true, Data = orders });
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
}
