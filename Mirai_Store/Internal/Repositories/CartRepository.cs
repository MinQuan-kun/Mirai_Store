using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using MongoDB.Driver;

namespace Mirai_Store.Internal.Repositories
{
    public class CartRepository : ICartRepository
    {
        private readonly MongoDbContext _context;

        public CartRepository(MongoDbContext context)
        {
            _context = context;
        }

        public async Task<IEnumerable<Cart>> GetByUserId(string userId)
        {
            return await _context.Carts.Find(c => c.UserId == userId).ToListAsync();
        }

        public async Task<Cart?> GetByUserAndGame(string userId, string gameId)
        {
            return await _context.Carts.Find(c => c.UserId == userId && c.GameId == gameId).FirstOrDefaultAsync();
        }

        public async Task Create(Cart cart)
        {
            await _context.Carts.InsertOneAsync(cart);
        }

        public async Task Update(string id, Cart cart)
        {
            await _context.Carts.ReplaceOneAsync(c => c.Id == id, cart);
        }

        public async Task Delete(string id)
        {
            await _context.Carts.DeleteOneAsync(c => c.Id == id);
        }

        public async Task Clear(string userId)
        {
            await _context.Carts.DeleteManyAsync(c => c.UserId == userId);
        }

        public async Task<long> Count(string userId)
        {
            return await _context.Carts.CountDocumentsAsync(c => c.UserId == userId);
        }
    }
}
