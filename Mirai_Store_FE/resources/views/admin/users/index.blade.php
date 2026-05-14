@section('header_title', 'Quản lý Tài khoản')

<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">Danh sách người dùng</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Quản lý tài khoản khách hàng và phân quyền hệ thống.</p>
            </div>
            <div class="flex items-center gap-3 px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <i class="fa-solid fa-users text-miku-500"></i>
                <span class="text-sm font-bold">{{ count($users ?? []) }} thành viên</span>
            </div>
        </div>

        @if(isset($error))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm">
                <i class="fa-solid fa-circle-exclamation text-lg"></i>
                <span class="text-sm font-medium">{{ $error }}</span>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                            <th class="px-6 py-4">Người dùng</th>
                            <th class="px-6 py-4">Email / ID</th>
                            <th class="px-6 py-4">Phân quyền</th>
                            <th class="px-6 py-4">Trạng thái</th>
                            <th class="px-6 py-4">Số dư</th>
                            <th class="px-6 py-4 text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        @forelse($users ?? [] as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-miku-50 dark:bg-miku-900/30 flex items-center justify-center overflow-hidden border border-miku-100 dark:border-miku-800 shrink-0">
                                            @if(!empty($user['avatar']))
                                                <img src="{{ $user['avatar'] }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-sm font-black text-miku-600 dark:text-miku-400">{{ strtoupper(substr($user['name'] ?? '?', 0, 1)) }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $user['name'] ?? 'N/A' }}</div>
                                            <div class="text-[10px] text-gray-400 uppercase font-mono">Tham gia: {{ \Carbon\Carbon::parse($user['createdAt'] ?? now())->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user['email'] ?? 'N/A' }}</div>
                                    <div class="text-[9px] text-gray-400 font-mono mt-0.5">{{ $user['id'] ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if(($user['role'] ?? 'user') === 'admin')
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-purple-100 dark:bg-purple-900/40 px-2 py-1 text-[10px] font-black uppercase text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                            <i class="fa-solid fa-crown"></i> Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-lg bg-blue-100 dark:bg-blue-900/40 px-2 py-1 text-[10px] font-black uppercase text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                            <i class="fa-solid fa-user-shield"></i> User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if(($user['status'] ?? 'active') === 'active')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-2.5 py-1 text-[10px] font-black uppercase text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hoạt động
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-black uppercase text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Đã khóa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-black text-gray-900 dark:text-white">
                                    {{ number_format((float)($user['balance'] ?? 0), 0, ',', '.') }}đ
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        {{-- Toggle Status --}}
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user['id'] ?? '') }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="{{ ($user['status'] ?? 'active') === 'active' ? 'Khóa tài khoản' : 'Mở khóa' }}"
                                                class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-orange-500 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                                <i class="fa-solid {{ ($user['status'] ?? 'active') === 'active' ? 'fa-user-lock' : 'fa-user-check' }}"></i>
                                            </button>
                                        </form>
    
                                        {{-- Reset Password --}}
                                        <form method="POST" action="{{ route('admin.users.reset-password', $user['id'] ?? '') }}"
                                            onsubmit="return confirm('Reset mật khẩu của {{ $user['name'] ?? '' }} về 123456?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Reset mật khẩu"
                                                class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-blue-500 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                        </form>
    
                                        {{-- Delete --}}
                                        <form method="POST" action="{{ route('admin.users.destroy', $user['id'] ?? '') }}"
                                            onsubmit="return confirm('Xóa vĩnh viễn tài khoản {{ $user['name'] ?? '' }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Xóa tài khoản"
                                                class="p-2 rounded-lg bg-gray-50 dark:bg-gray-700 text-red-400 hover:text-red-600 transition shadow-sm border border-gray-100 dark:border-gray-600">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fa-solid fa-user-slash text-5xl text-gray-200 dark:text-gray-700 mb-4"></i>
                                        <p class="text-gray-400 italic">Không tìm thấy người dùng nào.</p>
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
