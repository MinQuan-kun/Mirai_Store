<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">
                <i class="fa-solid fa-shopping-cart text-miku-500 mr-2"></i>
                Giỏ game của bạn
            </h1>

            @if($cartItems->isEmpty())
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-12 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                    <i class="fa-solid fa-cart-shopping text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Giỏ hàng trống</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Hãy khám phá và thêm game yêu thích vào giỏ hàng!</p>
                    <a href="{{ route('home') }}"
                        class="inline-block bg-miku-500 hover:bg-miku-600 text-white font-bold py-3 px-6 rounded-xl transition">
                        <i class="fa-solid fa-store mr-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($cartItems as $item)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 flex gap-4">
                                
                                <div class="flex-shrink-0 w-32 h-32 rounded-lg overflow-hidden">
                                    <img src="{{ Str::startsWith($item->game->image, 'http') ? $item->game->image : asset('storage/' . $item->game->image) }}"
                                        alt="{{ $item->game->name }}" class="w-full h-full object-cover">
                                </div>

                                
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                        <a href="{{ route('game.show', $item->game->id) }}" class="hover:text-miku-500">
                                            {{ $item->game->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ $item->game->publisher }}</p>

                                    <div class="flex items-center justify-between">
                                        <p class="text-xl font-bold text-miku-600 dark:text-miku-400">
                                            {{ number_format($item->price_at_time, 0, ',', '.') }} VNĐ
                                        </p>

                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-medium transition">
                                                <i class="fa-solid fa-trash mr-1"></i>Xóa
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 sticky top-4">
                            <h2
                                class="text-xl font-bold text-gray-900 dark:text-white mb-6 pb-3 border-b border-gray-200 dark:border-gray-700">
                                Tóm tắt đơn hàng
                            </h2>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Số lượng:</span>
                                    <span class="font-semibold">{{ $cartItems->count() }} game</span>
                                </div>
                                <div class="flex justify-between text-gray-700 dark:text-gray-300">
                                    <span>Tạm tính:</span>
                                    <span class="font-semibold">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-200 dark:border-gray-700 mb-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold text-gray-900 dark:text-white">Tổng cộng:</span>
                                    <span class="text-2xl font-bold text-miku-600 dark:text-miku-400">
                                        {{ number_format($total, 0, ',', '.') }} VNĐ
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('checkout') }}"
                                class="block w-full bg-miku-500 hover:bg-miku-600 text-white font-bold py-4 px-6 rounded-xl text-center transition shadow-lg mb-3">
                                <i class="fa-solid fa-lock mr-2"></i>Thanh toán
                            </a>

                            <a href="{{ route('home') }}"
                                class="block text-center text-miku-500 hover:text-miku-600 font-medium">
                                <i class="fa-solid fa-arrow-left mr-1"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-shop-layout>