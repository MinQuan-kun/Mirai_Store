using Mirai_Store.Models.Common;
using Mirai_Store.Models.Game;

namespace Mirai_Store.Internal.Services.Interface
{
    public interface IGameService
    {
        Task<BaseResponse> GetAllGamesAsync();
        Task<BaseResponse> CreateGameAsync(CreateGameRequest request);
        Task<BaseResponse> UpdateGameAsync(string id, CreateGameRequest request);
        Task<BaseResponse> DeleteGameAsync(string id);
        Task<BaseResponse> ToggleGameStatusAsync(string id);
    }
}