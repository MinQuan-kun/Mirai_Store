using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Models.Game
{
    public class GameResponse
    {
        public string Id { get; set; } = null!;
        public string Title { get; set; } = null!;
        public string Description { get; set; } = null!;
        public decimal Price { get; set; }
        public string? ImageUrl { get; set; }
        public string CategoryName { get; set; } = string.Empty;
        public bool IsActive { get; set; }
    }
}