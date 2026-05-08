<x-shop-layout>
    @php
        $wishlistGameIds = session('wishlist_ids', []);
    @endphp
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300">

        <x-banner />

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            
            <div class="mb-8 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6"
                x-data="searchAutocomplete()">
                <div class="flex flex-col gap-4">
                    <div class="relative">
                        <input type="text" name="search" placeholder="🔍 Tìm kiếm game..."
                            value="{{ request('search') }}" @input="search($el.value)" @focus="open = true"
                            @keydown.escape="open = false"
                            class="w-full px-5 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg focus:ring-0 focus:border-miku-500 outline-none transition text-lg">

                        
                        <div x-show="open && suggestions.length > 0" @click.outside="closeSuggestions()"
                            class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border-2 border-miku-300 dark:border-miku-600 rounded-lg shadow-2xl z-50 max-h-96 overflow-y-auto">
                            <template x-for="(game, idx) in suggestions" :key="idx">
                                <a :href="`{{ url('/game') }}/${game.id}`" @click="closeSuggestions()"
                                    class="flex items-center gap-3 p-4 hover:bg-miku-50 dark:hover:bg-miku-900/20 transition border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <img :src="game.image" :alt="game.name"
                                        class="w-12 h-16 object-cover rounded-lg shadow-sm">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate"
                                            x-text="game.name"></p>
                                        <p class="text-xs text-miku-600 dark:text-miku-400 font-bold"
                                            x-text="game.price_formatted"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            
            <div>
                
                @if (isset($recommendedGames) && count($recommendedGames) > 0)
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-6">
                        <div
                            class="p-2 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg text-white shadow-lg">
                            <i class="fa-solid fa-wand-magic-sparkles text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-wide">
                                @if(session('user'))
                                Gợi ý dành riêng cho bạn
                                @else
                                Game Nổi Bật & Bán Chạy
                                @endif
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if(session('user'))
                                Dựa trên sở thích của bạn
                                @else
                                Những tựa game được yêu thích nhất
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($recommendedGames as $recGame)
                        <div
                            class="group relative bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl border border-gray-200 dark:border-gray-700 transition-all duration-300 hover:-translate-y-1">
                            
                            <div class="relative h-40 overflow-hidden">
                                <img src="{{ $recGame->image }}"
                                    alt="{{ $recGame->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                @if ($recGame->price == 0)
                                <span
                                    class="absolute top-2 right-2 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded">FREE</span>
                                @endif
                            </div>

                            
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 dark:text-white truncate mb-1"
                                    title="{{ $recGame->name }}">
                                    <a href="{{ route('game.show', $recGame->id) }}" class="after:absolute after:inset-0">{{ $recGame->name }}</a>
                                </h3>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-sm font-bold text-miku-600 dark:text-miku-400">
                                        {{ number_format($recGame->price, 0, ',', '.') }}đ
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between mb-6 border-l-4 border-miku-500 pl-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                        @if (request('category'))
                        {{ $category_name ?? 'Danh mục' }}
                        @else
                        Game Mới
                        @endif
                    </h2>
                    <a href="/shop" class="text-sm text-miku-600 dark:text-miku-400 hover:underline">
                        Xem tất cả <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach ($games as $game)
                        <div
                            class="group relative bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-gray-100 dark:border-gray-700 transition-all duration-300 flex flex-col h-full">

                        
                        <div class="relative h-48 overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <a href="{{ route('game.show', $game->id) }}" class="block w-full h-full">
                                <img src="{{ $game->image }}"
                                    alt="{{ $game->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 ease-out">
                            </a>

                            <div class="absolute top-2 right-2 flex flex-col items-end gap-1">
                                @if ($game->price == 0)
                                <span
                                    class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded shadow animate-pulse">
                                    Miễn phí
                                </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="p-4 flex flex-col flex-grow">
                            
                            <h3
                                class="text-base font-bold text-gray-900 dark:text-white leading-tight mb-2 line-clamp-2 hover:text-miku-600 dark:hover:text-miku-400 transition-colors">
                                <a href="{{ route('game.show', $game->id) }}" class="after:absolute after:inset-0">
                                    {{ $game->name }}
                                </a>
                            </h3>

                            <div class="mb-4 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <span>{{ $game->category ?? 'Game' }}</span>
                            </div>

                            
                            <div class="mt-auto pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                                <div class="flex flex-col">
                                    @if ($game->price == 0)
                                    <span class="text-lg font-bold text-green-500">Miễn phí</span>
                                    @else
                                    <span class="text-lg font-black text-miku-600 dark:text-miku-400">{{ number_format($game->price, 0, ',', '.') }}đ</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <button class="relative z-10 w-10 h-10 flex items-center justify-center rounded-full bg-miku-50 dark:bg-miku-900/30 text-miku-600 dark:text-miku-400 hover:bg-miku-500 hover:text-white transition-all">
                                        <i class="fa-solid fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                
                @if(is_object($games) && method_exists($games, 'links'))
                <div class="mt-8">
                    {{ $games->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-shop-layout>