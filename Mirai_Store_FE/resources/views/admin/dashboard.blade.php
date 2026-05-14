@section('header_title', 'Tổng quan hệ thống')

<x-admin-layout>
    <div class="space-y-8">
        <!-- Header Section -->
        <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-gray-800 p-8 shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                        Chào mừng trở lại, <span class="text-miku-500">{{ optional(Auth::user())->name ?? session('user_name', 'Admin') }}</span>!
                    </h2>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-xl">
                        Dưới đây là các chỉ số quan trọng về hoạt động của cửa hàng Mirai Store trong thời gian qua.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="px-4 py-2 bg-miku-50 dark:bg-miku-900/20 text-miku-600 dark:text-miku-400 rounded-xl border border-miku-100 dark:border-miku-800 text-sm font-bold">
                        <i class="fa-solid fa-calendar-day mr-2"></i> {{ now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>
            
            <!-- Abstract Background Shapes -->
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-miku-500/5 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-blue-500/5 rounded-full blur-3xl"></div>
        </div>

        @if(!empty($dashboardError))
            <div class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl text-red-700 dark:text-red-300 shadow-sm animate-pulse">
                <i class="fa-solid fa-triangle-exclamation mr-3 text-xl"></i>
                <div class="font-medium">{{ $dashboardError }}</div>
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-admin-stat-card title="Tổng Games" value="{{ $stats['games'] ?? 0 }}" icon="fa-gamepad" color="bg-blue-500" />
            <x-admin-stat-card title="Danh mục" value="{{ $stats['categories'] ?? 0 }}" icon="fa-layer-group" color="bg-purple-500" />
            <x-admin-stat-card title="Khách hàng" value="{{ $stats['users'] ?? 0 }}" icon="fa-users" color="bg-green-500" />
            <x-admin-stat-card title="Đơn hàng" value="{{ $stats['orders'] ?? 0 }}" icon="fa-cart-shopping" color="bg-orange-500" />
        </div>

        <!-- Revenue Card -->
        <div class="bg-gradient-to-br from-miku-500 to-teal-600 rounded-3xl p-8 text-white shadow-xl shadow-miku-500/20 overflow-hidden relative group">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-miku-50 font-bold uppercase tracking-wider text-xs">Tổng Doanh Thu</p>
                        <h3 class="text-4xl md:text-5xl font-black mt-2">
                            {{ number_format($stats['revenue'] ?? 0, 0, ',', '.') }} <span class="text-xl font-normal opacity-80">VND</span>
                        </h3>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl backdrop-blur-md">
                        <i class="fa-solid fa-coins"></i>
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-2 text-miku-50/80 text-sm">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Dữ liệu được cập nhật từ tất cả các đơn hàng đã hoàn tất.</span>
                </div>
            </div>
            
            <!-- Decoration -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Orders Table -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Đơn hàng mới</h3>
                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-miku-500 hover:underline">Xem tất cả</a>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                                <th class="px-6 py-4">Mã đơn</th>
                                <th class="px-6 py-4">Khách hàng</th>
                                <th class="px-6 py-4">Số tiền</th>
                                <th class="px-6 py-4">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                            @forelse($recentOrders ?? [] as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm text-gray-500">#{{ $order['orderNumber'] ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-sm">{{ $order['customerName'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 font-black text-miku-600 dark:text-miku-400">
                                        {{ number_format($order['totalAmount'] ?? 0, 0, ',', '.') }}đ
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusColor = match($order['status'] ?? 'pending') {
                                                'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'pending' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase {{ $statusColor }}">
                                            {{ $order['status'] ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">Không có đơn hàng nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty Chart Space (Reserved for future charts) -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Xu hướng doanh thu</h3>
                    <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                </div>
                
                <div class="h-64 flex items-center justify-center border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-2xl">
                    <div class="text-center">
                        <i class="fa-solid fa-clock-rotate-left text-4xl text-gray-200 dark:text-gray-600 mb-3"></i>
                        <p class="text-sm text-gray-400">Biểu đồ đang được nâng cấp...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
