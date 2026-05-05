using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;

namespace Mirai_Store.Internal.Entities
{
    public class User
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("name")]
        public string Name { get; set; } = null!;

        [BsonElement("email")]
        public string Email { get; set; } = null!;

        [BsonElement("password")]
        public string Password { get; set; } = null!;

        [BsonElement("role")]
        public string Role { get; set; } = "user";

        [BsonElement("status")]
        public string Status { get; set; } = "active";

        [BsonElement("avatar")]
        public string? Avatar { get; set; }

        [BsonElement("balance")]
        public double Balance { get; set; } = 0;

        [BsonElement("created_at")]
        public DateTime CreatedAt { get; set; } = DateTime.UtcNow;
        [BsonElement("updated_at")]
        public DateTime UpdatedAt { get; set; } = DateTime.UtcNow;

    }
}
