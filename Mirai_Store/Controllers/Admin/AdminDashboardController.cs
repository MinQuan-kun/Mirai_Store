using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Mirai_Store.Internal.DataContext;
using Mirai_Store.Internal.Entities;
using Mirai_Store.Models;
using MongoDB.Driver;

namespace Mirai_Store.Controllers.Admin
{
    [ApiController]
    [Route("api/admin/stats")]
    [Authorize]
    public class AdminDashboardController : ControllerBase
    {
        private readonly IMongoCollection<Game> _gameCollection;
        private readonly IMongoCollection<Category> _categoryCollection;
        private readonly IMongoCollection<DiscountCode> _discountCollection;
        private readonly IMongoCollection<User> _userCollection;
        private readonly IMongoCollection<Order> _orderCollection;

        public AdminDashboardController(MongoDbContext dbContext)
        {
            _gameCollection = dbContext.Games;
            _categoryCollection = dbContext.Categories;
            _discountCollection = dbContext.DiscountCodes;
            _userCollection = dbContext.User;
            _orderCollection = dbContext.Orders;
        }

        [HttpGet]
        public async Task<IActionResult> GetStats()
        {
            var completedOrders = await _orderCollection
                .Find(x => x.Status == "completed")
                .ToListAsync();

            var recentOrders = await _orderCollection
                .Find(_ => true)
                .SortByDescending(x => x.CreatedAt)
                .Limit(5)
                .ToListAsync();

            var recentUserIds = recentOrders
                .Select(x => x.UserId)
                .Where(x => !string.IsNullOrWhiteSpace(x))
                .Distinct()
                .ToList();

            var recentUsers = recentUserIds.Count == 0
                ? new List<User>()
                : await _userCollection.Find(x => recentUserIds.Contains(x.Id!)).ToListAsync();

            var userNames = recentUsers
                .Where(x => !string.IsNullOrWhiteSpace(x.Id))
                .ToDictionary(x => x.Id!, x => x.Name ?? x.Email);

            var now = DateTime.UtcNow;
            var startOfWeek = now.Date.AddDays(-(int)now.DayOfWeek + 1);
            if (now.DayOfWeek == DayOfWeek.Sunday)
            {
                startOfWeek = now.Date.AddDays(-6);
            }

            var weekLabels = new List<string>();
            var weekData = new List<double>();
            for (var i = 0; i < 7; i++)
            {
                var date = startOfWeek.AddDays(i);
                weekLabels.Add(date.ToString("dd/MM"));
                weekData.Add(SumOrdersForDay(completedOrders, date));
            }

            var daysInMonth = DateTime.DaysInMonth(now.Year, now.Month);
            var monthLabels = new List<string>();
            var monthData = new List<double>();
            for (var day = 1; day <= daysInMonth; day++)
            {
                var date = new DateTime(now.Year, now.Month, day);
                monthLabels.Add($"{day}/{now:MM}");
                monthData.Add(SumOrdersForDay(completedOrders, date));
            }

            var startMonthOfQuarter = ((now.Month - 1) / 3) * 3 + 1;
            var quarterLabels = new List<string>();
            var quarterData = new List<double>();
            for (var i = 0; i < 3; i++)
            {
                var month = startMonthOfQuarter + i;
                quarterLabels.Add($"Tháng {month:00}");
                quarterData.Add(completedOrders
                    .Where(x => x.CreatedAt.HasValue && x.CreatedAt.Value.Year == now.Year && x.CreatedAt.Value.Month == month)
                    .Sum(x => x.TotalAmount));
            }

            var data = new
            {
                stats = new
                {
                    games = await _gameCollection.CountDocumentsAsync(_ => true),
                    categories = await _categoryCollection.CountDocumentsAsync(_ => true),
                    discounts = await _discountCollection.CountDocumentsAsync(_ => true),
                    users = await _userCollection.CountDocumentsAsync(_ => true),
                    orders = await _orderCollection.CountDocumentsAsync(_ => true),
                    revenue = completedOrders.Sum(x => x.TotalAmount)
                },
                charts = new
                {
                    week = new { labels = weekLabels, data = weekData },
                    month = new { labels = monthLabels, data = monthData },
                    quarter = new { labels = quarterLabels, data = quarterData }
                },
                recentOrders = recentOrders.Select(x => new
                {
                    id = x.Id,
                    orderNumber = x.OrderNumber,
                    customerName = userNames.GetValueOrDefault(x.UserId, "N/A"),
                    totalAmount = x.TotalAmount,
                    status = x.Status,
                    paymentMethod = x.PaymentMethod,
                    createdAt = x.CreatedAt
                })
            };

            return Ok(new BaseResponse { Success = true, Data = data });
        }

        private static double SumOrdersForDay(IEnumerable<Order> orders, DateTime date)
        {
            return orders
                .Where(x => x.CreatedAt.HasValue && x.CreatedAt.Value.Date == date.Date)
                .Sum(x => x.TotalAmount);
        }
    }
}
