using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using MongoDB.Driver;

namespace Mirai_Store.Internal.Repositories
{
    public class GameRepository : IGameRepository
    {
        private readonly IMongoCollection<Game> _gamesCollection;

        public GameRepository(MongoDbContext dbContext)
        {
            _gamesCollection = dbContext.Games;
        }

        public async Task<List<Game>> GetAll() =>
            await _gamesCollection.Find(x => x.IsActive).ToListAsync();

        public async Task<Game> GetById(string id) =>
            await _gamesCollection.Find(x => x.Id == id).FirstOrDefaultAsync();

        public async Task<Game> GetBySlug(string slug) =>
            await _gamesCollection.Find(x => x.Slug == slug).FirstOrDefaultAsync();

        public async Task Create(Game game) =>
            await _gamesCollection.InsertOneAsync(game);

        public async Task Update(string id, Game game) =>
            await _gamesCollection.ReplaceOneAsync(x => x.Id == id, game);

        public async Task Remove(string id) =>
            await _gamesCollection.DeleteOneAsync(x => x.Id == id);
    }
}