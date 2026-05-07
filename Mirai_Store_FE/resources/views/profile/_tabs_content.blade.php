
<div x-show="activeTab === 'history'" x-cloak>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Thư viện Game</h3>
    @if($purchasedGames->isEmpty())
        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
            <i class="fas fa-ghost text-6xl mb-4 opacity-50"></i>
            <p>Chưa có game nào trong thư viện.</p>
            <a href="/" class="text-miku-600 hover:underline mt-2 inline-block">Dạo cửa hàng ngay</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($purchasedGames as $game)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-miku-500 transition group">
                    <div class="aspect-video relative overflow-hidden">
                        <img src="{{ Str::startsWith($game->image, 'http') ? $game->image : asset('storage/' . $game->image) }}" 
                             alt="{{ $game->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">{{ $game->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $game->publisher }}</p>
                        <a href="{{ $game->download_link }}" target="_blank" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition font-medium">
                            <i class="fa-solid fa-download mr-2"></i>Tải Game
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


<div x-show="activeTab === 'billing'" x-cloak>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Lịch sử giao dịch</h3>
    @if($transactions->isEmpty())
        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
            <i class="fas fa-file-invoice-dollar text-6xl mb-4 opacity-50"></i>
            <p>Chưa có giao dịch nào được ghi nhận.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            @if($transaction->type === 'deposit')
                                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-down text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">Nạp tiền</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->payment_method ?? 'N/A' }}</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-shopping-cart text-red-600 dark:text-red-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">Mua game</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Order #{{ substr($transaction->order_id ?? 'N/A', 0, 8) }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $transaction->type === 'deposit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->type === 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', '.') }} VNĐ
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($transaction->status)
                        <div class="mt-2">
                            <span class="text-xs px-2 py-1 rounded-full 
                                {{ $transaction->status === 'completed' ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400' : '' }}
                                {{ $transaction->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400' : '' }}
                                {{ $transaction->status === 'failed' ? 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400' : '' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
