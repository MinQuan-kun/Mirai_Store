using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    public class Cart
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonRepresentation(BsonType.ObjectId)]
        public string UserId { get; set; } = null!;

        [BsonRepresentation(BsonType.ObjectId)]
        public string GameId { get; set; } = null!;

        public decimal PriceAtTime { get; set; }

        public int Quantity { get; set; } = 1;

        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;

        
        [BsonIgnore]
        public Game? Game { get; set; }
    }
}
