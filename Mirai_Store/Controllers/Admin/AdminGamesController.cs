using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Game;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/games")]
    [Authorize]
    public class AdminGamesController : ControllerBase
    {
        private readonly IGameService _gameService;

        public AdminGamesController(IGameService gameService)
        {
            _gameService = gameService;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll() => Ok(await _gameService.GetAllGamesAsync());

        
        [HttpPost]
        public async Task<IActionResult> CreateGame([FromForm] CreateGameRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            return Ok(await _gameService.CreateGameAsync(request));
        }

        
        [HttpPut("{id}")]
        public async Task<IActionResult> UpdateGame(string id, [FromForm] CreateGameRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });
            return Ok(await _gameService.UpdateGameAsync(id, request));
        }

        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteGame(string id) => Ok(await _gameService.DeleteGameAsync(id));

        [HttpPatch("{id}/toggle-status")]
        public async Task<IActionResult> ToggleGameStatus(string id)
        {
            return Ok(await _gameService.ToggleGameStatusAsync(id));
        }
    }
}