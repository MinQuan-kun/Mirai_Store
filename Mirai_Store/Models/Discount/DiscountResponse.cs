namespace Mirai_Store.Models.Discount
{
    public class DiscountResponse
    {
        public string Id { get; set; } = null!;
        public string Code { get; set; } = null!;
        public string Type { get; set; } = string.Empty;
        public double Value { get; set; }
        public DateTime ExpiresAt { get; set; }
    }
}