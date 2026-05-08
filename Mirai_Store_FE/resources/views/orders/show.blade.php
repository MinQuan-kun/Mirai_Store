<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-8 shadow-xl mb-8 text-white">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 backdrop-blur p-4 rounded-full">
                        <i class="fa-solid fa-check-circle text-4xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Đơn hàng thành công!</h1>
                        <p class="text-white/90 mt-1">Đơn hàng #{{ $order->order_number }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                            <i class="fa-solid fa-gamepad text-miku-500 mr-2"></i>
                            Các sản phẩm đã mua
                        </h2>

                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div
                                    class="flex gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                                    @php
                                        $imageSrc = asset('img/no-image.png'); // Ảnh mặc định nếu game bị xóa
                                        if ($item->game) {
                                            $imageSrc = Str::startsWith($item->game->image, 'http')
                                                ? $item->game->image
                                                : asset('storage/' . $item->game->image);
                                        }
                                    @endphp

                                    <img src="{{ $imageSrc }}" alt="{{ $item->game?->name ?? 'Game Deleted' }}"
                                        class="w-24 h-24 rounded-lg object-cover grayscale-0 {{ !$item->game ? 'grayscale opacity-50' : '' }}">

                                    <div class="flex-1">
                                        
                                        <h3 class="font-bold text-gray-900 dark:text-white mb-1">
                                            {{ $item->game?->name ?? 'Sản phẩm không còn tồn tại' }}
                                        </h3>

                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                            {{ $item->game?->publisher ?? 'N/A' }}
                                        </p>

                                        <p class="text-brand-600 dark:text-brand-400 font-bold">
                                            {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                        </p>

                                        
                                        @if ($item->game && $item->game->download_link)
                                            <a href="{{ $item->game->download_link }}" target="_blank"
                                                class="inline-block mt-3 bg-miku-500 hover:bg-miku-600 text-white text-sm font-bold py-2 px-4 rounded-lg transition">
                                                <i class="fa-solid fa-download mr-1"></i>
                                                Tải game
                                            </a>
                                        @elseif(!$item->game)
                                            <span class="inline-block mt-3 text-red-500 text-sm italic">
                                                <i class="fa-solid fa-circle-exclamation mr-1"></i>
                                                Game đã bị gỡ khỏi hệ thống
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6">
                        <h3 class="font-bold text-blue-900 dark:text-blue-200 mb-2">
                            <i class="fa-solid fa-info-circle mr-2"></i>
                            Hướng dẫn
                        </h3>
                        <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-300">
                            <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Nhấn nút "Tải game" để tải xuống
                            </li>
                            <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Giải nén và cài đặt theo hướng dẫn
                            </li>
                            <li><i class="fa-solid fa-check text-green-500 mr-2"></i>Bạn có thể tải lại bất cứ lúc nào
                                từ trang đơn hàng</li>
                        </ul>
                    </div>
                </div>

                
                <div class="lg:col-span-1">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 sticky top-4 space-y-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Thông tin đơn hàng</h2>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Mã đơn hàng:</span>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Ngày đặt:</span>
                                    <span
                                        class="font-semibold text-gray-900 dark:text-white">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Phương thức:</span>
                                    <span
                                        class="font-semibold text-miku-500">{{ ucfirst($order->payment_method) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Trạng thái:</span>
                                    @if ($order->status == 'completed')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                            Hoàn thành
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Tạm tính:</span>
                                <span class="text-gray-900 dark:text-white font-semibold">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                            <div
                                class="flex justify-between items-center pt-3 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-lg font-bold text-gray-900 dark:text-white">Tổng cộng:</span>
                                <span class="text-2xl font-bold text-brand-600">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <a href="{{ route('orders.index') }}"
                                class="block w-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-bold py-3 px-4 rounded-xl text-center transition">
                                <i class="fa-solid fa-box mr-2"></i>Xem đơn hàng khác
                            </a>
                            <a href="{{ route('home') }}"
                                class="block w-full bg-miku-500 hover:bg-miku-600 text-white font-bold py-3 px-4 rounded-xl text-center transition">
                                <i class="fa-solid fa-store mr-2"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-shop-layout>
