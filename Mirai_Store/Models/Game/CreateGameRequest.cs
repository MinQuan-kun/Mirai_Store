namespace Mirai_Store.Models.Game
{
    public class CreateGameRequest
    {
        public string Title { get; set; } = null!;
        public string Description { get; set; } = string.Empty;
        public decimal Price { get; set; }
        public string CategoryId { get; set; } = null!;
        public string? ImageUrl { get; set; }
    }
}
