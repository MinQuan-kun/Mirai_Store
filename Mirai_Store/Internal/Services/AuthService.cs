using Mirai_Store.Internal.Entities;
using BCrypt.Net;
using Mirai_Store.Internal.Helpers;
using Mirai_Store.Internal.Repositories.Interface;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Auth;

namespace Mirai_Store.Internal.Services
{
    public class AuthService : IAuthService
    {
        private readonly IUserRepository _userRepository;
        private readonly JwtHelper _jwtHelper;

        public AuthService(IUserRepository userRepository, JwtHelper jwtHelper)
        {
            _userRepository = userRepository;
            _jwtHelper = jwtHelper;
        }

        public async Task<AuthResponse> Register(RegisterRequest request)
        {
            
            var existingUser = await _userRepository.GetByUsername(request.Name);
            if (existingUser != null)
                return new AuthResponse { Success = false, Message = "Tên tài khoản đã tồn tại." };

            
            var existingEmail = await _userRepository.GetByEmail(request.Email);
            if (existingEmail != null)
                return new AuthResponse { Success = false, Message = "Email đã tồn tại." };

            
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

            await _userRepository.Create(newUser);

            return new AuthResponse
            {
                Success = true,
                Message = "Đăng ký thành công."
            };
        }

        public async Task<AuthResponse> Login(LoginRequest request)
        {
            var user = await _userRepository.GetByEmail(request.Email);
            
            if (user == null || !BCrypt.Net.BCrypt.EnhancedVerify(request.Password, user.Password, hashType: HashType.SHA384))
            {
                return new AuthResponse { Success = false, Message = "Tài khoản hoặc mật khẩu không đúng." };
            }

            if (user.Status != "active")
            {
                return new AuthResponse { Success = false, Message = "Tài khoản của bạn đã bị khóa." };
            }
            
            var token = _jwtHelper.GenerateJwtToken(user);

            
            user.UpdatedAt = DateTime.UtcNow;
            await _userRepository.Update(user.Id, user);

            return new AuthResponse
            {
                Success = true,
                Message = "Đăng nhập thành công.",
                Token = token,
                Name = user.Name,
                Email = user.Email
            };
        }
    }
}
