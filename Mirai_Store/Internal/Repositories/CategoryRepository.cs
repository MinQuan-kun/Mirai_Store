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
        public async Task<Category> GetById(string id) => await _collection.Find(x => x.Id == id).FirstOrDefaultAsync();
        public async Task Create(Category category) => await _collection.InsertOneAsync(category);
        public async Task Update(string id, Category category) => await _collection.ReplaceOneAsync(x => x.Id == id, category);
        public async Task Remove(string id) => await _collection.DeleteOneAsync(x => x.Id == id);
    }
}