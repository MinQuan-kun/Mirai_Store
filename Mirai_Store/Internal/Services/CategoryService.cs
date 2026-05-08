using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Category;
using Mirai_Store.Models.Common;

namespace Mirai_Store.Internal.Services
{
    public class CategoryService : ICategoryService
    {
        private readonly ICategoryRepository _categoryRepo;
        private readonly IGameRepository _gameRepo;

        public CategoryService(ICategoryRepository categoryRepo, IGameRepository gameRepo)
        {
            _categoryRepo = categoryRepo;
            _gameRepo = gameRepo;
        }

        public async Task<BaseResponse> GetAllCategoriesAsync()
        {
            var entities = await _categoryRepo.GetAll();

            
            var responseDtos = entities.Select(e => new CategoryResponse
            {
                Id = e.Id!,
                Name = e.Name,
                Slug = e.Slug,
                Description = e.Description,
                IsActive = e.IsActive
            }).ToList();

            return new BaseResponse { Success = true, Data = responseDtos };
        }

        public async Task<BaseResponse> CreateCategoryAsync(CategoryRequest request)
        {
            var entity = new Category
            {
                Name = request.Name,
                Slug = request.Name.ToLower().Replace(" ", "-"),
                Description = request.Description,
                IsActive = request.IsActive
            };

            await _categoryRepo.Create(entity);
            return new BaseResponse { Success = true, Message = "Thêm danh mục thành công!" };
        }

        public async Task<BaseResponse> UpdateCategoryAsync(string id, CategoryRequest request)
        {
            var entity = await _categoryRepo.GetById(id);
            if (entity == null) return new BaseResponse { Success = false, Message = "Không tìm thấy danh mục." };

            entity.Name = request.Name;
            entity.Slug = request.Name.ToLower().Replace(" ", "-");
            entity.Description = request.Description;
            entity.IsActive = request.IsActive;

            await _categoryRepo.Update(id, entity);
            return new BaseResponse { Success = true, Message = "Cập nhật thành công!" };
        }

        public async Task<BaseResponse> DeleteCategoryAsync(string id)
        {
            var entity = await _categoryRepo.GetById(id);
            if (entity == null) return new BaseResponse { Success = false, Message = "Không tìm thấy danh mục." };

            
            var games = await _gameRepo.GetAll();
            if (games.Any(g => g.CategoryIds.Contains(id)))
            {
                return new BaseResponse { Success = false, Message = "Không thể xóa! Danh mục này đang chứa game." };
            }

            await _categoryRepo.Remove(id);
            return new BaseResponse { Success = true, Message = "Đã xóa danh mục!" };
        }
    }
}