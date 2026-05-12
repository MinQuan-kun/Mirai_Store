namespace Mirai_Store.Models
{
    public class BaseResponse
    {
        public bool Success { get; set; }
        public string? Message { get; set; }
        public object? Data { get; set; }
    }

    public class CategoryRequest
    {
        public string Name { get; set; } = null!;
        public string Description { get; set; } = "";
        public bool IsActive { get; set; } = true;
    }

    public class CategoryResponse
    {
        public string Id { get; set; } = null!;
        public string Name { get; set; } = null!;
        public string Slug { get; set; } = null!;
        public string Description { get; set; } = "";
        public bool IsActive { get; set; }
    }

    public class DiscountRequest
    {
        public string Code { get; set; } = null!;
        public string Type { get; set; } = "percent";
        public double Value { get; set; }
        public DateTime ExpiresAt { get; set; }
        public bool IsActive { get; set; } = true;
        public int? UsageLimit { get; set; }
    }

    public class DiscountResponse
    {
        public string Id { get; set; } = null!;
        public string Code { get; set; } = null!;
        public string Type { get; set; } = null!;
        public double Value { get; set; }
        public bool IsActive { get; set; }
        public DateTime ExpiresAt { get; set; }
        public int? UsageLimit { get; set; }
        public int? UsedCount { get; set; }
    }
}
