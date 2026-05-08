namespace Mirai_Store.Models
{
    public class UserDto
    {
        public string Id { get; set; } = null!;
        public string Name { get; set; } = null!;
        public string Email { get; set; } = null!;
        public string? Avatar { get; set; }
    }

    public class TransactionDto
    {
        public string Id { get; set; } = null!;
        public string Type { get; set; } = null!;
        public double Amount { get; set; }
        public string Status { get; set; } = null!;
        public string PaymentMethod { get; set; } = null!;
        public string? OrderId { get; set; }
        public DateTime CreatedAt { get; set; }
    }

    public class PurchasedGameDto
    {
        public string Id { get; set; } = null!;
        public string Name { get; set; } = null!;
        public string? Image { get; set; }
        public string? Publisher { get; set; }
        public string? DownloadLink { get; set; }
    }

    public class ProfileResponse
    {
        public bool Success { get; set; }
        public string Message { get; set; } = null!;
        public UserDto? User { get; set; }
        public List<PurchasedGameDto> PurchasedGames { get; set; } = new();
        public List<TransactionDto> Transactions { get; set; } = new();
    }
}
