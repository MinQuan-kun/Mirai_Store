@section('header_title', 'Quản lý Khuyến mãi')

<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Mã giảm giá</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Quản lý các chương trình ưu đãi và voucher.</p>
            </div>
            <a href="{{ route('admin.discounts.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-miku-500 px-6 py-3 text-sm font-bold text-white hover:bg-miku-600 shadow-lg shadow-miku-500/30 transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-plus"></i>
                Thêm Mã Mới
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4">Mã Code</th>
                            <th class="px-6 py-4">Loại / Giá trị</th>
                            <th class="px-6 py-4">Hết hạn</th>
                            <th class="px-6 py-4">Trạng thái</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($discounts ?? [] as $discount)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="px-3 py-1 bg-miku-50 dark:bg-miku-900/30 border border-miku-200 dark:border-miku-800 rounded-lg text-miku-700 dark:text-miku-400 font-mono font-bold inline-block">
                                        {{ $discount['code'] ?? $discount['Code'] ?? 'NO-CODE' }}
                                    </div>
                                    <div class="text-[9px] text-gray-400 font-mono mt-1 uppercase">ID: {{ $discount['id'] ?? $discount['Id'] ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $value = $discount['value'] ?? $discount['Value'] ?? 0;
                                        $type = $discount['type'] ?? $discount['Type'] ?? 'fixed';
                                    @endphp
                                    <div class="font-bold text-gray-900 dark:text-white">
                                        {{ $type === 'percentage' ? rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.') . '%' : number_format((float) $value, 0, ',', '.') . 'đ' }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 uppercase font-black tracking-tighter">{{ $type }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ !empty($discount['expiresAt']) ? \Carbon\Carbon::parse($discount['expiresAt'])->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(($discount['isActive'] ?? $discount['IsActive'] ?? true))
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-black uppercase text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Có hiệu lực
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-black uppercase text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Hết hạn
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.discounts.edit', $discount['id'] ?? $discount['Id'] ?? '') }}"
                                            class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-miku-500 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.discounts.destroy', $discount['id'] ?? $discount['Id'] ?? '') }}" onsubmit="return confirm('Xóa mã giảm giá này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-red-400 hover:text-red-600 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-ticket text-5xl text-gray-200 dark:text-gray-700 mb-4"></i>
                                        <p class="text-gray-400 italic">Chưa có mã giảm giá nào.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
