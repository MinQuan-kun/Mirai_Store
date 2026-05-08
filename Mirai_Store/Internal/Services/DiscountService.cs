using Mirai_Store.Internal.Entities;
using Mirai_Store.Internal.Repositories.Interface;
using Mirai_Store.Internal.Services.Interface;
using Mirai_Store.Models.Common;
using Mirai_Store.Models.Discount;

namespace Mirai_Store.Internal.Services
{
    public class DiscountService : IDiscountService
    {
        private readonly IDiscountRepository _repo;

        public DiscountService(IDiscountRepository repo)
        {
            _repo = repo;
        }

        public async Task<BaseResponse> GetAllDiscountsAsync()
        {
            var entities = await _repo.GetAll();

            
            var responseDtos = entities.Select(e => new DiscountResponse
            {
                Id = e.Id!,
                Code = e.Code,
                Type = e.Type,
                Value = e.Value,
                ExpiresAt = e.ExpiresAt
            }).ToList();

            return new BaseResponse { Success = true, Data = responseDtos };
        }

        public async Task<BaseResponse> CreateDiscountAsync(DiscountRequest request)
        {
            var code = request.Code.ToUpper();
            if (await _repo.GetByCode(code) != null)
                return new BaseResponse { Success = false, Message = "Mã giảm giá đã tồn tại." };

            var newEntity = new DiscountCode
            {
                Code = code,
                Type = request.Type,
                Value = request.Value,
                ExpiresAt = request.ExpiresAt
            };

            await _repo.Create(newEntity);
            return new BaseResponse { Success = true, Message = "Tạo mã giảm giá thành công!" };
        }

        public async Task<BaseResponse> UpdateDiscountAsync(string id, DiscountRequest request)
        {
            var entity = await _repo.GetById(id);
            if (entity == null) return new BaseResponse { Success = false, Message = "Không tìm thấy." };

            entity.Code = request.Code.ToUpper();
            entity.Type = request.Type;
            entity.Value = request.Value;
            entity.ExpiresAt = request.ExpiresAt;

            await _repo.Update(id, entity);
            return new BaseResponse { Success = true, Message = "Cập nhật thành công!" };
        }

        public async Task<BaseResponse> DeleteDiscountAsync(string id)
        {
            var entity = await _repo.GetById(id);
            if (entity == null) return new BaseResponse { Success = false, Message = "Không tìm thấy." };

            await _repo.Remove(id);
            return new BaseResponse { Success = true, Message = "Đã xóa mã giảm giá!" };
        }
    }
}