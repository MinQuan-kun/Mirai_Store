using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Models.Cart
{
    public class CartResponse
    {
        public IEnumerable<CartItemDto> Items { get; set; } = new List<CartItemDto>();
        public decimal Total { get; set; }
    }

    public class CartItemDto
    {
        public string? Id { get; set; }
        public string GameId { get; set; } = null!;
        public string GameName { get; set; } = string.Empty;
        public string GameImage { get; set; } = string.Empty;
        public decimal PriceAtTime { get; set; }
        public int Quantity { get; set; }
    }
}
