using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using Mirai_Store.Models;
using MongoDB.Driver;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/categories")]
    [Authorize]
    public class AdminCategoriesController : ControllerBase
    {
        private readonly IMongoCollection<Category> _categoryCollection;
        private readonly IMongoCollection<Game> _gameCollection;

        public AdminCategoriesController(MongoDbContext dbContext)
        {
            _categoryCollection = dbContext.Categories;
            _gameCollection = dbContext.Games;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var entities = await _categoryCollection.Find(_ => true).ToListAsync();
            var responseDtos = entities.Select(e => new CategoryResponse
            {
                Id = e.Id!,
                Name = e.Name,
                Slug = e.Slug,
                Description = e.Description,
                IsActive = e.IsActive
            }).ToList();

            return Ok(new BaseResponse { Success = true, Data = responseDtos });
        }

        [HttpPost]
        public async Task<IActionResult> Create([FromBody] CategoryRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });

            var entity = new Category
            {
                Name = request.Name,
                Slug = request.Name.ToLower().Replace(" ", "-"),
                Description = request.Description,
                IsActive = request.IsActive
            };

            await _categoryCollection.InsertOneAsync(entity);
            return Ok(new BaseResponse { Success = true, Message = "Thêm danh mục thành công!" });
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> Update(string id, [FromBody] CategoryRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });

            var entity = await _categoryCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (entity == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy danh mục." });

            entity.Name = request.Name;
            entity.Slug = request.Name.ToLower().Replace(" ", "-");
            entity.Description = request.Description;
            entity.IsActive = request.IsActive;

            await _categoryCollection.ReplaceOneAsync(x => x.Id == id, entity);
            return Ok(new BaseResponse { Success = true, Message = "Cập nhật thành công!" });
        }

        [HttpDelete("{id}")]
        public async Task<IActionResult> Delete(string id)
        {
            var entity = await _categoryCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (entity == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy danh mục." });

            // Logic kiểm tra xem có game nào dùng danh mục này không
            var hasGames = await _gameCollection.Find(g => g.CategoryIds.Contains(id)).AnyAsync();
            if (hasGames)
            {
                return BadRequest(new BaseResponse { Success = false, Message = "Không thể xóa! Danh mục này đang chứa game." });
            }

            await _categoryCollection.DeleteOneAsync(x => x.Id == id);
            return Ok(new BaseResponse { Success = true, Message = "Đã xóa danh mục!" });
        }
    }
}