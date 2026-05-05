using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface ICategoryRepository
    {
        Task<List<Category>> GetAll();
        Task<Category> GetBySlug(string slug);
    }
}
