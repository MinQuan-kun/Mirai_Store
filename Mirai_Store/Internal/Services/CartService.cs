using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Cart;

namespace Mirai_Store.Internal.Services
{
    public class CartService : ICartService
    {
        private readonly ICartRepository _cartRepository;
        private readonly IGameRepository _gameRepository;

        public CartService(ICartRepository cartRepository, IGameRepository gameRepository)
        {
            _cartRepository = cartRepository;
            _gameRepository = gameRepository;
        }

        public async Task<CartResponse> GetCart(string userId)
        {
            var cartItems = await _cartRepository.GetByUserId(userId);
            var result = new List<CartItemDto>();
            decimal total = 0;

            foreach (var item in cartItems)
            {
                var game = await _gameRepository.GetById(item.GameId);
                if (game != null)
                {
                    result.Add(new CartItemDto
                    {
                        Id = item.Id,
                        GameId = item.GameId,
                        GameName = game.Name,
                        GameImage = game.Image,
                        PriceAtTime = item.PriceAtTime,
                        Quantity = item.Quantity
                    });
                    total += item.PriceAtTime * item.Quantity;
                }
            }

            return new CartResponse
            {
                Items = result,
                Total = total
            };
        }

        public async Task<CartActionResponse> AddToCart(string userId, string gameId)
        {
            var game = await _gameRepository.GetById(gameId);
            if (game == null)
            {
                return new CartActionResponse { Success = false, Message = "Game không tồn tại", Status = "error" };
            }

            
            var existingItem = await _cartRepository.GetByUserAndGame(userId, gameId);
            if (existingItem != null)
            {
                return new CartActionResponse { Success = true, Message = "Game này đã có trong giỏ hàng", Status = "info" };
            }

            var cart = new Cart
            {
                UserId = userId,
                GameId = gameId,
                PriceAtTime = (decimal)game.Price,
                Quantity = 1
            };

            await _cartRepository.Create(cart);
            return new CartActionResponse { Success = true, Message = "Đã thêm vào giỏ hàng thành công!", Status = "success" };
        }

        public async Task<CartActionResponse> RemoveFromCart(string userId, string cartId)
        {
            await _cartRepository.Delete(cartId);
            return new CartActionResponse { Success = true, Message = "Đã xóa khỏi giỏ hàng!", Status = "success" };
        }

        public async Task<CartActionResponse> ClearCart(string userId)
        {
            await _cartRepository.Clear(userId);
            return new CartActionResponse { Success = true, Message = "Đã xóa tất cả sản phẩm khỏi giỏ hàng!", Status = "success" };
        }

        public async Task<long> GetCartCount(string userId)
        {
            return await _cartRepository.Count(userId);
        }
    }
}
