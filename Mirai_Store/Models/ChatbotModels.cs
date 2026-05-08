namespace Mirai_Store.Models
{
    public class ChatRequest
    {
        public string Message { get; set; } = null!;
    }

    public class ChatResponse
    {
        public string Reply { get; set; } = null!;
    }
}
