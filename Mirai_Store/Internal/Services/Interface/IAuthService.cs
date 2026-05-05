using Mirai_Store.Models.Auth;

namespace Mirai_Store.Internal.Services.Interface
{
    public interface IAuthService
    {
        Task<AuthResponse> Register(RegisterRequest request);
        Task<AuthResponse> Login(LoginRequest request);
    }
}
