namespace Mirai_Store.Models.Order
{
    public class CheckoutRequest
    {
        public string? DiscountCode { get; set; }
    }

    public class OrderResponse
    {
        public string Id { get; set; } = null!;
        public string OrderNumber { get; set; } = null!;
        public decimal TotalAmount { get; set; }
        public string Status { get; set; } = null!;
        public string PaymentMethod { get; set; } = null!;
        public DateTime CreatedAt { get; set; }
    }
}
