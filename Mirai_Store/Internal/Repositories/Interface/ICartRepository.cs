using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface ICartRepository
    {
        Task<IEnumerable<Cart>> GetByUserId(string userId);
        Task<Cart?> GetByUserAndGame(string userId, string gameId);
        Task Create(Cart cart);
        Task Update(string id, Cart cart);
        Task Delete(string id);
        Task Clear(string userId);
        Task<long> Count(string userId);
    }
}
