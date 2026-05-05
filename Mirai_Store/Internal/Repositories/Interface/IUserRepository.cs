using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface IUserRepository
    {
        Task<List<User>> GetAll();
        Task<User> GetById(string id);
        Task<User> GetByUsername(string username);
        Task<User> GetByEmail(string email);
        Task Create(User newAccount);
        Task Update(string id, User updatedAccount);
        Task Remove(string id);
    }
}