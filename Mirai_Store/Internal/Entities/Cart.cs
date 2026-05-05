using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    public class Cart
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("user_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string UserId { get; set; } = null!;

        [BsonElement("game_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string GameId { get; set; } = null!;

        [BsonElement("quantity")]
        public int Quantity { get; set; } = 1;

        [BsonElement("price_at_time")]
        public double PriceAtTime { get; set; }
    }
}
