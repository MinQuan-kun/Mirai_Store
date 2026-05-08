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
    [Route("api/wallet")]
    [Authorize]
    public class WalletController : ControllerBase
    {
        private readonly IMongoCollection<Transaction> _transactionCollection;
        private readonly IMongoCollection<User> _userCollection;

        public WalletController(MongoDbContext dbContext)
        {
            _transactionCollection = dbContext.Transactions;
            _userCollection = dbContext.User;
        }

        [HttpGet("history")]
        public async Task<IActionResult> GetHistory([FromQuery] string? filter)
        {
            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var filterBuilder = Builders<Transaction>.Filter;
            var mongoFilter = filterBuilder.Eq(x => x.UserId, userId);

            if (!string.IsNullOrEmpty(filter) && filter != "all")
            {
                mongoFilter &= filterBuilder.Eq(x => x.Type, filter);
            }

            var transactions = await _transactionCollection.Find(mongoFilter)
                .SortByDescending(x => x.CreatedAt)
                .ToListAsync();

            return Ok(new { Success = true, Data = transactions });
        }

        [HttpPost("deposit")]
        public async Task<IActionResult> Deposit([FromBody] DepositRequest request)
        {
            if (request.Amount < 1000) return BadRequest(new { Success = false, Message = "Số tiền tối thiểu là 1,000 VNĐ" });

            var userId = User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var transaction = new Transaction
            {
                UserId = userId,
                Type = "deposit",
                Amount = request.Amount,
                Status = "completed",
                Description = "Nạp tiền - Hệ thống",
                ReferenceId = "DEP" + DateTime.Now.Ticks,
                PaymentMethod = "test_card",
                CreatedAt = DateTime.UtcNow
            };

            await _transactionCollection.InsertOneAsync(transaction);

            var update = Builders<User>.Update.Inc(x => x.Balance, request.Amount);
            await _userCollection.UpdateOneAsync(x => x.Id == userId, update);

            return Ok(new { Success = true, Message = "Nạp tiền thành công!", NewBalance = (await _userCollection.Find(x => x.Id == userId).FirstOrDefaultAsync())?.Balance });
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
    }
}
