<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">
                <i class="fa-solid fa-box text-miku-500 mr-2"></i>
                Đơn hàng của tôi
            </h1>

            @if($orders->isEmpty())
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-12 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                    <i class="fa-solid fa-box-open text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Chưa có đơn hàng nào</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Hãy khám phá và đặt hàng game yêu thích!</p>
                    <a href="{{ route('home') }}"
                        class="inline-block bg-miku-500 hover:bg-miku-600 text-white font-bold py-3 px-6 rounded-xl transition">
                        <i class="fa-solid fa-store mr-2"></i>Khám phá game
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                        Đơn hàng #{{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    @if($order->status == 'completed')
                                        <span
                                            class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                            <i class="fa-solid fa-check-circle"></i> Hoàn thành
                                        </span>
                                    @elseif($order->status == 'pending')
                                        <span
                                            class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <i class="fa-solid fa-clock"></i> Đang xử lý
                                        </span>
                                    @elseif($order->status == 'cancelled')
                                        <span
                                            class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <i class="fa-solid fa-times-circle"></i> Đã hủy
                                        </span>
                                    @endif

                                    <p class="text-xl font-bold text-brand-600 dark:text-brand-400 mt-2">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <i class="fa-solid fa-gamepad mr-1"></i>
                                    {{ $order->items->count() }} game
                                </p>

                                <a href="{{ route('orders.show', $order->id) }}"
                                    class="text-miku-500 hover:text-miku-600 dark:hover:text-miku-400 font-medium transition">
                                    Xem chi tiết <i class="fa-solid fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>
    </div>
</x-shop-layout>