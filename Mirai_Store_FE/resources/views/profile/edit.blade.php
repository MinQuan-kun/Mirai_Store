<x-shop-layout>

    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 transition-colors duration-300"
        x-data="{ activeTab: 'info' }">

        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">

            
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-700 sticky top-24 transition-colors duration-300">

                    
                    <div class="relative w-32 h-32 mx-auto mb-4 group">
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-miku-500 shadow-lg relative bg-gray-100 dark:bg-gray-700">
                            
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=39C5BB&color=fff&size=128"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @endif

                            
                            <form id="avatar-upload-form" method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="absolute inset-0 z-20">
                                @csrf
                                @method('patch')
                                
                                <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer">
                                    <div class="flex flex-col items-center text-white">
                                        <i class="fas fa-camera text-2xl mb-1"></i>
                                        <span class="text-xs font-bold">Đổi ảnh</span>
                                    </div>
                                    <input type="file" name="avatar" class="hidden" accept="image/*" 
                                           onchange="document.getElementById('avatar-upload-form').submit(); document.getElementById('avatar-loading').classList.remove('hidden');">
                                </label>
                            </form>
                            
                            
                            <div id="avatar-loading" class="absolute inset-0 bg-black/60 flex items-center justify-center z-30 hidden transition-all">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-spinner fa-spin text-white text-2xl mb-1"></i>
                                    <span class="text-white text-xs font-bold">Đang tải...</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    @if (session('status') === 'avatar-updated')
                        <div class="mb-4 text-center animate-bounce">
                            <span class="text-green-600 dark:text-green-400 text-sm font-bold bg-green-100 dark:bg-green-900/30 py-1 px-3 rounded-full border border-green-200 dark:border-green-800">
                                <i class="fas fa-check mr-1"></i> Đổi ảnh thành công!
                            </span>
                        </div>
                    @endif
                    
                    @error('avatar')
                        <div class="mb-4 text-center">
                            <span class="text-red-500 text-sm font-medium">{{ $message }}</span>
                        </div>
                    @enderror

                    <h2 class="text-xl font-bold text-gray-800 dark:text-white mb-1 text-center">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 truncate text-center">{{ $user->email }}</p>

                    
                    <div class="space-y-2">
                        @php
                            $tabClass = 'w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 transition font-medium';
                            $activeClass = 'bg-miku-500 text-white shadow-lg shadow-miku-500/30';
                            $inactiveClass = 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-miku-600 dark:hover:text-miku-400';
                        @endphp

                        <button @click="activeTab = 'info'"
                            :class="activeTab === 'info' ? '{{ $activeClass }}' : '{{ $inactiveClass }}'"
                            class="{{ $tabClass }}">
                            <i class="fas fa-user-circle w-6 text-center"></i> Thông tin cá nhân
                        </button>

                        <button @click="activeTab = 'history'"
                            :class="activeTab === 'history' ? '{{ $activeClass }}' : '{{ $inactiveClass }}'"
                            class="{{ $tabClass }}">
                            <i class="fas fa-gamepad w-6 text-center"></i> Game đã mua
                        </button>

                        <button @click="activeTab = 'billing'"
                            :class="activeTab === 'billing' ? '{{ $activeClass }}' : '{{ $inactiveClass }}'"
                            class="{{ $tabClass }}">
                            <i class="fas fa-receipt w-6 text-center"></i> Lịch sử giao dịch
                        </button>

                        <button @click="activeTab = 'donate'"
                            :class="activeTab === 'donate' ? '{{ $activeClass }}' : '{{ $inactiveClass }}'"
                            class="{{ $tabClass }}">
                            <i class="fas fa-donate w-6 text-center"></i> Donate
                        </button>

                        
                        <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-700">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                    class="w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 transition font-bold text-white bg-red-500 hover:bg-red-600 shadow-lg shadow-red-500/30 transform active:scale-95">
                                    <i class="fas fa-sign-out-alt w-6 text-center"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-9">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 md:p-8 shadow-lg border border-gray-200 dark:border-gray-700 min-h-[600px] transition-colors duration-300 relative overflow-hidden">

                    
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-miku-400/10 rounded-full blur-3xl pointer-events-none"></div>

                    
                    <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">

                        <div x-data="{ isEditing: {{ $errors->any() ? 'true' : 'false' }} }">
                            
                            
                            <div class="flex justify-between items-center mb-8 border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Hồ sơ người dùng</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý thông tin và bảo mật tài khoản</p>
                                </div>
                                
                                
                                <button type="button" x-show="!isEditing" @click="isEditing = true"
                                    class="text-sm bg-miku-50 dark:bg-miku-900/30 text-miku-600 dark:text-miku-400 px-4 py-2 rounded-lg hover:bg-miku-500 hover:text-white transition border border-miku-200 dark:border-miku-800 font-bold shadow-sm flex items-center h-full">
                                    <i class="fas fa-pen mr-2"></i> Chỉnh sửa
                                </button>
                            </div>

                            
                            @if (session('status') === 'profile-updated')
                                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-600 dark:text-green-400 flex items-center gap-3 animate-pulse">
                                    <i class="fas fa-check-circle text-xl"></i>
                                    <span class="font-bold">Đã cập nhật hồ sơ thành công!</span>
                                </div>
                            @endif

                            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                                @csrf @method('patch')

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    <div class="space-y-2">
                                        <label class="text-gray-700 dark:text-gray-300 text-sm font-semibold"> Username </label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3.5 text-gray-400"><i class="fas fa-user"></i></span>
                                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                                :disabled="!isEditing"
                                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl py-3 pl-10 pr-4 focus:ring-2 focus:ring-miku-500 focus:border-miku-500 outline-none text-gray-900 dark:text-white transition-all disabled:bg-gray-100 dark:disabled:bg-gray-800 disabled:text-gray-500">
                                        </div>
                                        @error('name')
                                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    
                                    <div class="space-y-2">
                                        <label class="text-gray-700 dark:text-gray-300 text-sm font-semibold">Địa chỉ Email</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-3.5 text-gray-400"><i class="fas fa-envelope"></i></span>
                                            <input type="email" value="{{ $user->email }}" disabled
                                                class="w-full bg-gray-100 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-xl py-3 pl-10 pr-4 text-gray-500 dark:text-gray-400 font-mono cursor-not-allowed">
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1 pl-1">Email không thể thay đổi vì lý do bảo mật.</p>
                                    </div>
                                </div>

                                
                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border border-gray-200 dark:border-gray-700 mt-6"
                                    x-show="isEditing" x-transition>
                                    <h4 class="text-miku-600 dark:text-miku-400 text-sm mb-5 font-bold uppercase tracking-wider flex items-center">
                                        <i class="fas fa-lock mr-2"></i> Bảo mật (Tùy chọn)
                                    </h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-gray-700 dark:text-gray-300 text-sm font-medium">Mật khẩu mới</label>
                                            <input type="password" name="password" placeholder="Để trống nếu không đổi..."
                                                class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-miku-500 focus:border-miku-500 outline-none text-gray-900 dark:text-white">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-gray-700 dark:text-gray-300 text-sm font-medium">Xác nhận mật khẩu</label>
                                            <input type="password" name="password_confirmation" placeholder="Nhập lại mật khẩu mới..."
                                                class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-miku-500 focus:border-miku-500 outline-none text-gray-900 dark:text-white">
                                        </div>
                                    </div>
                                    @if ($errors->updatePassword->any())
                                        <div class="mt-3 text-red-500 text-sm font-medium p-2 bg-red-50 dark:bg-red-900/20 rounded">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            {{ $errors->updatePassword->first() }}
                                        </div>
                                    @endif
                                </div>

                                
                                <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700"
                                    x-show="isEditing" x-transition>
                                    <button type="button" @click="isEditing = false"
                                        class="px-6 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition font-bold text-gray-700 dark:text-gray-200">
                                        Hủy bỏ
                                    </button>
                                    <button type="submit"
                                        class="px-8 py-2.5 rounded-xl bg-miku-600 hover:bg-miku-500 transition font-bold text-white shadow-lg shadow-miku-500/30 transform active:scale-95 flex items-center gap-2">
                                        <i class="fas fa-save"></i> Lưu thay đổi
                                    </button>
                                </div>
                            </form>

                            
                            <div class="mt-16 pt-8 border-t border-red-200 dark:border-red-900/30">
                                <h4 class="text-red-600 dark:text-red-400 font-bold text-lg mb-2">Vùng nguy hiểm</h4>
                                <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 rounded-xl p-5 flex flex-col md:flex-row items-center justify-between gap-4">
                                    <div>
                                        <p class="text-gray-700 dark:text-gray-300 font-medium">Xóa tài khoản vĩnh viễn</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Một khi bạn xóa tài khoản, tất cả dữ liệu sẽ không thể khôi phục.</p>
                                    </div>
                                    <button @click="$dispatch('open-modal', 'confirm-user-deletion')"
                                        class="whitespace-nowrap px-5 py-2.5 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition shadow-md shadow-red-500/20">
                                        Xóa tài khoản
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                   
<div x-show="activeTab === 'history'" x-cloak>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Thư viện Game</h3>
    @if($purchasedGames->isEmpty())
        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
            <i class="fas fa-ghost text-6xl mb-4 opacity-50"></i>
            <p>Chưa có game nào trong thư viện.</p>
            <a href="/" class="text-miku-600 hover:underline mt-2 inline-block">Dạo cửa hàng ngay</a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($purchasedGames as $game)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:border-miku-500 transition group">
                    <div class="aspect-video relative overflow-hidden">
                        <img src="{{ Str::startsWith($game->image, 'http') ? $game->image : asset('storage/' . $game->image) }}" 
                             alt="{{ $game->name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">{{ $game->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $game->publisher }}</p>
                        <a href="{{ $game->download_link }}" target="_blank" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition font-medium">
                            <i class="fa-solid fa-download mr-2"></i>Tải Game
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>


