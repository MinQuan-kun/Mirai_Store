using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Auth;
namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    public class AuthController : ControllerBase
    {
        private readonly IAuthService _authService;

        public AuthController(IAuthService authService)
        {
            _authService = authService;
        }

        
        
        
        [HttpPost("register")]
        public async Task<IActionResult> Register([FromBody] RegisterRequest request)
        {
            
            if (!ModelState.IsValid)
            {
                return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            }

            
            var result = await _authService.Register(request);

            if (!result.Success)
            {
                return BadRequest(result); 
            }

            return Ok(result); 
        }

        
        
        
        [HttpPost("login")]
        public async Task<IActionResult> Login([FromBody] LoginRequest request)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            }

            
            var result = await _authService.Login(request);

            if (!result.Success)
            {
                return Unauthorized(result); 
            }

            
            return Ok(result);
        }
    }
}
