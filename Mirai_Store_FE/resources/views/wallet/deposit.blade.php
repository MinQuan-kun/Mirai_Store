<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-credit-card text-miku-500 mr-2"></i>
                    Nạp tiền vào ví
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Chọn phương thức thanh toán và số tiền bạn muốn nạp</p>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">

                
                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-4 uppercase">
                        Chọn phương thức thanh toán
                    </label>

                    <div class="grid grid-cols-3 gap-4" x-data="{ method: 'card' }">
                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="card" x-model="method"
                                class="sr-only peer">
                            <div
                                class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-6 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 transition">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-credit-card text-5xl text-indigo-600"></i>
                                    <p class="font-bold text-gray-900 dark:text-white">Thẻ ngân hàng</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Nạp nhanh
                                    </p>
                                </div>
                            </div>
                        </label>

                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="momo" x-model="method"
                                class="sr-only peer">
                            <div
                                class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-6 peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-pink-900/20 transition">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-wallet text-5xl text-pink-600"></i>
                                    <p class="font-bold text-gray-900 dark:text-white">MoMo</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Thanh toán bằng VNĐ
                                    </p>
                                    <span class="text-xs text-green-500">(Sandbox khả dụng)</span>
                                </div>
                            </div>
                        </label>

                        
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_method" value="paypal" x-model="method"
                                class="sr-only peer">
                            <div
                                class="border-2 border-gray-200 dark:border-gray-600 rounded-xl p-6 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fab fa-paypal text-5xl text-blue-600"></i>
                                    <p class="font-bold text-gray-900 dark:text-white">PayPal</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Thanh toán bằng USD
                                    </p>
                                    <span class="text-xs text-green-500">(Sandbox khả dụng)</span>
                                </div>
                            </div>
                        </label>

                        
                        <div x-show="method === 'card'" x-transition class="col-span-3 mt-4">
                            <form action="{{ route('wallet.test.deposit') }}" method="POST">
                                @csrf
                                <div
                                    class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-indigo-800 dark:text-indigo-200">
                                        <i class="fa-solid fa-info-circle mr-1"></i>
                                        <strong>Nạp tiền nhanh:</strong> Nhập thông tin thẻ để nạp tiền ngay lập tức
                                    </p>
                                </div>

                                
                                <div
                                    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-6 mb-6">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                                        <i class="fa-solid fa-credit-card mr-2"></i>Thông tin thẻ
                                    </h3>

                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Số thẻ
                                        </label>
                                        <input type="text" name="card_number" placeholder="4242 4242 4242 4242" required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Tên chủ thẻ
                                            </label>
                                            <input type="text" name="card_holder" placeholder="NGUYEN VAN A" required
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white uppercase focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>

                                        
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Ngày hết hạn
                                            </label>
                                            <input type="text" name="card_expiry" placeholder="MM/YY" required
                                                pattern="\d{2}/\d{2}"
                                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>

                                    
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            CVV
                                        </label>
                                        <input type="text" name="card_cvv" placeholder="123" required pattern="\d{3,4}"
                                            maxlength="4"
                                            class="w-32 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono text-center focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </div>

                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    Nhập số tiền (VNĐ)
                                </label>

                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <button type="button"
                                        onclick="document.getElementById('card_amount').value = 100000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        100,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('card_amount').value = 500000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        500,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('card_amount').value = 1000000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-indigo-100 dark:hover:bg-indigo-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        1,000,000đ
                                    </button>
                                </div>

                                <input type="number" id="card_amount" name="amount" required min="1000" step="1000"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Nhập số tiền (tối thiểu 1,000đ)">

                                <button type="submit"
                                    class="mt-6 w-full bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-lock"></i>
                                    Thanh toán an toàn
                                </button>
                            </form>
                        </div>

                        
                        <div x-show="method === 'momo'" x-transition class="col-span-3 mt-4">
                            <form action="{{ route('payment.momo.deposit') }}" method="POST">
                                @csrf
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    Chọn số tiền (VNĐ)
                                </label>

                                <div class="grid grid-cols-3 gap-3 mb-4">
                                    <button type="button" onclick="document.getElementById('momo_amount').value = 50000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-pink-100 dark:hover:bg-pink-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        50,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('momo_amount').value = 100000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-pink-100 dark:hover:bg-pink-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        100,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('momo_amount').value = 200000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-pink-100 dark:hover:bg-pink-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        200,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('momo_amount').value = 500000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-pink-100 dark:hover:bg-pink-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        500,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('momo_amount').value = 1000000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-pink-100 dark:hover:bg-pink-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        1,000,000đ
                                    </button>
                                    <button type="button"
                                        onclick="document.getElementById('momo_amount').value = 2000000"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-pink-100 dark:hover:bg-pink-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        2,000,000đ
                                    </button>
                                </div>

                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hoặc nhập
                                    số tiền tùy chỉnh</label>
                                <input type="number" id="momo_amount" name="amount" required min="10000" step="1000"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-pink-500 focus:border-pink-500"
                                    placeholder="Nhập số tiền (tối thiểu 10,000đ)">

                                <button type="submit"
                                    class="mt-6 w-full bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-wallet"></i>
                                    Nạp tiền qua MoMo
                                </button>
                            </form>
                        </div>

                        
                        <div x-show="method === 'paypal'" x-transition class="col-span-3 mt-4">
                            <form action="{{ route('payment.paypal.deposit') }}" method="POST">
                                @csrf
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    Chọn số tiền (USD)
                                </label>

                                <div class="grid grid-cols-4 gap-3 mb-4">
                                    <button type="button" onclick="document.getElementById('paypal_amount').value = 10"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        $10
                                    </button>
                                    <button type="button" onclick="document.getElementById('paypal_amount').value = 20"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        $20
                                    </button>
                                    <button type="button" onclick="document.getElementById('paypal_amount').value = 50"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        $50
                                    </button>
                                    <button type="button" onclick="document.getElementById('paypal_amount').value = 100"
                                        class="py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white font-semibold transition">
                                        $100
                                    </button>
                                </div>

                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hoặc nhập
                                    số tiền tùy chỉnh</label>
                                <input type="number" id="paypal_amount" name="amount" required min="1" step="0.01"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Nhập số tiền USD (tối thiểu $1)">

                                <div
                                    class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                        <i class="fa-solid fa-info-circle mr-1"></i>
                                        <strong>Tỷ giá:</strong> 1 USD ≈ 25,000 VNĐ (tỷ giá có thể thay đổi)
                                    </p>
                                </div>

                                <button type="submit"
                                    class="mt-6 w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg flex items-center justify-center gap-2">
                                    <i class="fab fa-paypal"></i>
                                    Nạp tiền qua PayPal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-gray-900 dark:text-white mb-3">
                        <i class="fa-solid fa-shield-alt text-miku-500 mr-2"></i>
                        Thông tin quan trọng
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Giao dịch được mã hóa an toàn</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Số dư cập nhật ngay sau khi thanh toán
                            thành công</li>
                        <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Môi trường sandbox - Sử dụng tài khoản
                            test để thanh toán</li>
                    </ul>
                </div>

            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('wallet.index') }}" class="text-miku-500 hover:text-miku-600 font-medium">
                    <i class="fa-solid fa-arrow-left mr-1"></i>
                    Quay lại ví của tôi
                </a>
            </div>

        </div>
    </div>
</x-shop-layout>