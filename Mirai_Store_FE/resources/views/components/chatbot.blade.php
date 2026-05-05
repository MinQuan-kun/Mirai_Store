<div x-data="{
    isOpen: false,
    messages: [
        { text: 'Xin chào! Muki có thể giúp gì cho bạn hôm nay? 👋', isUser: false }
    ],
    userInput: '',
    isLoading: false,

    sendMessage() {
        if (this.userInput.trim() === '') return;

        // 1. Thêm tin nhắn người dùng
        this.messages.push({ text: this.userInput, isUser: true });
        const messageToSend = this.userInput;
        this.userInput = '';
        this.isLoading = true;

        // Cuộn xuống cuối
        this.$nextTick(() => {
            const chatBox = document.getElementById('chat-messages');
            chatBox.scrollTop = chatBox.scrollHeight;
        });

        // 2. Gửi AJAX
        fetch('{{ route('chatbot.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: messageToSend })
            })
            .then(res => res.json())
            .then(data => {
                // 3. Nhận phản hồi
                this.messages.push({ text: data.reply, isUser: false });
                this.isLoading = false;

                this.$nextTick(() => {
                    const chatBox = document.getElementById('chat-messages');
                    chatBox.scrollTop = chatBox.scrollHeight;
                });
            })
            .catch(() => {
                this.messages.push({ text: 'Có lỗi kết nối, vui lòng thử lại!', isUser: false });
                this.isLoading = false;
            });
    }
}" class="fixed bottom-6 right-6 z-50 font-sans flex flex-col items-end">
    

    
    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-90"
        class="mb-4 w-[350px] md:w-[400px] bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden border border-gray-200 dark:border-gray-700 flex flex-col origin-bottom-right"
        style="height: 500px; display: none;">
        

        
        <div class="bg-gradient-to-r from-miku-500 to-blue-500 p-4 text-white flex items-center gap-3 shrink-0">
            <div
                class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center overflow-hidden border border-white/30">
                <img src="{{ asset('img/Muki.gif') }}" alt="Chatbot Avatar" class="w-full h-full object-cover">
            </div>
            <div>
                <h3 class="font-bold text-lg">Hỗ trợ viên Muki</h3>
                <p class="text-xs text-white/80 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span> Online
                </p>
            </div>
            
            <button @click="isOpen = false" class="ml-auto text-white/80 hover:text-white">
                <i class="fa-solid fa-minus"></i>
            </button>
        </div>

        
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50 dark:bg-gray-900/50">
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex items-end gap-2" :class="msg.isUser ? 'justify-end' : 'justify-start'">

                    
                    <template x-if="!msg.isUser">
                        <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200 shadow-sm shrink-0">
                            <img src="{{ asset('img/Muki.gif') }}" alt="Bot" class="w-full h-full object-cover">
                        </div>
                    </template>

                    
                    <div class="max-w-[80%] p-3 rounded-2xl text-sm shadow-sm leading-relaxed"
                        :class="msg.isUser ?
                            'bg-miku-500 text-white rounded-br-none' :
                            'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-none border border-gray-200 dark:border-gray-600'">
                        <span x-text="msg.text"></span>
                    </div>
                </div>
            </template>

            
            <div x-show="isLoading" class="flex justify-start">
                <div
                    class="bg-white dark:bg-gray-700 p-3 rounded-2xl rounded-bl-none border border-gray-200 dark:border-gray-600 flex gap-1">
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-100"></span>
                    <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-200"></span>
                </div>
            </div>
        </div>

        
        <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 shrink-0">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" x-model="userInput" placeholder="Hỏi gì đó về game..."
                    class="flex-1 bg-gray-100 dark:bg-gray-700 border-none rounded-xl focus:ring-2 focus:ring-miku-500 dark:text-white px-4 py-2">
                <button type="submit" :disabled="isLoading"
                    class="w-10 h-10 bg-miku-500 hover:bg-miku-600 text-white rounded-xl flex items-center justify-center transition disabled:opacity-50">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    
    <button @click="isOpen = !isOpen"
        class="w-16 h-16 bg-miku-500 hover:bg-miku-600 text-white rounded-full shadow-2xl flex items-center justify-center transition-transform hover:scale-110 border-4 border-white dark:border-gray-800 shrink-0">
        <span x-show="!isOpen" class="text-3xl flex items-center justify-center h-full w-full p-1">
            <img src="{{ asset('img/Muki.gif') }}" alt="Chatbot Icon" class="w-full h-full object-cover rounded-full">
        </span>
        <span x-show="isOpen" class="text-2xl"><i class="fa-solid fa-xmark"></i></span>
    </button>

</div>
