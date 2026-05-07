<x-shop-layout>
    @php
        $wishlistGameIds = session('wishlist_ids', []);
    @endphp
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">
                    Cửa Hàng Trò Chơi
                </h1>
                <p class="text-gray-500 dark:text-gray-400">Khám phá hàng ngàn tựa game hấp dẫn</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                
                <div class="lg:col-span-3 order-2 lg:order-1">

                    
                    <div
                        class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">
                            Hiển thị {{ count($games) }} kết quả
                        </span>

                        <form action="{{ route('shop.index') }}" method="GET" class="w-full sm:w-auto relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Tìm game..."
                                class="w-full sm:w-64 pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-miku-500 focus:border-transparent transition">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </form>
                    </div>

                    
                    @if (count($games) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($games as $game)
                        <div
                            class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 border border-gray-100 dark:border-gray-700 transition-all duration-300 flex flex-col h-full">

                            
                            <div class="relative h-48 overflow-hidden bg-gray-200 dark:bg-gray-700">
                                <a href="{{ route('game.show', $game['id']) }}" class="block w-full h-full">
                                    <img src="{{ $game['image'] }}"
                                        alt="{{ $game['name'] }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                                </a>

                                <div class="absolute top-3 right-3 flex flex-col items-end gap-1 pointer-events-none">
                                    @if ($game['price'] == 0)
                                    <span class="bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold px-2.5 py-1 rounded-lg shadow-sm animate-pulse">
                                        Miễn phí
                                    </span>
                                    @endif
                                </div>
                            </div>

                            
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-tight mb-2 line-clamp-2 group-hover:text-miku-500 transition-colors">
                                    <a href="{{ route('game.show', $game['id']) }}">
                                        {{ $game['name'] }}
                                    </a>
                                </h3>

                                <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700 flex items-end justify-between">
                                    <div class="flex flex-col">
                                        @if ($game['price'] == 0)
                                        <span class="text-lg font-black text-green-500">Free</span>
                                        @else
                                        <span class="text-lg font-black text-miku-600 dark:text-miku-400 leading-none">
                                            {{ number_format($game['price'], 0, ',', '.') }}đ
                                        </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-miku-500 hover:text-white transition-all shadow-sm">
                                            <i class="fa-solid fa-cart-plus text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                        <i class="fa-solid fa-ghost text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <p class="text-xl font-bold text-gray-500 dark:text-gray-400">Không tìm thấy game nào phù hợp</p>
                        <a href="{{ route('shop.index') }}" class="inline-block mt-4 text-miku-500 hover:underline font-semibold">Xóa bộ lọc</a>
                    </div>
                    @endif
                </div>

                
                <aside class="lg:col-span-1 order-1 lg:order-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Bộ lọc tìm kiếm</h2>
                        </div>
                        
                        
                        <div class="p-6">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Thể loại</h3>
                            <div class="space-y-2">
                                @foreach($categories as $cat)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-miku-500 focus:ring-miku-500">
                                    <span class="text-sm text-gray-600 dark:text-gray-400 group-hover:text-miku-500 transition-colors">{{ $cat['name'] }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        
                        <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Khoảng giá</h3>
                            <div class="space-y-2">
                                <button class="block w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">Dưới 100K</button>
                                <button class="block w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">100K - 500K</button>
                                <button class="block w-full text-left px-3 py-2 rounded-lg text-sm hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-600 dark:text-gray-400 transition-colors">Trên 500K</button>
                            </div>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </div>
</x-shop-layout>