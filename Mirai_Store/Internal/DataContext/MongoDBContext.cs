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
            var connectionString = DatabaseConst.ConnectionString;
            var client = new MongoClient(connectionString);
            
            // Lấy Database Name từ Connection String (phần sau dấu / và trước dấu ?)
            var mongoUrl = new MongoUrl(connectionString);
            var databaseName = mongoUrl.DatabaseName ?? DatabaseConst.DatabaseName;
            
            _database = client.GetDatabase(databaseName);
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
            => _database.GetCollection<DiscountCode>("discount_codes");
        public IMongoCollection<Transaction> Transactions
            => _database.GetCollection<Transaction>("transactions");
        public IMongoCollection<Wishlist> Wishlists
            => _database.GetCollection<Wishlist>("wishlists");
        public IMongoCollection<Cart> Carts
            => _database.GetCollection<Cart>("carts");

        public IMongoCollection<BsonDocument> GetBsonCollection(string collectionName)
            => _database.GetCollection<BsonDocument>(collectionName);


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