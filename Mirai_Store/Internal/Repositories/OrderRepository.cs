using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using MongoDB.Driver;


namespace Mirai_Store.Internal.Repositories
{
    public class OrderRepository : IOrderRepository
    {
        private readonly IMongoCollection<Order> _collection;
        public OrderRepository(MongoDbContext dbContext) => _collection = dbContext.Orders;
        public async Task<List<Order>> GetAll() => await _collection.Find(_ => true).ToListAsync();
        public async Task Create(Order order) => await _collection.InsertOneAsync(order);
        public async Task Update(string id, Order order) => await _collection.ReplaceOneAsync(x => x.Id == id, order);      
        public async Task Remove(string id) => await _collection.DeleteOneAsync(x => x.Id == id);
        public async Task CreateOrder(Order order) => await _collection.InsertOneAsync(order);
        public async Task CreateOrderItems(List<OrderItem> items)
        {
            var orderItemCollection = new MongoDbContext().OrderItems;
            await orderItemCollection.InsertManyAsync(items);
        }
        public async Task<List<Order>> GetByUserId(string userId) => await _collection.Find(x => x.UserId == userId).ToListAsync();
        public async Task<Order> GetById(string id) => await _collection.Find(x => x.Id == id).FirstOrDefaultAsync();
        
    }
}
