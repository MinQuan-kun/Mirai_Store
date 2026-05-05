namespace Mirai_Store.Models.User
{
    public class UserResponse
    {
        public string? Id { get; set; }
        public string Name { get; set; } = string.Empty;
        public string Email { get; set; } = string.Empty;
        public string Role { get; set; } = string.Empty;
        public string? AvatarUrl { get; set; }
        public string? Phone { get; set; }
        public DateTime CreatedAt { get; set; }
    }
}