@section('header_title', 'Quản lý Games')

<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Danh sách Games</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Quản lý các sản phẩm game đang có trên cửa hàng.</p>
            </div>
            <a href="{{ route('admin.games.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-miku-500 px-6 py-3 text-sm font-bold text-white hover:bg-miku-600 shadow-lg shadow-miku-500/30 transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-plus"></i>
                Thêm Game Mới
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4">Sản phẩm</th>
                            <th class="px-6 py-4">Danh mục</th>
                            <th class="px-6 py-4">Giá bán</th>
                            <th class="px-6 py-4">Trạng thái</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($games ?? [] as $game)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 overflow-hidden shadow-sm shrink-0">
                                            <img src="{{ $game['imageUrl'] ?? $game['image'] ?? asset('images/placeholder.png') }}" 
                                                 alt="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-bold text-gray-900 dark:text-white truncate max-w-[250px]">{{ $game['title'] ?? $game['name'] ?? 'Untitled' }}</div>
                                            <div class="text-[10px] font-mono text-gray-400 uppercase mt-1">ID: {{ $game['id'] ?? $game['Id'] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md">
                                        {{ $game['categoryName'] ?? $game['category_name'] ?? 'Chưa phân loại' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-black text-miku-600 dark:text-miku-400">
                                    {{ number_format((float)($game['price'] ?? 0), 0, ',', '.') }}đ
                                </td>
                                <td class="px-6 py-4">
                                    @if(($game['isActive'] ?? $game['is_active'] ?? true))
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-black uppercase text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đang bán
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-black uppercase text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Đã ẩn
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.games.edit', $game['id'] ?? $game['Id'] ?? '') }}"
                                            class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-miku-500 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.games.toggle-status', $game['id'] ?? $game['Id'] ?? '') }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-orange-500 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                                <i class="fa-solid fa-eye-slash"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.games.destroy', $game['id'] ?? $game['Id'] ?? '') }}" onsubmit="return confirm('Xóa game này?');">
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
                                        <i class="fa-solid fa-box-open text-5xl text-gray-200 dark:text-gray-700 mb-4"></i>
                                        <p class="text-gray-400 italic">Không tìm thấy game nào trong hệ thống.</p>
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
