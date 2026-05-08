using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;
using System.Security.Claims;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/wishlist")]
    [Authorize]
    public class WishlistController : ControllerBase
    {
        private readonly IMongoCollection<Wishlist> _wishlistCollection;
        private readonly IMongoCollection<Game> _gameCollection;

        public WishlistController(MongoDbContext dbContext)
        {
            _wishlistCollection = dbContext.Wishlists;
            _gameCollection = dbContext.Games;
        }

        [HttpGet]
        public async Task<IActionResult> GetWishlist()
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var wishlistItems = await _wishlistCollection.Find(x => x.UserId == userId).ToListAsync();
            var games = new List<Game>();

            foreach (var item in wishlistItems)
            {
                var game = await _gameCollection.Find(x => x.Id == item.GameId && x.IsActive == true).FirstOrDefaultAsync();
                if (game != null) games.Add(game);
            }

            return Ok(new { Success = true, Data = games });
        }

        [HttpPost("add/{gameId}")]
        public async Task<IActionResult> AddToWishlist(string gameId)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var exists = await _wishlistCollection.Find(x => x.UserId == userId && x.GameId == gameId).AnyAsync();
            if (exists) return BadRequest(new { Success = false, Message = "Game đã có trong danh sách yêu thích" });

            await _wishlistCollection.InsertOneAsync(new Wishlist
            {
                UserId = userId,
                GameId = gameId
            });

            return Ok(new { Success = true, Message = "Đã thêm vào danh sách yêu thích" });
        }

        [HttpDelete("remove/{gameId}")]
        public async Task<IActionResult> RemoveFromWishlist(string gameId)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            await _wishlistCollection.DeleteOneAsync(x => x.UserId == userId && x.GameId == gameId);

            return Ok(new { Success = true, Message = "Đã xóa khỏi danh sách yêu thích" });
        }
    }
}
