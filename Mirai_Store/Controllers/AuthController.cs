using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Models;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Helpers;
using Mirai_Store.Internal.Entities;
using MongoDB.Driver;
using BCrypt.Net;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly IMongoCollection<User> _userCollection;
        private readonly JwtHelper _jwtHelper;

        public AuthController(MongoDbContext dbContext, JwtHelper jwtHelper)
        {
            _userCollection = dbContext.User;
            _jwtHelper = jwtHelper;
        }

        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] RegisterRequest request)
        {
            if (!ModelState.IsValid)
                return BadRequest(new AuthResponse { Success = false, Message = "Dữ liệu không hợp lệ." });

            var existingUser = await _userCollection.Find(x => x.Name == request.Name).FirstOrDefaultAsync();
            if (existingUser != null)
                return BadRequest(new AuthResponse { Success = false, Message = "Tên tài khoản đã tồn tại." });

            var existingEmail = await _userCollection.Find(x => x.Email == request.Email).FirstOrDefaultAsync();
            if (existingEmail != null)
                return BadRequest(new AuthResponse { Success = false, Message = "Email đã tồn tại." });

            string passwordHash = BCrypt.Net.BCrypt.EnhancedHashPassword(request.Password, hashType: HashType.SHA384);

            var newUser = new User
            {
                Name = request.Name,
                Password = passwordHash,
                Email = request.Email,
                Status = "active",
                CreatedAt = DateTime.UtcNow,
                UpdatedAt = DateTime.UtcNow,
            };

            await _userCollection.InsertOneAsync(newUser);

            return Ok(new AuthResponse { Success = true, Message = "Đăng ký thành công." });
        }

        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {
            if (!ModelState.IsValid)
                return BadRequest(new AuthResponse { Success = false, Message = "Dữ liệu không hợp lệ." });

            var user = await _userCollection.Find(x => x.Email == request.Email).FirstOrDefaultAsync();
            
            if (user == null || !BCrypt.Net.BCrypt.EnhancedVerify(request.Password, user.Password, hashType: HashType.SHA384))
                return Unauthorized(new AuthResponse { Success = false, Message = "Tài khoản hoặc mật khẩu không đúng." });

            if (user.Status != "active")
                return Unauthorized(new AuthResponse { Success = false, Message = "Tài khoản của bạn đã bị khóa." });

            var token = _jwtHelper.GenerateJwtToken(user);

            user.UpdatedAt = DateTime.UtcNow;
            await _userCollection.ReplaceOneAsync(x => x.Id == user.Id, user);

            return Ok(new AuthResponse
            {
                Success = true,
                Message = "Đăng nhập thành công.",
                Token = token,
                Name = user.Name,
                Email = user.Email,
                User = user
            });
        }
    }
}
