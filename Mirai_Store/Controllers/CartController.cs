using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Models;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using MongoDB.Driver;
using System.Security.Claims;
using System.IdentityModel.Tokens.Jwt;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [Authorize]
    public class CartController : ControllerBase
    {
        private readonly IMongoCollection<Cart> _cartCollection;
        private readonly IMongoCollection<Game> _gameCollection;

        public CartController(MongoDbContext dbContext)
        {
            _cartCollection = dbContext.Carts;
            _gameCollection = dbContext.Games;
        }

        private string? GetUserId()
        {
            return User.FindFirstValue(ClaimTypes.NameIdentifier) ?? User.FindFirstValue(JwtRegisteredClaimNames.Sub);
        }

        [HttpGet]
        public async Task<IActionResult> GetCart()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var cartItems = await _cartCollection.Find(x => x.UserId == userId).ToListAsync();
            var result = new List<CartItemDto>();
            decimal total = 0;

            foreach (var item in cartItems)
            {
                var game = await _gameCollection.Find(x => x.Id == item.GameId).FirstOrDefaultAsync();
                if (game != null)
                {
                    result.Add(new CartItemDto
                    {
                        Id = item.Id,
                        GameId = item.GameId,
                        GameName = game.Name,
                        GameImage = game.Image,
                        PriceAtTime = item.PriceAtTime,
                        Quantity = item.Quantity
                    });
                    total += item.PriceAtTime * item.Quantity;
                }
            }

            return Ok(new CartResponse
            {
                Items = result,
                Total = total
            });
        }

        [HttpPost("add")]
        public async Task<IActionResult> AddToCart([FromBody] AddToCartRequest request)
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var game = await _gameCollection.Find(x => x.Id == request.GameId).FirstOrDefaultAsync();
            if (game == null)
                return BadRequest(new CartActionResponse { Success = false, Message = "Game không tồn tại", Status = "error" });

            var existingItem = await _cartCollection.Find(x => x.UserId == userId && x.GameId == request.GameId).FirstOrDefaultAsync();
            if (existingItem != null)
                return Ok(new CartActionResponse { Success = true, Message = "Game này đã có trong giỏ hàng", Status = "info" });

            var cart = new Cart
            {
                UserId = userId,
                GameId = request.GameId,
                PriceAtTime = (decimal)game.Price,
                Quantity = 1
            };

            await _cartCollection.InsertOneAsync(cart);
            return Ok(new CartActionResponse { Success = true, Message = "Đã thêm vào giỏ hàng thành công!", Status = "success" });
        }

        [HttpDelete("remove/{cartId}")]
        public async Task<IActionResult> RemoveFromCart(string cartId)
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            await _cartCollection.DeleteOneAsync(x => x.Id == cartId);
            return Ok(new CartActionResponse { Success = true, Message = "Đã xóa khỏi giỏ hàng!", Status = "success" });
        }

        [HttpDelete("clear")]
        public async Task<IActionResult> ClearCart()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            await _cartCollection.DeleteManyAsync(x => x.UserId == userId);
            return Ok(new CartActionResponse { Success = true, Message = "Đã xóa tất cả sản phẩm khỏi giỏ hàng!", Status = "success" });
        }

        [HttpGet("count")]
        public async Task<IActionResult> GetCount()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Ok(new { Count = 0 });

            var count = await _cartCollection.CountDocumentsAsync(x => x.UserId == userId);
            return Ok(new { Count = count });
        }
    }

    public class AddToCartRequest
    {
        public string GameId { get; set; } = null!;
    }
}
