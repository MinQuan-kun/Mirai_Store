using CloudinaryDotNet;
using CloudinaryDotNet.Actions;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Common;
using Mirai_Store.Models.Game;

namespace Mirai_Store.Internal.Services
{
    public class GameService : IGameService
    {
        private readonly IGameRepository _gameRepository;

        public GameService(IGameRepository gameRepository)
        {
            _gameRepository = gameRepository;
        }

        public async Task<BaseResponse> GetAllGamesAsync()
        {
            var games = await _gameRepository.GetAll();
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

            return new BaseResponse { Success = true, Data = responseData };
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

        public async Task<BaseResponse> CreateGameAsync(CreateGameRequest request)
        {
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

            await _gameRepository.Create(newGame);
            return new BaseResponse { Success = true, Message = "Tạo game thành công!" };
        }

        public async Task<BaseResponse> UpdateGameAsync(string id, CreateGameRequest request)
        {
            var game = await _gameRepository.GetById(id);
            if (game == null) return new BaseResponse { Success = false, Message = "Không tìm thấy game" };

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

            await _gameRepository.Update(id, game);
            return new BaseResponse { Success = true, Message = "Cập nhật game thành công!" };
        }

        public async Task<BaseResponse> DeleteGameAsync(string id)
        {
            var game = await _gameRepository.GetById(id);
            if (game == null) return new BaseResponse { Success = false, Message = "Không tìm thấy game" };

            await _gameRepository.Remove(id);
            return new BaseResponse { Success = true, Message = "Xóa game thành công" };
        }

        public async Task<BaseResponse> ToggleGameStatusAsync(string id)
        {
            var game = await _gameRepository.GetById(id);
            if (game == null) return new BaseResponse { Success = false, Message = "Không tìm thấy game" };

            game.IsActive = !game.IsActive;
            await _gameRepository.Update(id, game);

            return new BaseResponse { Success = true, Message = $"Đã {(game.IsActive ? "bật" : "tắt")} hiển thị game" };
        }
    }
}