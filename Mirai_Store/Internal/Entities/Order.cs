using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    [BsonIgnoreExtraElements]
    public class Order
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("user_id")]
        [BsonRepresentation(BsonType.ObjectId)]
        public string UserId { get; set; } = null!;

        [BsonElement("order_number")]
        public string OrderNumber { get; set; } = null!;

        [BsonElement("total_amount")]
        public double TotalAmount { get; set; }

        [BsonElement("status")]
        public string Status { get; set; } = "pending"; 

        [BsonElement("payment_method")]
        public string PaymentMethod { get; set; } = "wallet";

        [BsonElement("created_at")]
        public DateTime? CreatedAt { get; set; }
    }
}
