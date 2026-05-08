namespace Mirai_Store.Models.Category
{
    public class CategoryResponse
    {
        public string Id { get; set; } = null!;
        public string Name { get; set; } = null!;
        public string Slug { get; set; } = null!;
        public string? Description { get; set; }
        public bool IsActive { get; set; }
    }
}