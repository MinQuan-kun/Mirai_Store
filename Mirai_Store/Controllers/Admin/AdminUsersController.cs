using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;
using BCrypt.Net;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/users")]
    public class AdminUsersController : ControllerBase
    {
        private readonly IMongoCollection<User> _userCollection;

        public AdminUsersController(MongoDbContext dbContext)
        {
            _userCollection = dbContext.User;
        }

        /// <summary>
        /// Lấy danh sách tất cả người dùng
        /// </summary>
        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var users = await _userCollection.Find(_ => true)
                .SortByDescending(x => x.CreatedAt)
                .ToListAsync();

            var result = users.Select(u => new
            {
                id = u.Id,
                name = u.Name,
                email = u.Email,
                role = u.Role,
                status = u.Status,
                avatar = u.Avatar,
                balance = u.Balance,
                createdAt = u.CreatedAt,
                updatedAt = u.UpdatedAt
            });

            return Ok(new { success = true, data = result });
        }

        /// <summary>
        /// Lấy thông tin chi tiết một người dùng
        /// </summary>
        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(string id)
        {
            var user = await _userCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (user == null)
                return NotFound(new { success = false, message = "Không tìm thấy người dùng." });

            return Ok(new
            {
                success = true,
                data = new
                {
                    id = user.Id,
                    name = user.Name,
                    email = user.Email,
                    role = user.Role,
                    status = user.Status,
                    avatar = user.Avatar,
                    balance = user.Balance,
                    createdAt = user.CreatedAt,
                    updatedAt = user.UpdatedAt
                }
            });
        }

        /// <summary>
        /// Toggle trạng thái active/banned của người dùng
        /// </summary>
        [HttpPatch("{id}/toggle-status")]
        public async Task<IActionResult> ToggleStatus(string id)
        {
            var user = await _userCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (user == null)
                return NotFound(new { success = false, message = "Không tìm thấy người dùng." });

            var newStatus = user.Status == "active" ? "banned" : "active";
            var update = Builders<User>.Update
                .Set(x => x.Status, newStatus)
                .Set(x => x.UpdatedAt, DateTime.UtcNow);

            await _userCollection.UpdateOneAsync(x => x.Id == id, update);

            return Ok(new { success = true, message = $"Tài khoản {user.Name} đã được {(newStatus == "active" ? "mở khóa" : "khóa")}." });
        }

        /// <summary>
        /// Cập nhật role cho người dùng
        /// </summary>
        [HttpPatch("{id}/role")]
        public async Task<IActionResult> UpdateRole(string id, [FromBody] UpdateRoleRequest request)
        {
            var user = await _userCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (user == null)
                return NotFound(new { success = false, message = "Không tìm thấy người dùng." });

            var update = Builders<User>.Update
                .Set(x => x.Role, request.Role)
                .Set(x => x.UpdatedAt, DateTime.UtcNow);

            await _userCollection.UpdateOneAsync(x => x.Id == id, update);

            return Ok(new { success = true, message = $"Đã cập nhật quyền của {user.Name} thành {request.Role}." });
        }

        /// <summary>
        /// Reset mật khẩu về mặc định (123456)
        /// </summary>
        [HttpPatch("{id}/reset-password")]
        public async Task<IActionResult> ResetPassword(string id)
        {
            var user = await _userCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (user == null)
                return NotFound(new { success = false, message = "Không tìm thấy người dùng." });

            var hashedPassword = BCrypt.Net.BCrypt.EnhancedHashPassword("123456", hashType: HashType.SHA384);

            var update = Builders<User>.Update
                .Set(x => x.Password, hashedPassword)
                .Set(x => x.UpdatedAt, DateTime.UtcNow);

            await _userCollection.UpdateOneAsync(x => x.Id == id, update);

            return Ok(new { success = true, message = $"Đã reset mật khẩu của {user.Name} về '123456'." });
        }

        /// <summary>
        /// Xóa người dùng
        /// </summary>
        [HttpDelete("{id}")]
        public async Task<IActionResult> Delete(string id)
        {
            var user = await _userCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (user == null)
                return NotFound(new { success = false, message = "Không tìm thấy người dùng." });

            await _userCollection.DeleteOneAsync(x => x.Id == id);

            return Ok(new { success = true, message = $"Đã xóa người dùng {user.Name}." });
        }
    }

    public class UpdateRoleRequest
    {
        public string Role { get; set; } = "user";
    }
}
