using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    public class OrderItem
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("order_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string OrderId { get; set; } = null!;

        [BsonElement("game_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string GameId { get; set; } = null!;

        [BsonElement("price")]
        public double Price { get; set; }
    }
}
