<x-shop-layout>
    @php
        $purchasedGameIds = $purchasedGameIds ?? [];
    @endphp
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8">
                <i class="fa-solid fa-heart text-pink-500 mr-2"></i>
                Danh sách yêu thích
            </h1>

            @if($wishlistItems->isEmpty())
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl p-12 shadow-sm border border-gray-200 dark:border-gray-700 text-center">
                    <i class="fa-solid fa-heart-crack text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700 dark:text-gray-300 mb-2">Chưa có game yêu thích</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Hãy khám phá và thêm game yêu thích vào danh sách!</p>
                    <a href="{{ route('home') }}"
                        class="inline-block bg-pink-500 hover:bg-pink-600 text-white font-bold py-3 px-6 rounded-xl transition">
                        <i class="fa-solid fa-store mr-2"></i>Khám phá game
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishlistItems as $item)
                        @if($item->game)
                            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-300">
                                
                                <div class="relative aspect-video overflow-hidden bg-gray-900">
                                    <a href="{{ route('game.show', $item->game->id) }}">
                                        <img src="{{ Str::startsWith($item->game->image, 'http') ? $item->game->image : asset('storage/' . $item->game->image) }}"
                                            alt="{{ $item->game->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </a>
                                    
                                    
                                    <form action="{{ route('wishlist.remove', $item->game->id) }}" method="POST" class="absolute top-3 right-3">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="w-10 h-10 rounded-full bg-white/90 hover:bg-white text-pink-500 hover:text-pink-600 flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-110">
                                            <i class="fa-solid fa-heart"></i>
                                        </button>
                                    </form>

                                    
                                    <div class="absolute bottom-3 right-3">
                                        @if($item->game->price == 0)
                                            <span class="bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-bold px-4 py-2 rounded-lg shadow-lg">
                                                <i class="fa-solid fa-gift mr-1"></i>Miễn phí
                                            </span>
                                        @else
                                            <span class="bg-gradient-to-r from-miku-500 to-miku-600 text-white text-sm font-bold px-4 py-2 rounded-lg shadow-lg">
                                                {{ number_format($item->game->price, 0, ',', '.') }} VNĐ
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                
                                <div class="p-5">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                        <a href="{{ route('game.show', $item->game->id) }}" class="hover:text-miku-500 transition">
                                            {{ $item->game->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $item->game->publisher }}</p>

                                    
                                    <div class="space-y-2">
                                        @if(in_array((string) $item->game->id, array_map('strval', $purchasedGameIds), true))
                                            
                                            <a href="{{ $item->game->download_link }}" target="_blank"
                                                class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg text-center transition shadow-md">
                                                <i class="fa-solid fa-download mr-2"></i>Tải Game
                                            </a>
                                        @else
                                            
                                            @if($item->game->price > 0)
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="game_id" value="{{ $item->game->id }}">
                                                    <button type="submit"
                                                        class="w-full bg-gradient-to-r from-miku-500 to-miku-600 hover:from-miku-600 hover:to-miku-700 text-white font-bold py-2.5 px-4 rounded-lg text-center transition shadow-md">
                                                        <i class="fa-solid fa-cart-plus mr-2"></i>Thêm vào giỏ hàng
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ $item->game->download_link ?? '#' }}" target="_blank" @if(empty($item->game->download_link)) aria-disabled="true" @endif
                                                    class="block w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-2.5 px-4 rounded-lg text-center transition shadow-md {{ empty($item->game->download_link) ? 'pointer-events-none opacity-60' : '' }}">
                                                    <i class="fa-solid fa-download mr-2"></i>Tải Game Miễn Phí
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                
                <div class="mt-8 text-center">
                    <a href="{{ route('home') }}"
                        class="inline-block text-miku-500 hover:text-miku-600 font-medium">
                        <i class="fa-solid fa-arrow-left mr-1"></i>Tiếp tục khám phá game
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-shop-layout>
