using System.ComponentModel.DataAnnotations;

namespace Mirai_Store.Models.Category
{
    public class CategoryRequest
    {
        [Required(ErrorMessage = "Tên danh mục là bắt buộc.")]
        public string Name { get; set; } = null!;
        public string? Description { get; set; }
        public bool IsActive { get; set; } = true;
    }
}