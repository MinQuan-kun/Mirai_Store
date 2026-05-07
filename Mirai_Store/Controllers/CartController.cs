using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Cart;
using System.Security.Claims;
using System.IdentityModel.Tokens.Jwt;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/[controller]")]
    [Authorize]
    public class CartController : ControllerBase
    {
        private readonly ICartService _cartService;

        public CartController(ICartService cartService)
        {
            _cartService = cartService;
        }

        private string? GetUserId()
        {
            return User.FindFirstValue(ClaimTypes.NameIdentifier) ?? User.FindFirstValue(JwtRegisteredClaimNames.Sub);
        }

        
        
        
        [HttpGet]
        public async Task<IActionResult> GetCart()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var cart = await _cartService.GetCart(userId);
            return Ok(cart);
        }

        
        
        
        [HttpPost("add")]
        public async Task<IActionResult> AddToCart([FromBody] AddToCartRequest request)
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var result = await _cartService.AddToCart(userId, request.GameId);
            return result.Success ? Ok(result) : BadRequest(result);
        }

        
        
        
        [HttpDelete("remove/{cartId}")]
        public async Task<IActionResult> RemoveFromCart(string cartId)
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var result = await _cartService.RemoveFromCart(userId, cartId);
            return Ok(result);
        }

        
        
        
        [HttpDelete("clear")]
        public async Task<IActionResult> ClearCart()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Unauthorized();

            var result = await _cartService.ClearCart(userId);
            return Ok(result);
        }

        
        
        
        [HttpGet("count")]
        public async Task<IActionResult> GetCount()
        {
            var userId = GetUserId();
            if (string.IsNullOrEmpty(userId)) return Ok(new { Count = 0 });

            var count = await _cartService.GetCartCount(userId);
            return Ok(new { Count = count });
        }
    }

    public class AddToCartRequest
    {
        public string GameId { get; set; } = null!;
    }
}
