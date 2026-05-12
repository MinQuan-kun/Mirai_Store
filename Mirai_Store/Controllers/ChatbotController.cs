using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;
using System.Text.Json;
using System.Text;

namespace Mirai_Store.Controllers
{
    [ApiController]
    [Route("api/chatbot")]
    public class ChatbotController : ControllerBase
    {
        private readonly IMongoCollection<Game> _gameCollection;
        private readonly IMongoCollection<Category> _categoryCollection;
        private readonly HttpClient _httpClient;

        public ChatbotController(MongoDbContext dbContext, IHttpClientFactory httpClientFactory)
        {
            _gameCollection = dbContext.Games;
            _categoryCollection = dbContext.Categories;
            _httpClient = httpClientFactory.CreateClient();
        }

        [HttpPost("chat")]
        public async Task<IActionResult> Chat([FromBody] ChatRequest request)
        {
            if (string.IsNullOrEmpty(request.Message)) return BadRequest();

            try
            {
                var apiKey = ChatboxConst.GEMINI_API_KEY;
                if (string.IsNullOrEmpty(apiKey)) return Ok(new { reply = "Hệ thống chatbot đang bảo trì (Thiếu API Key)." });

                var allCategories = await _categoryCollection.Find(_ => true).ToListAsync();
                var categoryMap = allCategories.ToDictionary(x => x.Id!, x => x.Name);
                
                // Tìm game liên quan đến tin nhắn
                var filter = Builders<Game>.Filter.And(
                    Builders<Game>.Filter.Eq(x => x.IsActive, true),
                    Builders<Game>.Filter.Regex(x => x.Name, new MongoDB.Bson.BsonRegularExpression(request.Message, "i"))
                );

                var games = await _gameCollection.Find(filter).Limit(5).ToListAsync();
                if (!games.Any())
                {
                    games = await _gameCollection.Find(x => x.IsActive).SortByDescending(x => x.Id).Limit(5).ToListAsync();
                }

                var gameDataText = new StringBuilder();
                gameDataText.AppendLine("THÔNG TIN CỬA HÀNG:");
                gameDataText.AppendLine("- Các thể loại hiện có: " + string.Join(", ", allCategories.Select(x => x.Name)));
                gameDataText.AppendLine("\nDANH SÁCH GAME:");

                foreach (var game in games)
                {
                    var catName = game.CategoryIds.Any() && categoryMap.ContainsKey(game.CategoryIds[0]) ? categoryMap[game.CategoryIds[0]] : "Chưa phân loại";
                    gameDataText.AppendLine($"- Tên: {game.Name} | Thể loại: {catName} | Giá: {game.Price:N0} VNĐ");
                }

                var systemInstruction = @"Bạn là Muki - Trợ lý ảo bán game.
                Nhiệm vụ: Trả lời ngắn gọn, vui vẻ, chốt đơn dựa trên DANH SÁCH GAME.
                Tuyệt đối không bịa ra game không có trong danh sách.
                Nếu khách hỏi về kỹ thuật/lỗi: Liên hệ Admin 1234567890.
                Mua game: Đăng nhập > Chọn > Thanh toán.";

                var finalPrompt = $"{systemInstruction}\n\n{gameDataText}\n\nKHÁCH HỎI: {request.Message}";

                var geminiRequest = new
                {
                    contents = new[]
                    {
                        new { parts = new[] { new { text = finalPrompt } } }
                    }
                };

                var jsonRequest = JsonSerializer.Serialize(geminiRequest);
                var content = new StringContent(jsonRequest, Encoding.UTF8, "application/json");

                var url = $"https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={apiKey}";
                var response = await _httpClient.PostAsync(url, content);

                if (response.IsSuccessStatusCode)
                {
                    var responseJson = await response.Content.ReadAsStringAsync();
                    using var doc = JsonDocument.Parse(responseJson);
                    var reply = doc.RootElement
                        .GetProperty("candidates")[0]
                        .GetProperty("content")
                        .GetProperty("parts")[0]
                        .GetProperty("text")
                        .GetString();

                    return Ok(new { reply = reply });
                }

                return Ok(new { reply = "Muki đang bận một chút, bạn thử lại sau nhé!" });
            }
            catch (Exception)
            {
                return Ok(new { reply = "Có lỗi xảy ra, admin đang kiểm tra!" });
            }
        }
    }
}
