@section('header_title', 'Quản lý Danh mục')

<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Danh mục sản phẩm</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Quản lý các thể loại game trong hệ thống.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-miku-500 px-6 py-3 text-sm font-bold text-white hover:bg-miku-600 shadow-lg shadow-miku-500/30 transition-all hover:-translate-y-0.5">
                <i class="fa-solid fa-plus"></i>
                Thêm Danh Mục
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4">Tên danh mục</th>
                            <th class="px-6 py-4">Mô tả</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($categories ?? [] as $cat)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $cat['name'] ?? $cat['Name'] ?? 'Untitled' }}</div>
                                    <div class="text-[10px] font-mono text-gray-400 uppercase mt-1">ID: {{ $cat['id'] ?? $cat['Id'] ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-md">
                                    {{ \Illuminate\Support\Str::limit($cat['description'] ?? $cat['Description'] ?? '', 120) }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $cat['id'] ?? $cat['Id'] ?? '') }}"
                                            class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-miku-500 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $cat['id'] ?? $cat['Id'] ?? '') }}" onsubmit="return confirm('Xóa danh mục này?');">
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
                                <td colspan="3" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-folder-open text-5xl text-gray-200 dark:text-gray-700 mb-4"></i>
                                        <p class="text-gray-400 italic">Chưa có danh mục nào.</p>
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