<div x-show="activeTab === 'billing'" x-cloak>
    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Lịch sử giao dịch</h3>
    @if($transactions->isEmpty())
        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
            <i class="fas fa-file-invoice-dollar text-6xl mb-4 opacity-50"></i>
            <p>Chưa có giao dịch nào được ghi nhận.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3">
                            @if($transaction->type === 'deposit')
                                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-arrow-down text-green-600 dark:text-green-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">Nạp tiền</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->payment_method ?? 'N/A' }}</p>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                                    <i class="fa-solid fa-shopping-cart text-red-600 dark:text-red-400"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white">Mua game</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Order #{{ substr($transaction->order_id ?? 'N/A', 0, 8) }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="font-bold {{ $transaction->type === 'deposit' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $transaction->type === 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 0, ',', '.') }} VNĐ
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $transaction->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($transaction->status)
                        <div class="mt-2">
                            <span class="text-xs px-2 py-1 rounded-full 
                                {{ $transaction->status === 'completed' ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-400' : '' }}
                                {{ $transaction->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400' : '' }}
                                {{ $transaction->status === 'failed' ? 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400' : '' }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>


                    
                    <div x-show="activeTab === 'donate'" x-cloak>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Donate</h3>
                        <div class="text-center py-20 text-gray-500 dark:text-gray-400">
                            <i class="fas fa-donate text-6xl mb-4 opacity-50"></i>
                            <p>Tính năng đang phát triển</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
            class="p-6 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
            @csrf
            @method('delete')

            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-500 text-xl"></i>
            </div>

            <h2 class="text-xl font-bold text-center mb-2">{{ __('Xác nhận xóa tài khoản?') }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">
                {{ __('Hành động này không thể hoàn tác. Vui lòng nhập mật khẩu để xác nhận bạn chính là chủ sở hữu.') }}
            </p>

            <div class="mt-6">
                <div class="relative">
                    <input id="password" name="password" type="password" placeholder="Nhập mật khẩu của bạn"
                        class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all" />
                </div>
                @if ($errors->userDeletion->has('password'))
                    <span class="text-red-500 text-sm mt-2 block font-medium">{{ $errors->userDeletion->first('password') }}</span>
                @endif
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700 pt-4">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    {{ __('Hủy bỏ') }}
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-bold shadow-lg shadow-red-500/30 hover:bg-red-500 transition">
                    {{ __('Xóa vĩnh viễn') }}
                </button>
            </div>
        </form>
    </x-modal>
</x-shop-layout>