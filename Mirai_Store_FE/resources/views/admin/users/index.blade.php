@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold dark:text-white">Quản lý tài khoản</h2>
                <p class="text-sm text-gray-500">Loaded from <code>GET /api/admin/users</code> via the backend API.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-users mr-1"></i> {{ count($users ?? []) }} người dùng
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Người dùng</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Quyền</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Trạng thái</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Số dư</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Ngày tạo</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-300">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                    @forelse($users ?? [] as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition">
                            <td class="px-4 py-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center overflow-hidden shrink-0">
                                        @if(!empty($user['avatar']))
                                            <img src="{{ $user['avatar'] }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-300">{{ strtoupper(substr($user['name'] ?? '?', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user['name'] ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">ID: {{ substr($user['id'] ?? '', 0, 12) }}...</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 align-middle text-sm text-gray-700 dark:text-gray-300">{{ $user['email'] ?? 'N/A' }}</td>
                            <td class="px-4 py-4 align-middle">
                                @if(($user['role'] ?? 'user') === 'admin')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 dark:bg-purple-900 px-2.5 py-1 text-xs font-semibold text-purple-700 dark:text-purple-300">
                                        <i class="fa-solid fa-shield-halved text-[10px]"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 dark:bg-blue-900 px-2.5 py-1 text-xs font-semibold text-blue-700 dark:text-blue-300">
                                        <i class="fa-solid fa-user text-[10px]"></i> User
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-middle">
                                @if(($user['status'] ?? 'active') === 'active')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-1 text-xs font-semibold text-red-700 dark:text-red-300">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Banned
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-middle text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format((float)($user['balance'] ?? 0), 0, ',', '.') }} đ
                            </td>
                            <td class="px-4 py-4 align-middle text-sm text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($user['createdAt'] ?? now())->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-4 align-middle text-right">
                                <div class="inline-flex items-center gap-1.5 flex-wrap justify-end">
                                    {{-- Toggle Status --}}
                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user['id'] ?? '') }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="{{ ($user['status'] ?? 'active') === 'active' ? 'Khóa tài khoản' : 'Mở khóa' }}"
                                            class="rounded-md border px-2.5 py-1.5 text-xs font-medium transition
                                            {{ ($user['status'] ?? 'active') === 'active'
                                                ? 'border-amber-300 text-amber-600 hover:bg-amber-50 dark:border-amber-600 dark:text-amber-400 dark:hover:bg-amber-900/30'
                                                : 'border-emerald-300 text-emerald-600 hover:bg-emerald-50 dark:border-emerald-600 dark:text-emerald-400 dark:hover:bg-emerald-900/30' }}">
                                            <i class="fa-solid {{ ($user['status'] ?? 'active') === 'active' ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Toggle Role --}}
                                    <form method="POST" action="{{ route('admin.users.update-role', $user['id'] ?? '') }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="role" value="{{ ($user['role'] ?? 'user') === 'admin' ? 'user' : 'admin' }}">
                                        <button type="submit" title="{{ ($user['role'] ?? 'user') === 'admin' ? 'Hạ quyền về User' : 'Nâng lên Admin' }}"
                                            class="rounded-md border border-purple-300 dark:border-purple-600 px-2.5 py-1.5 text-xs font-medium text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/30 transition">
                                            <i class="fa-solid {{ ($user['role'] ?? 'user') === 'admin' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Reset Password --}}
                                    <form method="POST" action="{{ route('admin.users.reset-password', $user['id'] ?? '') }}"
                                        onsubmit="return confirm('Reset mật khẩu của {{ $user['name'] ?? '' }} về 123456?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" title="Reset mật khẩu về 123456"
                                            class="rounded-md border border-blue-300 dark:border-blue-600 px-2.5 py-1.5 text-xs font-medium text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('admin.users.destroy', $user['id'] ?? '') }}"
                                        onsubmit="return confirm('Xóa vĩnh viễn tài khoản {{ $user['name'] ?? '' }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Xóa tài khoản"
                                            class="rounded-md border border-red-300 dark:border-red-600 px-2.5 py-1.5 text-xs font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-users-slash text-3xl text-gray-300 dark:text-gray-600"></i>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Không có người dùng nào từ backend API.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Legend --}}
        <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg shadow px-4 py-3">
            <span class="font-semibold">Chú thích:</span>
            <span><i class="fa-solid fa-lock text-amber-500 mr-1"></i> Khóa / <i class="fa-solid fa-lock-open text-emerald-500 mr-1"></i> Mở khóa</span>
            <span><i class="fa-solid fa-arrow-up text-purple-500 mr-1"></i> Nâng Admin / <i class="fa-solid fa-arrow-down text-purple-500 mr-1"></i> Hạ User</span>
            <span><i class="fa-solid fa-key text-blue-500 mr-1"></i> Reset mật khẩu (123456)</span>
            <span><i class="fa-solid fa-trash text-red-500 mr-1"></i> Xóa vĩnh viễn</span>
        </div>
    </div>
@endcomponent
