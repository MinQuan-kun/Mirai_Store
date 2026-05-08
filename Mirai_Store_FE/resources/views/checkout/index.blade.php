<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">
                <i class="fa-solid fa-cash-register text-miku-500 mr-2"></i>
                Thanh toán
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Các sản phẩm</h2>

                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                                <div
                                    class="flex gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                                    <img src="{{ Str::startsWith($item->game->image, 'http') ? $item->game->image : asset('storage/' . $item->game->image) }}"
                                        alt="{{ $item->game->name }}" class="w-20 h-20 rounded-lg object-cover">

                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $item->game->name }}</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->game->publisher }}</p>
                                        <p class="text-brand-600 dark:text-brand-400 font-bold mt-1">
                                            {{ number_format($item->price_at_time, 0, ',', '.') }} VNĐ
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    
                    <div class="bg-gradient-to-br from-miku-500 to-brand-600 rounded-xl p-6 shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-white/80 text-sm font-medium uppercase">Số dư ví hiện tại</p>
                                <p class="text-3xl font-bold mt-1">
                                    {{ number_format($user->balance, 0, ',', '.') }} VNĐ
                                </p>
                            </div>
                            <i class="fa-solid fa-wallet text-white/30 text-5xl"></i>
                        </div>

                        @if($user->balance < $total)
                            <div class="mt-4 bg-red-500/20 border border-red-400 rounded-lg p-3">
                                <p class="text-sm font-medium">
                                    <i class="fa-solid fa-exclamation-triangle mr-1"></i>
                                    Số dư không đủ! Thiếu: {{ number_format($total - $user->balance, 0, ',', '.') }} VNĐ
                                </p>
                                <a href="{{ route('wallet.deposit') }}"
                                    class="text-sm underline hover:no-underline mt-1 inline-block">
                                    Nạp thêm tiền →
                                </a>
                            </div>
                        @else
                            <div class="mt-4 bg-green-500/20 border border-green-400 rounded-lg p-3">
                                <p class="text-sm font-medium">
                                    <i class="fa-solid fa-check-circle mr-1"></i>
                                    Số dư đủ để thanh toán
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg border border-gray-200 dark:border-gray-700 sticky top-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Thanh toán</h2>

                        
                        <div class="mb-6">
                            <label for="discount_code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <i class="fa-solid fa-tag mr-1 text-miku-500"></i>Mã giảm giá
                            </label>
                            <input type="text" name="discount_code" id="discount_code"
                                placeholder="Nhập mã giảm giá (VD: GAME10)"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-miku-500 focus:border-miku-500 transition uppercase">
                            <p id="discount-message" class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                Mã sẽ được áp dụng khi thanh toán
                            </p>
                        </div>


                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-700 dark:text-gray-300 text-base">
                                <span>Tạm tính:</span>
                                <span class="font-bold text-lg">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                            </div>

                            
                            <div id="discount-row"
                                class="flex justify-between text-green-600 dark:text-green-400 text-base hidden">
                                <span>Giảm giá:</span>
                                <span class="font-bold text-lg" id="discount-amount">- 0 VNĐ</span>
                            </div>

                            <div class="flex justify-between text-gray-700 dark:text-gray-300 text-base">
                                <span>Phương thức:</span>
                                <span class="font-semibold text-miku-500 text-base">Ví điện tử</span>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700 mb-8">
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-gray-900 dark:text-white">Tổng thanh toán:</span>
                                <span class="text-3xl font-bold text-brand-600" id="final-total">
                                    {{ number_format($total, 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="discount_code" id="hidden_discount_code" value="">
                            <button type="submit" @if($user->balance < $total) disabled @endif
                                onclick="document.getElementById('hidden_discount_code').value = document.getElementById('discount_code').value"
                                class="w-full bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg disabled:opacity-50 disabled:cursor-not-allowed mb-3">
                                <i class="fa-solid fa-check-circle mr-2"></i>
                                Xác nhận thanh toán
                            </button>
                        </form>

                        <a href="{{ route('cart.index') }}"
                            class="block text-center text-gray-600 dark:text-gray-400 hover:text-miku-500 dark:hover:text-miku-400 font-medium">
                            <i class="fa-solid fa-arrow-left mr-1"></i>Quay lại giỏ hàng
                        </a>

                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                <i class="fa-solid fa-shield-alt mr-1"></i>
                                Giao dịch được bảo mật và mã hóa
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        let discountTimeout;
        const originalTotal = {{ $total }};
        let currentDiscountAmount = 0;

        document.getElementById('discount_code').addEventListener('input', function (e) {
            const code = e.target.value.trim();

            // Clear previous timeout
            clearTimeout(discountTimeout);

            if (code.length === 0) {
                resetDiscount();
                return;
            }

            // Debounce for 500ms
            discountTimeout = setTimeout(() => {
                validateDiscountCode(code);
            }, 500);
        });

        function validateDiscountCode(code) {
            const messageDiv = document.getElementById('discount-message');
            const discountRow = document.getElementById('discount-row');
            const finalTotalSpan = document.getElementById('final-total');

            // Show loading state
            messageDiv.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Đang kiểm tra...';
            messageDiv.className = 'mt-2 text-sm text-gray-500 dark:text-gray-400';

            fetch('{{ route('checkout.validate.discount') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    total: originalTotal
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.valid) {
                        // Valid discount code
                        currentDiscountAmount = data.discount_amount;

                        messageDiv.innerHTML = '<i class="fa-solid fa-check-circle mr-1"></i> ' + data.message;
                        messageDiv.className = 'mt-2 text-sm text-green-600 dark:text-green-400 font-medium';

                        // Show discount breakdown
                        discountRow.classList.remove('hidden');
                        document.getElementById('discount-amount').textContent =
                            '- ' + new Intl.NumberFormat('vi-VN').format(data.discount_amount) + ' VNĐ';

                        // Update final total
                        finalTotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(data.final_total) + ' VNĐ';
                        finalTotalSpan.classList.remove('text-brand-600');
                        finalTotalSpan.classList.add('text-green-600', 'dark:text-green-400');

                    } else {
                        // Invalid discount code
                        currentDiscountAmount = 0;

                        messageDiv.innerHTML = '<i class="fa-solid fa-times-circle mr-1"></i> ' + data.message;
                        messageDiv.className = 'mt-2 text-sm text-red-600 dark:text-red-400 font-medium';

                        resetDiscount();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    messageDiv.innerHTML = '<i class="fa-solid fa-exclamation-triangle mr-1"></i> Lỗi khi kiểm tra mã';
                    messageDiv.className = 'mt-2 text-sm text-red-600 dark:text-red-400';
                    resetDiscount();
                });
        }

        function resetDiscount() {
            const discountRow = document.getElementById('discount-row');
            const finalTotalSpan = document.getElementById('final-total');
            const messageDiv = document.getElementById('discount-message');

            currentDiscountAmount = 0;
            discountRow.classList.add('hidden');
            finalTotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(originalTotal) + ' VNĐ';
            finalTotalSpan.classList.remove('text-green-600', 'dark:text-green-400');
            finalTotalSpan.classList.add('text-brand-600');

            if (document.getElementById('discount_code').value.trim().length === 0) {
                messageDiv.innerHTML = '<i class="fa-solid fa-info-circle mr-1"></i> Mã sẽ được áp dụng khi thanh toán';
                messageDiv.className = 'mt-2 text-xs text-gray-500 dark:text-gray-400';
            }
        }
    </script>
</x-shop-layout>