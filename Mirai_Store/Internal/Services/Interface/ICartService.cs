using Mirai_Store.Internal.Entities;
using Mirai_Store.Models.Cart;

namespace Mirai_Store.Internal.Services.Interface
{
    public interface ICartService
    {
        Task<CartResponse> GetCart(string userId);
        Task<CartActionResponse> AddToCart(string userId, string gameId);
        Task<CartActionResponse> RemoveFromCart(string userId, string cartId);
        Task<CartActionResponse> ClearCart(string userId);
        Task<long> GetCartCount(string userId);
    }
}
