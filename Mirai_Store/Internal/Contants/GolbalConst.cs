using MongoDB.Driver.Core.Configuration;

namespace Mirai_Store.Internal.Contants
{

    public static class DatabaseConst
    {
        public const string ConnectionString = "mongodb+srv://Ikkun:FhyWO9cGs22RfA9R@cluster0.zvdubot.mongodb.net/Game?appName=Cluster0";
        public const string DatabaseName = "store_game";
    }

    public static class CloudianryConst
    {
        public const string CloudName = "davfujasj";
        public const string ApiKey = "155879274742561";
        public const string ApiSecret = "j2dKa - onHxbm5jKbDJjz9SUaIrw";
        public const string FolderName = "Game";
    }

    public static class SecurityConst
    {
        public const string key = "ToiNgheoVaBanCungThe_TatCaChungTaDeuLaNoLeChoTuBan_DaylaKeyyyy";
        public const string Issuer = "game";
        public const string Audience = "game";
        public const int ExpireDays = 7;
    }
    public static class ChatboxConst
    {
        public const string GEMINI_API_KEY = "AIzaSyDMZLDAOKwEaMVVvG0yxCCFLyZYmDr4Fj8";
    }
    public static class PaymentConst
    {
        public const string MOMO_ACCESS_KEY = "klm05TvNBzhg7h7j";
        public const string MOMO_ENDPOINT = "https://test-payment.momo.vn";
        public const string MOMO_PARTNER_CODE = "MOMOBKUN20180529";
        public const string MOMO_SECRET_KEY = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";
        public const string PAYPAL_MODE = "sandbox";
        public const string PAYPAL_CLIENT_ID = "AVSq_9QjMRY47FYCHNUnO1biM_L9I84VMfj4wCsQ_qqSjb6AXFd-Zmy1ZxQny6J1EJ1yIz7K95i9pLc2";
        public const string PAYPAL_CLIENT_SECRET = "ENybO6lo8IgjI8p2vpn9mnPLjF-SDPV94xNmWLF1nDm9mVcKMcZR_DR05NtFsMtid2RMZ1O9Mg6fnVck";
    }
}