using MongoDB.Driver;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
namespace Mirai_Store.Internal.Repositories
{
    public class UserRepository : IUserRepository
    {
        private readonly IMongoCollection<User> _userAccountsCollection;

        public UserRepository(MongoDbContext dbContext)
        {
            _userAccountsCollection = dbContext.User;
        }
        public async Task<List<User>> GetAll() =>
            await _userAccountsCollection.Find(_ => true).ToListAsync();
        public async Task<User> GetById(string id) =>
            await _userAccountsCollection.Find(x => x.Id == id).FirstOrDefaultAsync();

        public async Task<User> GetByUsername(string username) =>
            await _userAccountsCollection.Find(x => x.Name == username).FirstOrDefaultAsync();

        public async Task<User> GetByEmail(string email) =>
            await _userAccountsCollection.Find(x => x.Email == email).FirstOrDefaultAsync();

        public async Task Create(User newAccount) =>
            await _userAccountsCollection.InsertOneAsync(newAccount);

        public async Task Update(string id, User updatedAccount) =>
            await _userAccountsCollection.ReplaceOneAsync(x => x.Id == id, updatedAccount);

        public async Task Remove(string id) =>
            await _userAccountsCollection.DeleteOneAsync(x => x.Id == id);
    }
}
