using MongoDB.Bson;
using MongoDB.Driver;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.DataContext
{
    public class MongoDbContext
    {
        private readonly IMongoDatabase _database;

        public MongoDbContext()
        {
            var client = new MongoClient(DatabaseConst.ConnectionString);
            _database = client.GetDatabase(DatabaseConst.DatabaseName);
        }

        
        public IMongoCollection<User> User
            => _database.GetCollection<User>("users");
        public IMongoCollection<Game> Games
            => _database.GetCollection<Game>("games");
        public IMongoCollection<Category> Categories
            => _database.GetCollection<Category>("categories");
        public IMongoCollection<Order> Orders
            => _database.GetCollection<Order>("orders");
        public IMongoCollection<OrderItem> OrderItems
            => _database.GetCollection<OrderItem>("orderItems");
        public IMongoCollection<DiscountCode> DiscountCodes
            => _database.GetCollection<DiscountCode>("discountCodes");
        public IMongoCollection<Transaction> Transactions
            => _database.GetCollection<Transaction>("transactions");
        public IMongoCollection<Wishlist> Wishlists
            => _database.GetCollection<Wishlist>("wishlists");


        public async Task<bool> CheckConnectionAsync()
        {
            try
            {
                var command = new BsonDocument("ping", 1);
                await _database.RunCommandAsync<BsonDocument>(command);
                return true;
            }
            catch (Exception)
            {
                return false;
            }
        }
    }
}