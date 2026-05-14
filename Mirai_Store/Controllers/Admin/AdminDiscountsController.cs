using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/discounts")]
    [Authorize]
    public class AdminDiscountsController : ControllerBase
    {
        private readonly IMongoCollection<DiscountCode> _discountCollection;

        public AdminDiscountsController(MongoDbContext dbContext)
        {
            _discountCollection = dbContext.DiscountCodes;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var entities = await _discountCollection.Find(_ => true).ToListAsync();
            var responseDtos = entities.Select(e => new DiscountResponse
            {
                Id = e.Id!,
                Code = e.Code,
                Type = e.Type,
                Value = e.Value,
                ExpiresAt = e.ExpiresAt,
                IsActive = e.IsActive ?? true,
                UsageLimit = e.UsageLimit,
                UsedCount = e.UsedCount
            }).ToList();

            return Ok(new BaseResponse { Success = true, Data = responseDtos });
        }

        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(string id)
        {
            var entity = await _discountCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (entity == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy." });

            var response = new DiscountResponse
            {
                Id = entity.Id!,
                Code = entity.Code,
                Type = entity.Type,
                Value = entity.Value,
                ExpiresAt = entity.ExpiresAt,
                IsActive = entity.IsActive ?? true,
                UsageLimit = entity.UsageLimit,
                UsedCount = entity.UsedCount
            };

            return Ok(new BaseResponse { Success = true, Data = response });
        }

        [HttpPost]
        public async Task<IActionResult> Create([FromBody] DiscountRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });

            var code = request.Code.ToUpper();
            var existing = await _discountCollection.Find(x => x.Code == code).FirstOrDefaultAsync();
            if (existing != null)
                return BadRequest(new BaseResponse { Success = false, Message = "Mã giảm giá đã tồn tại." });

            var newEntity = new DiscountCode
            {
                Code = code,
                Type = request.Type,
                Value = request.Value,
                ExpiresAt = request.ExpiresAt,
                IsActive = request.IsActive,
                UsageLimit = request.UsageLimit,
                UsedCount = 0
            };

            await _discountCollection.InsertOneAsync(newEntity);
            return Ok(new BaseResponse { Success = true, Message = "Tạo mã giảm giá thành công!" });
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> Update(string id, [FromBody] DiscountRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });

            var entity = await _discountCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (entity == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy." });

            entity.Code = request.Code.ToUpper();
            entity.Type = request.Type;
            entity.Value = request.Value;
            entity.ExpiresAt = request.ExpiresAt;
            entity.IsActive = request.IsActive;
            entity.UsageLimit = request.UsageLimit;
            entity.UsedCount = entity.UsedCount ?? 0;

            await _discountCollection.ReplaceOneAsync(x => x.Id == id, entity);
            return Ok(new BaseResponse { Success = true, Message = "Cập nhật thành công!" });
        }

        [HttpDelete("{id}")]
        public async Task<IActionResult> Delete(string id)
        {
            var entity = await _discountCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (entity == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy." });

            await _discountCollection.DeleteOneAsync(x => x.Id == id);
            return Ok(new BaseResponse { Success = true, Message = "Đã xóa mã giảm giá!" });
        }
    }
}