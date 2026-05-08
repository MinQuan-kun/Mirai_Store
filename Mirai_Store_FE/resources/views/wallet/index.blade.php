<x-shop-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen transition-colors duration-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                    <i class="fa-solid fa-wallet text-miku-500 mr-2"></i>
                    Ví của tôi
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Quản lý số dư và lịch sử giao dịch</p>
            </div>

            
            <div class="bg-gradient-to-br from-miku-500 to-brand-600 rounded-2xl p-8 shadow-xl mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white/80 text-sm font-medium uppercase tracking-wide">Số dư hiện tại</p>
                        <p class="text-white text-4xl font-bold mt-2">
                            {{ number_format($user->balance ?? 0, 0, ',', '.') }} <span class="text-xl">VNĐ</span>
                        </p>
                    </div>
                    <div class="bg-white/20 backdrop-blur p-4 rounded-xl">
                        <i class="fa-solid fa-coins text-white text-4xl"></i>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('wallet.deposit') }}" 
                       class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i>
                        Nạp tiền
                    </a>
                </div>
            </div>

            
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <i class="fa-solid fa-history text-miku-500 mr-2"></i>
                        Lịch sử giao dịch
                    </h2>
                    
                    
                    <div class="flex gap-2">
                        <a href="{{ route('wallet.index') }}" 
                           class="px-4 py-2 text-sm rounded-lg {{ !request('filter') || request('filter') == 'all' ? 'bg-miku-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Tất cả
                        </a>
                        <a href="{{ route('wallet.index', ['filter' => 'deposit']) }}" 
                           class="px-4 py-2 text-sm rounded-lg {{ request('filter') == 'deposit' ? 'bg-miku-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Nạp tiền
                        </a>
                        <a href="{{ route('wallet.index', ['filter' => 'purchase']) }}" 
                           class="px-4 py-2 text-sm rounded-lg {{ request('filter') == 'purchase' ? 'bg-miku-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            Mua hàng
                        </a>
                    </div>
                </div>

                @if($transactions->isEmpty())
                    <div class="text-center py-12">
                        <i class="fa-solid fa-receipt text-gray-300 dark:text-gray-600 text-6xl mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">Chưa có giao dịch nào</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ngày</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mô tả</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Loại</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trạng thái</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Số tiền</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $transaction->description }}
                                            @if($transaction->payment_method)
                                                <span class="text-xs text-gray-500">({{ strtoupper($transaction->payment_method) }})</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->type == 'deposit')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <i class="fa-solid fa-arrow-down"></i> Nạp tiền
                                                </span>
                                            @elseif($transaction->type == 'purchase')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    <i class="fa-solid fa-shopping-cart"></i> Mua hàng
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                    {{ ucfirst($transaction->type) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($transaction->status == 'completed')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                    <i class="fa-solid fa-check-circle"></i> Hoàn thành
                                                </span>
                                            @elseif($transaction->status == 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                    <i class="fa-solid fa-clock"></i> Đang xử lý
                                                </span>
                                            @elseif($transaction->status == 'cancelled')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                    <i class="fa-solid fa-ban"></i> Đã hủy
                                                </span>
                                            @elseif($transaction->status == 'failed')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                    <i class="fa-solid fa-times-circle"></i> Thất bại
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                    {{ ucfirst($transaction->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold">
                                            @if($transaction->type == 'deposit')
                                                <span class="text-green-600 dark:text-green-400">
                                                    +{{ number_format($transaction->amount, 0, ',', '.') }} VNĐ
                                                </span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400">
                                                    -{{ number_format($transaction->amount, 0, ',', '.') }} VNĐ
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                            @if($transaction->status == 'pending')
                                                <form action="{{ route('wallet.transaction.cancel', $transaction->id) }}" method="POST" 
                                                      onsubmit="return confirm('Bạn có chắc muốn hủy giao dịch này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                                        <i class="fa-solid fa-times-circle"></i> Hủy
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="mt-6">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-shop-layout>
