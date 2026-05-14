<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Quản lý đơn hàng
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium text-gray-500 hover:text-black dark:text-white"
                        href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
                <li class="font-medium text-black dark:text-white">Orders</li>
            </ol>
        </nav>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-black dark:text-white">Danh sách đơn hàng</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 text-xs uppercase text-gray-500 font-semibold">
                            <th class="px-6 py-4 text-left">Mã Đơn</th>
                            <th class="px-6 py-4 text-left">Khách Hàng</th>
                            <th class="px-6 py-4 text-right">Tổng Tiền</th>
                            <th class="px-6 py-4 text-center">Trạng Thái</th>
                            <th class="px-6 py-4 text-right">Ngày Tạo</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($orders ?? [] as $order)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4 text-sm font-medium text-indigo-600">#{{ $order['orderNumber'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">{{ $order['customerName'] ?? 'Guest' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-bold">{{ number_format($order['totalAmount'] ?? 0, 0, ',', '.') }} đ</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($order['status'] ?? '') === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order['status'] ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">
                                    {{ !empty($order['createdAt']) ? \Carbon\Carbon::parse($order['createdAt'])->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">Chi tiết</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
