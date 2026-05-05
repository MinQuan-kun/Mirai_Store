using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using MongoDB.Driver;

namespace Mirai_Store.Internal.Repositories
{
    public class CategoryRepository : ICategoryRepository
    {

        private readonly IMongoCollection<Category> _collection;
        public CategoryRepository(MongoDbContext dbContext) => _collection = dbContext.Categories;
        public async Task<List<Category>> GetAll() => await _collection.Find(x => x.IsActive).ToListAsync();
        public async Task<Category> GetBySlug(string slug) => await _collection.Find(x => x.Slug == slug).FirstOrDefaultAsync();
    }
}
