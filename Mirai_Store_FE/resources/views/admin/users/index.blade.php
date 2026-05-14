<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Quản lý người dùng
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li><a class="font-medium text-gray-500 hover:text-black dark:text-white"
                        href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
                <li class="font-medium text-black dark:text-white">Users</li>
            </ol>
        </nav>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 py-6 flex justify-between items-center border-b border-gray-200 dark:border-gray-800">
            <h3 class="text-xl font-bold text-black dark:text-white">Danh sách tài khoản</h3>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Thêm quản trị
                </a>
                <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-md">
                    Tổng: {{ count($users ?? []) }} users
                </span>
            </div>
        </div>

        <div class="p-6">
            <div class="max-w-full overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left font-medium text-gray-500">User</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500">Vai trò</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500">Trạng thái</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500">Ngày tham gia</th>
                            <th class="px-5 py-3 text-left font-medium text-gray-500">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users ?? [] as $user)
                            <tr class="border-b border-gray-50 dark:border-gray-800/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-200">
                                            @if (!empty($user['avatar']))
                                                <img src="{{ $user['avatar'] }}" alt="Avatar" class="h-full w-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user['name'] ?? 'U') }}&background=random" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="font-semibold text-black dark:text-white">{{ $user['name'] ?? 'N/A' }}</h5>
                                            <p class="text-sm text-gray-500">{{ $user['email'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($user['role'] ?? '') === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($user['role'] ?? 'user') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($user['status'] ?? '') === 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($user['status'] ?? 'active') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ !empty($user['createdAt']) ? \Carbon\Carbon::parse($user['createdAt'])->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <form action="{{ route('admin.users.toggle-status', $user['id'] ?? '') }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-gray-500 hover:text-blue-600 transition-colors">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.destroy', $user['id'] ?? '') }}" method="POST" onsubmit="return confirm('Xóa tài khoản này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors">
                                                <i class="fa-solid fa-trash"></i>
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
