namespace Mirai_Store.Models
{
    public class DepositRequest
    {
        public double Amount { get; set; }
        public string PaymentMethod { get; set; } = "test_card";
    }

    public class PaymentCallbackRequest
    {
        public string ReferenceId { get; set; } = null!;
        public bool Success { get; set; }
        public double? Amount { get; set; }
        public string? Message { get; set; }
        public string? GatewayTransactionId { get; set; }
    }

    public class TransactionResponse
    {
        public string Id { get; set; } = null!;
        public string Type { get; set; } = null!;
        public double Amount { get; set; }
        public string Status { get; set; } = null!;
        public string? Description { get; set; }
        public string? OrderId { get; set; }
        public string PaymentMethod { get; set; } = null!;
        public DateTime CreatedAt { get; set; }
    }
}
