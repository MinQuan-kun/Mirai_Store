using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Category;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/categories")]
    [Authorize]
    public class AdminCategoriesController : ControllerBase
    {
        private readonly ICategoryService _categoryService;

        public AdminCategoriesController(ICategoryService categoryService)
        {
            _categoryService = categoryService;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll() => Ok(await _categoryService.GetAllCategoriesAsync());

        [HttpPost]
        public async Task<IActionResult> Create([FromBody] CategoryRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            return Ok(await _categoryService.CreateCategoryAsync(request));
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> Update(string id, [FromBody] CategoryRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            return Ok(await _categoryService.UpdateCategoryAsync(id, request));
        }

        [HttpDelete("{id}")]
        public async Task<IActionResult> Delete(string id) => Ok(await _categoryService.DeleteCategoryAsync(id));
    }
}