namespace Mirai_Store.Models.Cart
{
    public class CartActionResponse
    {
        public bool Success { get; set; }
        public string Message { get; set; } = string.Empty;
        public string? Status { get; set; } 
    }
}
