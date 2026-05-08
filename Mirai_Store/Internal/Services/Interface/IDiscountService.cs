using Mirai_Store.Models.Common;
using Mirai_Store.Models.Discount;

namespace Mirai_Store.Internal.Services.Interface
{
    public interface IDiscountService
    {
        Task<BaseResponse> GetAllDiscountsAsync();
        Task<BaseResponse> CreateDiscountAsync(DiscountRequest request);
        Task<BaseResponse> UpdateDiscountAsync(string id, DiscountRequest request);
        Task<BaseResponse> DeleteDiscountAsync(string id);
    }
}