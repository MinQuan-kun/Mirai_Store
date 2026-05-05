using Microsoft.IdentityModel.Tokens;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using Mirai_Store.Internal.Contants;
using Mirai_Store.Internal.Entities;

namespace Mirai_Store.Internal.Helpers
{
    public class JwtHelper
    {
        public JwtHelper()
        {
        }

        public string GenerateJwtToken(User user)
        {
            var key = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(SecurityConst.key));
            var creds = new SigningCredentials(key, SecurityAlgorithms.HmacSha256);

            var claims = new[]
            {
                new Claim(JwtRegisteredClaimNames.Sub, user.Id ?? ""),
                new Claim(JwtRegisteredClaimNames.UniqueName, user.Name ?? ""),
                new Claim(JwtRegisteredClaimNames.Email, user.Email),
            };

            var token = new JwtSecurityToken(
                issuer: SecurityConst.Issuer,
                audience: SecurityConst.Audience,
                claims: claims,
                expires: DateTime.UtcNow.AddDays(SecurityConst.ExpireDays),
                signingCredentials: creds
            );

            return new JwtSecurityTokenHandler().WriteToken(token);
        }
    }
}
