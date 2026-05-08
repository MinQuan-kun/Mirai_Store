using System.ComponentModel.DataAnnotations;

namespace Mirai_Store.Models.Discount
{
    public class DiscountRequest
    {
        [Required]
        public string Code { get; set; } = null!;
        [Required]
        public string Type { get; set; } = "percent";
        [Required]
        public double Value { get; set; }
        [Required]
        public DateTime ExpiresAt { get; set; }
    }
}