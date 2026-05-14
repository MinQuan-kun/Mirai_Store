<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Quản lý Thể loại
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-black dark:text-white" href="{{ route('admin.dashboard') }}">
                        Dashboard /
                    </a>
                </li>
                <li class="font-medium text-black dark:text-white">Categories</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-xl font-bold text-black dark:text-white">
                Danh sách thể loại
            </h3>
            <div class="flex items-center gap-4">
                <span
                    class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    Tổng: {{ count($categories ?? []) }} thể loại
                </span>
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="3">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Thêm Thể loại
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="max-w-full overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-white/5">
                            <th class="px-5 py-3 sm:px-6">
                                <p class="text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tên thể loại</p>
                            </th>
                            <th class="px-5 py-3 sm:px-6">
                                <p class="text-left font-medium text-gray-500 text-theme-xs dark:text-gray-400">Đường dẫn (Slug)</p>
                            </th>
                            <th class="px-5 py-3 sm:px-6 text-right">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Thao tác</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($categories ?? [] as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                <td class="px-6 py-4">
                                    <h5 class="font-semibold text-black dark:text-white">{{ $category['name'] ?? 'N/A' }}</h5>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">/{{ $category['slug'] ?? '' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end space-x-3">
                                        <a href="{{ route('admin.categories.edit', $category['id'] ?? '') }}"
                                            class="p-2 rounded-full text-gray-500 hover:bg-gray-100 transition-all"
                                            title="Chỉnh sửa">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category['id'] ?? '') }}" method="POST" onsubmit="return confirm('Xóa danh mục này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-full text-gray-500 hover:bg-red-50 hover:text-red-600 transition-all" title="Xóa">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>
