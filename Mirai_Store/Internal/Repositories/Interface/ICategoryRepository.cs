using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface ICategoryRepository
    {
        Task<List<Category>> GetAll();
        Task<Category> GetBySlug(string slug);
        Task<Category> GetById(string id);
        Task Create(Category category);
        Task Update(string id, Category category);
        Task Remove(string id);
    }
}