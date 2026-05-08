using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using MongoDB.Driver;

namespace Mirai_Store.Internal.Repositories
{
    public class DiscountRepository : IDiscountRepository
    {
        private readonly IMongoCollection<DiscountCode> _collection;
        public DiscountRepository(MongoDbContext dbContext) => _collection = dbContext.DiscountCodes;

        public async Task<List<DiscountCode>> GetAll() => await _collection.Find(_ => true).ToListAsync();
        public async Task<DiscountCode> GetById(string id) => await _collection.Find(x => x.Id == id).FirstOrDefaultAsync();
        public async Task<DiscountCode> GetByCode(string code) => await _collection.Find(x => x.Code == code).FirstOrDefaultAsync();
        public async Task Create(DiscountCode discount) => await _collection.InsertOneAsync(discount);
        public async Task Update(string id, DiscountCode discount) => await _collection.ReplaceOneAsync(x => x.Id == id, discount);
        public async Task Remove(string id) => await _collection.DeleteOneAsync(x => x.Id == id);
    }
}