using MongoDB.Driver.Core.Configuration;

namespace Mirai_Store.Internal.Contants
{

    public static class DatabaseConst
    {
        public static string ConnectionString = Environment.GetEnvironmentVariable("DB_DSN") ?? Environment.GetEnvironmentVariable("DB_URI") ?? "mongodb+srv://Ikkun:FhyWO9cGs22RfA9R@cluster0.zvdubot.mongodb.net/Game?appName=Cluster0";
        public static string DatabaseName = Environment.GetEnvironmentVariable("DB_DATABASE") ?? "store_game";
    }

    public static class CloudianryConst
    {
        public static string CloudName = Environment.GetEnvironmentVariable("CLOUDINARY_CLOUD_NAME") ?? "davfujasj";
        public static string ApiKey = Environment.GetEnvironmentVariable("CLOUDINARY_API_KEY") ?? "155879274742561";
        public static string ApiSecret = Environment.GetEnvironmentVariable("CLOUDINARY_API_SECRET") ?? "j2dKa - onHxbm5jKbDJjz9SUaIrw";
        public static string FolderName = Environment.GetEnvironmentVariable("CLOUDINARY_FOLDER") ?? "Game";
    }

    public static class SecurityConst
    {
        public static string key = Environment.GetEnvironmentVariable("JWT_SECRET") ?? "ToiNgheoVaBanCungThe_TatCaChungTaDeuLaNoLeChoTuBan_DaylaKeyyyy";
        public static string Issuer = Environment.GetEnvironmentVariable("JWT_ISSUER") ?? "game";
        public static string Audience = Environment.GetEnvironmentVariable("JWT_AUDIENCE") ?? "game";
        public static int ExpireDays = int.TryParse(Environment.GetEnvironmentVariable("JWT_EXPIRE_DAYS"), out var days) ? days : 7;
    }

    public static class ChatboxConst
    {
        public static string GEMINI_API_KEY = Environment.GetEnvironmentVariable("GEMINI_API_KEY") ?? "AIzaSyDMZLDAOKwEaMVVvG0yxCCFLyZYmDr4Fj8";
    }

    public static class PaymentConst
    {
        public static string MOMO_ACCESS_KEY = Environment.GetEnvironmentVariable("MOMO_ACCESS_KEY") ?? "klm05TvNBzhg7h7j";
        public static string MOMO_ENDPOINT = Environment.GetEnvironmentVariable("MOMO_ENDPOINT") ?? "https://test-payment.momo.vn";
        public static string MOMO_PARTNER_CODE = Environment.GetEnvironmentVariable("MOMO_PARTNER_CODE") ?? "MOMOBKUN20180529";
        public static string MOMO_SECRET_KEY = Environment.GetEnvironmentVariable("MOMO_SECRET_KEY") ?? "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        public static string PAYPAL_MODE = Environment.GetEnvironmentVariable("PAYPAL_MODE") ?? "sandbox";
        public static string PAYPAL_CLIENT_ID = Environment.GetEnvironmentVariable("PAYPAL_CLIENT_ID") ?? "AVSq_9QjMRY47FYCHNUnO1biM_L9I84VMfj4wCsQ_qqSjb6AXFd-Zmy1ZxQny6J1EJ1yIz7K95i9pLc2";
        public static string PAYPAL_CLIENT_SECRET = Environment.GetEnvironmentVariable("PAYPAL_CLIENT_SECRET") ?? "ENybO6lo8IgjI8p2vpn9mnPLjF-SDPV94xNmWLF1nDm9mVcKMcZR_DR05NtFsMtid2RMZ1O9Mg6fnVck";
    }
}