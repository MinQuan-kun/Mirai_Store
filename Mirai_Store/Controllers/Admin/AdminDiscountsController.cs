using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Discount;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/discounts")]
    [Authorize]
    public class AdminDiscountsController : ControllerBase
    {
        private readonly IDiscountService _discountService;

        public AdminDiscountsController(IDiscountService discountService)
        {
            _discountService = discountService;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll() => Ok(await _discountService.GetAllDiscountsAsync());

        [HttpPost]
        public async Task<IActionResult> Create([FromBody] DiscountRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            return Ok(await _discountService.CreateDiscountAsync(request));
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> Update(string id, [FromBody] DiscountRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            return Ok(await _discountService.UpdateDiscountAsync(id, request));
        }

        [HttpDelete("{id}")]
        public async Task<IActionResult> Delete(string id) => Ok(await _discountService.DeleteDiscountAsync(id));
    }
}