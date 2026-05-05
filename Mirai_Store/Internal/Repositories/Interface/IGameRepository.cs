using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface IGameRepository
    {
        Task<List<Game>> GetAll();
        Task<Game> GetById(string id);
        Task<Game> GetBySlug(string slug);
        Task Create(Game game);
        Task Update(string id, Game game);
        Task Remove(string id);
    }
}