using Mirai_Store.Internal.DataContext;
using MongoDB.Driver;
using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Repositories.Interface
{
    public interface ITransactionRepository
    {
        Task<List<Transaction>> GetAll();
        Task<Transaction> GetById(string id);
        Task<Transaction> GetBySlug(string slug);
        Task Create(Transaction transaction);
        Task Update(string id, Transaction transaction);
        Task Remove(string id);
    }
}
