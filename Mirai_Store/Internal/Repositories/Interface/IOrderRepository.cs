using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface IOrderRepository
    {
        Task<List<Order>> GetAll();
        Task Create(Order order);
        Task Update(string id, Order order);
        Task Remove(string id);
        Task CreateOrder(Order order);
        Task CreateOrderItems(List<OrderItem> items);
        Task<List<Order>> GetByUserId(string userId);
        Task<Order> GetById(string id);

    }
}