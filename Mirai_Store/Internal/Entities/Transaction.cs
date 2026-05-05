using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    public class Transaction
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("user_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string UserId { get; set; } = null!;

        [BsonElement("type")]
        public string Type { get; set; } = null!; 

        [BsonElement("amount")]
        public double Amount { get; set; }

        [BsonElement("status")]
        public string Status { get; set; } = "pending"; 

        [BsonElement("description")]
        public string? Description { get; set; }

        [BsonElement("order_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? OrderId { get; set; }

        [BsonElement("reference_id")]
        public string? ReferenceId { get; set; }

        [BsonElement("payment_method")]
        public string PaymentMethod { get; set; } = null!; 

        [BsonElement("metadata")]
        public object? Metadata { get; set; }
    }
}
