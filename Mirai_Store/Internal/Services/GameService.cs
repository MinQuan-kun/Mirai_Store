using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using Mirai_Store.Internal.Services.Interface;

namespace Mirai_Store.Internal.Services
{
    public class GameService : IGameService
    {
        private readonly IGameRepository _gameRepository;

        public GameService(IGameRepository gameRepository)
        {
            _gameRepository = gameRepository;
        }


    }
}
