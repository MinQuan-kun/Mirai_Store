using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    [BsonIgnoreExtraElements]
    public class DiscountCode
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("code")]
        public string Code { get; set; } = null!;

        [BsonElement("type")]
        public string Type { get; set; } = "percent";

        [BsonElement("value")]
        public double Value { get; set; }

        [BsonElement("expires_at")]
        public DateTime ExpiresAt { get; set; }

        [BsonElement("is_active")]
        [BsonIgnoreIfNull]
        public bool? IsActive { get; set; }

        [BsonElement("usage_limit")]
        [BsonIgnoreIfNull]
        public int? UsageLimit { get; set; }

        [BsonElement("used_count")]
        [BsonIgnoreIfNull]
        public int? UsedCount { get; set; }
    }
}
