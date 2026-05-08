using Microsoft.AspNetCore.Http;
using System.ComponentModel.DataAnnotations;

namespace Mirai_Store.Models.Game
{
    public class CreateGameRequest
    {
        [Required(ErrorMessage = "Tên game là bắt buộc.")]
        public string Title { get; set; } = null!;
        public string Description { get; set; } = string.Empty;

        [Required]
        public decimal Price { get; set; }

        [Required]
        public string CategoryId { get; set; } = null!;

        
        public IFormFile? ImageFile { get; set; }

        
        public string? ImageUrl { get; set; }
    }
}