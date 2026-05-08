namespace Mirai_Store.Models
{
    public class AddToCartRequest
    {
        public string GameId { get; set; } = null!;
    }

    public class CartItemDto
    {
        public string? Id { get; set; }
        public string GameId { get; set; } = null!;
        public string GameName { get; set; } = null!;
        public string? GameImage { get; set; }
        public decimal PriceAtTime { get; set; }
        public int Quantity { get; set; }
    }

    public class CartResponse
    {
        public List<CartItemDto> Items { get; set; } = new();
        public decimal Total { get; set; }
    }

    public class CartActionResponse
    {
        public bool Success { get; set; }
        public string Message { get; set; } = null!;
        public string Status { get; set; } = null!; // success, error, info
    }
}
