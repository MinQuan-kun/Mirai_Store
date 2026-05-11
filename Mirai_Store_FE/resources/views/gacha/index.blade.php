<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-12 transition-colors duration-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <p class="text-sm uppercase tracking-[0.3em] text-miku-500 font-bold mb-3">Gacha</p>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white">Quay game ngẫu nhiên</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-3">Bấm quay lại để nhận một tựa game bất kỳ từ kho game.</p>
            </div>

            @if(session('error'))
                <div class="mb-6 rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-red-700 dark:text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            @if(!empty($error))
                <div class="mb-6 rounded-xl border border-red-200 dark:border-red-900/40 bg-red-50 dark:bg-red-900/20 px-4 py-3 text-red-700 dark:text-red-300">
                    {{ $error }}
                </div>
            @endif

            @if($game)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                        <div class="relative aspect-[4/3] bg-gray-900">
                            <img src="{{ $game->image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-5 left-5 right-5">
                                <p class="text-white/70 text-sm uppercase tracking-[0.2em] font-bold mb-2">Game trúng thưởng</p>
                                <h2 class="text-3xl md:text-4xl font-black text-white leading-tight">{{ $game->name }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-xl p-8 flex flex-col">
                        <div class="mb-6">
                            <div class="flex items-center gap-3 mb-4">
                                @if($game->price == 0)
                                    <span class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900/30 px-3 py-1 text-sm font-bold text-green-600 dark:text-green-300">Miễn phí</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-miku-100 dark:bg-miku-900/30 px-3 py-1 text-sm font-bold text-miku-600 dark:text-miku-300">{{ number_format($game->price, 0, ',', '.') }}đ</span>
                                @endif
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $game->publisher }}</span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $game->description ?: 'Chưa có mô tả chi tiết cho game này.' }}</p>
                        </div>

                        <div class="mt-auto space-y-3">
                            <a href="{{ route('gacha') }}" class="block w-full text-center rounded-xl bg-gradient-to-r from-miku-500 to-miku-600 hover:from-miku-600 hover:to-miku-700 text-white font-bold py-3 px-4 shadow-lg transition">Quay tiếp</a>
                            <a href="{{ route('game.show', $game->id) }}" class="block w-full text-center rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 font-bold py-3 px-4 transition hover:bg-gray-200 dark:hover:bg-gray-600">Xem chi tiết</a>
                            @if($game->price == 0)
                                <a href="{{ $game->download_link ?? route('game.show', $game->id) }}" class="block w-full text-center rounded-xl bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 transition {{ empty($game->download_link) ? 'pointer-events-none opacity-60' : '' }}">Tải game miễn phí</a>
                            @else
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    <button type="submit" class="w-full rounded-xl bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900 font-bold py-3 px-4 transition hover:opacity-90">Thêm vào giỏ hàng</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-3xl bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 p-12 text-center shadow-sm">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-miku-100 dark:bg-miku-900/30 text-miku-600 dark:text-miku-300 text-2xl">
                        <i class="fa-solid fa-dice"></i>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-3">Chưa có game để quay</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Backend chưa trả về dữ liệu gacha hợp lệ hoặc không còn game hoạt động.</p>
                    <a href="{{ route('home') }}" class="inline-flex items-center rounded-xl bg-miku-500 hover:bg-miku-600 text-white font-bold px-5 py-3 transition">Về trang chủ</a>
                </div>
            @endif
        </div>
    </div>
</x-shop-layout>
