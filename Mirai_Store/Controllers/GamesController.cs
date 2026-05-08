using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Bson;
using MongoDB.Driver;
using System.Text.RegularExpressions;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/games")]
    public class GamesController : ControllerBase
    {
        private readonly IMongoCollection<Game> _gameCollection;
        private readonly IMongoCollection<Category> _categoryCollection;

        public GamesController(MongoDbContext dbContext)
        {
            _gameCollection = dbContext.Games;
            _categoryCollection = dbContext.Categories;
        }

        [HttpGet]
        public async Task<IActionResult> GetAll(
            [FromQuery] string? search,
            [FromQuery] string? category,
            [FromQuery] double? minPrice,
            [FromQuery] double? maxPrice,
            [FromQuery] string? publisher,
            [FromQuery] string? platform,
            [FromQuery] string? sort,
            [FromQuery] int page = 1,
            [FromQuery] int pageSize = 12)
        {
            var filterBuilder = Builders<Game>.Filter;
            var filter = filterBuilder.Eq(x => x.IsActive, true);

            if (!string.IsNullOrEmpty(search))
            {
                filter &= filterBuilder.Regex(x => x.Name, new BsonRegularExpression(search, "i"));
            }

            if (!string.IsNullOrEmpty(category))
            {
                filter &= filterBuilder.AnyEq(x => x.CategoryIds, category);
            }

            if (minPrice.HasValue)
            {
                filter &= filterBuilder.Gte(x => x.Price, minPrice.Value);
            }

            if (maxPrice.HasValue)
            {
                filter &= filterBuilder.Lte(x => x.Price, maxPrice.Value);
            }

            if (!string.IsNullOrEmpty(publisher))
            {
                filter &= filterBuilder.Eq(x => x.Publisher, publisher);
            }

            if (!string.IsNullOrEmpty(platform))
            {
                filter &= filterBuilder.AnyEq(x => x.Platforms, platform);
            }

            var query = _gameCollection.Find(filter);

            // Sắp xếp
            switch (sort)
            {
                case "price_asc":
                    query = query.SortBy(x => x.Price);
                    break;
                case "price_desc":
                    query = query.SortByDescending(x => x.Price);
                    break;
                case "newest":
                default:
                    query = query.SortByDescending(x => x.Id); // Giả định Id ObjectId có tính thứ tự thời gian
                    break;
            }

            var totalItems = await _gameCollection.CountDocumentsAsync(filter);
            var games = await query.Skip((page - 1) * pageSize).Limit(pageSize).ToListAsync();

            var responseData = games.Select(g => new GameResponse
            {
                Id = g.Id!,
                Title = g.Name,
                Description = g.Description ?? "",
                Price = (decimal)g.Price,
                ImageUrl = g.Image,
                CategoryName = "Game", // Có thể join thêm nếu cần
                IsActive = g.IsActive
            }).ToList();

            return Ok(new
            {
                Success = true,
                Data = responseData,
                Pagination = new
                {
                    TotalItems = totalItems,
                    CurrentPage = page,
                    PageSize = pageSize,
                    TotalPages = (int)Math.Ceiling((double)totalItems / pageSize)
                }
            });
        }

        [HttpGet("{id}")]
        public async Task<IActionResult> GetById(string id)
        {
            var game = await _gameCollection.Find(x => x.Id == id && x.IsActive == true).FirstOrDefaultAsync();
            if (game == null) return NotFound(new BaseResponse { Success = false, Message = "Không tìm thấy game" });

            return Ok(new { Success = true, Data = game });
        }

        [HttpGet("gacha")]
        public async Task<IActionResult> Gacha()
        {
            var activeGames = await _gameCollection.Find(x => x.IsActive == true).ToListAsync();
            if (!activeGames.Any()) return BadRequest(new { Success = false, Message = "Không có game nào để quay!" });

            var random = new Random();
            var randomGame = activeGames[random.Next(activeGames.Count)];

            return Ok(new { Success = true, Data = randomGame });
        }

        [HttpGet("search-suggestions")]
        public async Task<IActionResult> SearchSuggestions([FromQuery] string q)
        {
            if (string.IsNullOrEmpty(q) || q.Length < 2) return Ok(new List<object>());

            var filter = Builders<Game>.Filter.And(
                Builders<Game>.Filter.Eq(x => x.IsActive, true),
                Builders<Game>.Filter.Regex(x => x.Name, new BsonRegularExpression(q, "i"))
            );

            var games = await _gameCollection.Find(filter).Limit(8).ToListAsync();

            var suggestions = games.Select(g => new
            {
                id = g.Id,
                name = g.Name,
                image = g.Image,
                price = g.Price,
                price_formatted = g.Price == 0 ? "Miễn phí" : g.Price.ToString("N0") + " đ"
            });

            return Ok(suggestions);
        }
    }
}
