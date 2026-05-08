<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <button onclick="window.history.back()"
                        class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 hover:text-miku-600 dark:hover:text-miku-400 hover:border-miku-500 transition shadow-sm">
                        <i class="fa-solid fa-arrow-left text-lg"></i>
                    </button>
                    <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                        <a href="{{ route('home') }}" class="hover:text-miku-500 transition">Trang chủ</a>
                        <span class="mx-3">/</span>
                        <span class="text-gray-800 dark:text-white font-semibold max-w-xs truncate">{{ $game->name }}</span>
                    </nav>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                
                <div class="lg:col-span-2 space-y-6">

                    
                    <div class="rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 group">
                        <div class="relative aspect-video bg-gray-900 overflow-hidden">
                            <img src="{{ $game->image }}"
                                alt="{{ $game->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 right-4 flex flex-col items-end gap-2">
                                @if($game->price == 0)
                                    <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-lg font-bold px-6 py-3 rounded-xl shadow-2xl">
                                        <i class="fa-solid fa-gift mr-2"></i>Miễn phí
                                    </span>
                                @else
                                    <span class="bg-gradient-to-r from-miku-500 to-miku-600 text-white text-lg font-bold px-6 py-3 rounded-xl shadow-2xl">
                                        {{ number_format($game->price, 0, ',', '.') }} VNĐ
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h1 class="text-4xl font-black text-gray-900 dark:text-white mb-2 bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            {{ $game->name }}</h1>

                        <div class="flex flex-wrap gap-6 mb-8 text-sm pt-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-miku-50 dark:bg-miku-900/30 flex items-center justify-center text-miku-600">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Nhà phát hành</p>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $game->publisher }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">Ngày đăng</p>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $game->release_date }}</p>
                                </div>
                            </div>
                        </div>

                        
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                            @if($game->price > 0)
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    <button type="submit"
                                        class="w-full bg-miku-500 hover:bg-miku-600 text-white font-bold py-3 px-6 rounded-xl text-center transition shadow-lg shadow-miku-500/30 flex items-center justify-center gap-2 group">
                                        <i class="fa-solid fa-cart-plus group-hover:scale-110 transition"></i>
                                        Thêm vào giỏ hàng
                                    </button>
                                </form>
                            @else
                                <a href="#"
                                    class="flex-1 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-3 px-6 rounded-xl text-center transition shadow-lg shadow-green-500/30 flex items-center justify-center gap-2 group">
                                    <i class="fa-solid fa-download group-hover:scale-110 transition"></i>
                                    Tải Game Miễn Phí
                                </a>
                            @endif

                            <button class="px-6 py-3 rounded-xl font-bold bg-white dark:bg-gray-700 text-pink-500 dark:text-pink-400 border-2 border-pink-500 dark:border-pink-400 hover:bg-pink-50 dark:hover:bg-gray-600 transition shadow-lg flex items-center justify-center gap-2 group">
                                <i class="fa-regular fa-heart group-hover:scale-110 transition"></i>
                                <span class="hidden sm:inline">Yêu thích</span>
                            </button>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-sm border border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-miku-500 to-miku-600 flex items-center justify-center text-white">
                                <i class="fa-solid fa-align-left text-sm"></i>
                            </div>
                            Giới thiệu game
                        </h2>
                        <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line text-base">
                            {{ $game->description }}
                        </div>
                    </div>
                </div>

                
                <div class="w-full space-y-6">
                    
                    <div class="bg-gradient-to-br from-[#5865F2] to-[#4752c4] rounded-2xl overflow-hidden relative h-72 flex items-center justify-center group cursor-pointer shadow-2xl border border-[#6c77ff]/50 hover:shadow-[#5865F2]/50 transition-all duration-300">
                        <img src="https://res.cloudinary.com/davfujasj/image/upload/v1765274236/Gemini_Generated_Image_l4qelbl4qelbl4qe_v2akas.png"
                            class="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-40 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#5865F2]/80 via-transparent to-transparent"></div>
                        <div class="relative z-10 text-center p-6">
                            <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-white/20 flex items-center justify-center text-2xl">
                                <i class="fa-brands fa-discord text-white"></i>
                            </div>
                            <h3 class="text-2xl font-black text-white mb-2">Cộng đồng Discord</h3>
                            <p class="text-sm font-semibold text-blue-100 mb-5 leading-relaxed">Hỗ trợ cài đặt & tìm bạn chơi cùng</p>
                            <a href="#" class="inline-block bg-yellow-400 text-gray-900 px-6 py-2 rounded-lg font-bold hover:bg-yellow-300 transition shadow-lg transform group-hover:scale-105 duration-300">
                                <i class="fa-brands fa-discord mr-2"></i>Tham gia ngay
                            </a>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-5 border-b border-gray-100 dark:border-gray-600 pb-3 flex items-center gap-2">
                            <i class="fa-solid fa-fire text-orange-500"></i>
                            Có thể bạn thích
                        </h3>
                        <div class="space-y-3">
                            @foreach($relatedGames as $related)
                                <a href="{{ route('game.show', $related->id) }}"
                                    class="flex gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                                    <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden relative shadow-md">
                                        <img src="{{ $related->image }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-miku-500 dark:group-hover:text-miku-400 transition line-clamp-2 mb-2">
                                            {{ $related->name }}
                                        </h4>
                                        <span class="text-sm font-black text-miku-600 dark:text-miku-400">{{ number_format($related->price, 0, ',', '.') }}đ</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shop-layout>