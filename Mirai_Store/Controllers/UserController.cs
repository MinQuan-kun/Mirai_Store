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
    public class UserController : ControllerBase
    {
        private readonly IMongoCollection<User> _userCollection;
        private readonly IMongoCollection<Transaction> _transactionCollection;
        private readonly IMongoCollection<Order> _orderCollection;
        private readonly IMongoCollection<OrderItem> _orderItemCollection;
        private readonly IMongoCollection<Game> _gameCollection;

        public UserController(MongoDbContext dbContext)
        {
            _userCollection = dbContext.User;
            _transactionCollection = dbContext.Transactions;
            _orderCollection = dbContext.Orders;
            _orderItemCollection = dbContext.OrderItems;
            _gameCollection = dbContext.Games;
        }

        private string? GetUserId()
        {
            return User.FindFirstValue(ClaimTypes.NameIdentifier) ?? User.FindFirstValue(JwtRegisteredClaimNames.Sub);
        }

        /// <summary>
        /// Lấy thông tin profile người dùng bao gồm User Info, Purchased Games, và Transactions
        /// </summary>
        [HttpGet("profile")]
        public async Task<IActionResult> GetProfile()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId))
            {
                return Unauthorized();
            }

            var user = await _userCollection.Find(x => x.Id == userId).FirstOrDefaultAsync();
            if (user == null)
            {
                return BadRequest(new ProfileResponse
                {
                    Success = false,
                    Message = "User not found"
                });
            }

            // Map user details
            var userDto = new UserDto
            {
                Id = user.Id!,
                Name = user.Name,
                Email = user.Email,
                Avatar = user.Avatar
            };

            // Fetch transactions
            var transactions = await _transactionCollection.Find(x => x.UserId == userId).SortByDescending(x => x.CreatedAt).ToListAsync();
            var transactionDtos = transactions.Select(t => new TransactionDto
            {
                Id = t.Id!,
                Type = t.Type,
                Amount = t.Amount,
                Status = t.Status,
                PaymentMethod = t.PaymentMethod,
                OrderId = t.OrderId,
                CreatedAt = t.CreatedAt
            }).ToList();

            // Fetch purchased games
            var orders = await _orderCollection.Find(x => x.UserId == userId).ToListAsync();
            var completedOrderIds = orders.Where(o => o.Status == "completed").Select(o => o.Id!).ToList();
            
            var purchasedGamesDtos = new List<PurchasedGameDto>();

            if (completedOrderIds.Any())
            {
                var orderItems = await _orderItemCollection.Find(x => completedOrderIds.Contains(x.OrderId)).ToListAsync();
                var gameIds = orderItems.Select(oi => oi.GameId).Distinct().ToList();

                if (gameIds.Any())
                {
                    var games = await _gameCollection.Find(x => gameIds.Contains(x.Id)).ToListAsync();
                    purchasedGamesDtos = games.Select(g => new PurchasedGameDto
                    {
                        Id = g.Id!,
                        Name = g.Name,
                        Image = g.Image,
                        Publisher = g.Publisher,
                        DownloadLink = g.DownloadLink
                    }).ToList();
                }
            }

            return Ok(new ProfileResponse
            {
                Success = true,
                Message = "Profile retrieved successfully",
                User = userDto,
                PurchasedGames = purchasedGamesDtos,
                Transactions = transactionDtos
            });
        }
    }
}
