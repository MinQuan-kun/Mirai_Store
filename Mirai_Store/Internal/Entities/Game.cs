using MongoDB.Bson;
using MongoDB.Bson.Serialization.Attributes;


namespace Mirai_Store.Internal.Entities
{
    [BsonIgnoreExtraElements]
    public class Game
    {
        [BsonId]
        [BsonRepresentation(BsonType.ObjectId)]
        public string? Id { get; set; }

        [BsonElement("name")]
        public string Name { get; set; } = null!;

        [BsonElement("slug")]
        public string Slug { get; set; } = null!;

        [BsonElement("category_ids")]
        [BsonRepresentation(BsonType.ObjectId)]
        public List<string> CategoryIds { get; set; } = new();

        [BsonElement("description")]
        public string? Description { get; set; }

        [BsonElement("price")]
        public double Price { get; set; }

        [BsonElement("image")]
        public string? Image { get; set; }

        [BsonElement("download_link")]
        public string? DownloadLink { get; set; }

        [BsonElement("publisher")]
        public string Publisher { get; set; } = null!;

        [BsonElement("platforms")]
        public List<string> Platforms { get; set; } = new();

        [BsonElement("languages")]
        public List<string> Languages { get; set; } = new();

        [BsonElement("sold_count")]
        public int SoldCount { get; set; } = 0;

        [BsonElement("is_active")]
        public bool IsActive { get; set; } = true;
    }
}