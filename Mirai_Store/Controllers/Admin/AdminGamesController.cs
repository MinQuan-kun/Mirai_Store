using CloudinaryDotNet;
using CloudinaryDotNet.Actions;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using Mirai_Store.Models;
using MongoDB.Driver;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/games")]
    [Authorize]
    public class AdminGamesController : ControllerBase
    {
        private readonly IMongoCollection<Game> _gameCollection;

        public AdminGamesController(MongoDbContext dbContext)
        {
            _gameCollection = dbContext.Games;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll()
        {
            var games = await _gameCollection.Find(_ => true).ToListAsync();
            var responseData = games.Select(g => new GameResponse
            {
                Id = g.Id!,
                Title = g.Name,
                Description = g.Description ?? "",
                Price = (decimal)g.Price,
                ImageUrl = g.Image,
                CategoryName = "Game",
                IsActive = g.IsActive
            }).ToList();

            return Ok(new BaseResponse { Success = true, Data = responseData });
        }

        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(string id)
        {
            var game = await _gameCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (game == null)
                return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy game" });

            var response = new GameEditResponse
            {
                Id = game.Id!,
                Title = game.Name,
                Description = game.Description ?? string.Empty,
                Price = (decimal)game.Price,
                ImageUrl = game.Image,
                CategoryId = game.CategoryIds.FirstOrDefault(),
                IsActive = game.IsActive
            };

            return Ok(new BaseResponse { Success = true, Data = response });
        }

        [HttpPost]
        public async Task<IActionResult> CreateGame([FromForm] CreateGameRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });

            var slug = request.Title.ToLower().Replace(" ", "-");
            string imageUrl = request.ImageUrl ?? "";

            if (request.ImageFile != null && request.ImageFile.Length > 0)
            {
                imageUrl = await UploadImageToCloudinaryAsync(request.ImageFile, slug);
            }

            var newGame = new Game
            {
                Name = request.Title,
                Slug = slug,
                Description = request.Description,
                Price = (double)request.Price,
                Image = imageUrl,
                CategoryIds = new List<string> { request.CategoryId },
                IsActive = true
            };

            await _gameCollection.InsertOneAsync(newGame);
            return Ok(new BaseResponse { Success = true, Message = "Tạo game thành công!" });
        }

        [HttpPut("{id}")]
        public async Task<IActionResult> UpdateGame(string id, [FromForm] CreateGameRequest request)
        {
            if (!ModelState.IsValid) return BadRequest(new { Success = false, Message = "Dữ liệu không hợp lệ." });

            var game = await _gameCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (game == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy game" });

            string imageUrl = game.Image ?? "";

            if (request.ImageFile != null && request.ImageFile.Length > 0)
            {
                imageUrl = await UploadImageToCloudinaryAsync(request.ImageFile, game.Slug);
            }

            game.Name = request.Title;
            game.Description = request.Description;
            game.Price = (double)request.Price;
            game.Image = imageUrl;
            game.CategoryIds = new List<string> { request.CategoryId };

            await _gameCollection.ReplaceOneAsync(x => x.Id == id, game);
            return Ok(new BaseResponse { Success = true, Message = "Cập nhật game thành công!" });
        }

        [HttpDelete("{id}")]
        public async Task<IActionResult> DeleteGame(string id)
        {
            var game = await _gameCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (game == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy game" });

            await _gameCollection.DeleteOneAsync(x => x.Id == id);
            return Ok(new BaseResponse { Success = true, Message = "Xóa game thành công" });
        }

        [HttpPatch("{id}/toggle-status")]
        public async Task<IActionResult> ToggleGameStatus(string id)
        {
            var game = await _gameCollection.Find(x => x.Id == id).FirstOrDefaultAsync();
            if (game == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy game" });

            game.IsActive = !game.IsActive;
            await _gameCollection.ReplaceOneAsync(x => x.Id == id, game);

            return Ok(new BaseResponse { Success = true, Message = $"Đã {(game.IsActive ? "bật" : "tắt")} hiển thị game" });
        }

        private async Task<string> UploadImageToCloudinaryAsync(Microsoft.AspNetCore.Http.IFormFile file, string slug)
        {
            var account = new Account(
                CloudianryConst.CloudName,
                CloudianryConst.ApiKey,
                CloudianryConst.ApiSecret
            );
            var cloudinary = new Cloudinary(account);

            using var stream = file.OpenReadStream();
            var uploadParams = new ImageUploadParams()
            {
                File = new FileDescription(file.FileName, stream),
                Folder = "Shop_Game/games",
                PublicId = "game_" + slug + "_" + DateTimeOffset.UtcNow.ToUnixTimeSeconds(),
                Overwrite = true
            };

            var uploadResult = await cloudinary.UploadAsync(uploadParams);
            return uploadResult.SecureUrl.ToString();
        }
    }
}