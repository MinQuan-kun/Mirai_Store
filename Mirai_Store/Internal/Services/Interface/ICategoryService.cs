using Mirai_Store.Models.Category;
using Mirai_Store.Models.Common;

namespace Mirai_Store.Internal.Services.Interface
{
    public interface ICategoryService
    {
        Task<BaseResponse> GetAllCategoriesAsync();
        Task<BaseResponse> CreateCategoryAsync(CategoryRequest request);
        Task<BaseResponse> UpdateCategoryAsync(string id, CategoryRequest request);
        Task<BaseResponse> DeleteCategoryAsync(string id);
    }
}