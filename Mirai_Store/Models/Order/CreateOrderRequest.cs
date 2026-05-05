namespace Mirai_Store.Models.Order
{
	public class CreateOrderRequest
	{
		public string UserId { get; set; } = null!;
		public List<OrderItem> Items { get; set; } = new();
		public string PaymentMethod { get; set; } = "wallet";
		public string? DiscountCode { get; set; }
	}
	public class OrderItem
	{
		public string GameId { get; set; } = null!;
		public int Quantity { get; set; } = 1;
	}
}

