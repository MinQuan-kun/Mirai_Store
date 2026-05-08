using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface IDiscountRepository
    {
        Task<List<DiscountCode>> GetAll();
        Task<DiscountCode> GetById(string id);
        Task<DiscountCode> GetByCode(string code);
        Task Create(DiscountCode discount);
        Task Update(string id, DiscountCode discount);
        Task Remove(string id);
    }
}