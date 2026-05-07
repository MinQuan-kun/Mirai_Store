<header
    class="sticky top-0 z-50 w-full
         bg-white/90
         dark:bg-black
         backdrop-blur
         border-b
         border-miku-100
         dark:border-neutral-800
         shadow-sm
         transition-colors duration-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center h-16">

            <div class="flex-1 flex items-center">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('img/Circle_Logo.png') }}" alt="Mirai Store Logo"
                        class="h-10 w-auto object-contain hover:opacity-90 transition-opacity">
                    <span
                        class="font-bold text-xl text-gray-800 dark:text-white tracking-wide transition-colors duration-200">
                        MIRAI<span class="text-miku-500">STORE</span>
                    </span>
                </a>
            </div>

            <nav class="hidden md:flex flex-2 justify-center space-x-8">
                <a href="/"
                    class="text-gray-600 dark:text-gray-300 hover:text-miku-500 dark:hover:text-miku-400 font-medium transition duration-150">
                    Trang chủ
                </a>
                <a href="/shop"
                    class="text-gray-600 dark:text-gray-300 hover:text-miku-500 dark:hover:text-miku-400 font-medium transition duration-150">
                    Cửa hàng
                </a>
                <a href="{{ route('community.index') }}"
                    class="text-gray-600 dark:text-gray-300 hover:text-miku-500 dark:hover:text-miku-400 font-medium transition duration-150 {{ request()->routeIs('community.index') ? 'text-miku-500 dark:text-miku-400' : '' }}">
                    Cộng đồng
                </a>

                
                <a href="{{ route('gacha') }}"
                    class="text-gray-600 dark:text-gray-300 hover:text-miku-500 dark:hover:text-miku-400 font-medium transition duration-150p">

                    
                    <i class="fa-solid fa-dice text-xl mr-2 animate-bounce"></i>

                    <span>Gacha</span>
                </a>
            </nav>
            <div class="flex-1 flex items-center justify-end gap-4">

            <div class="flex-1 flex items-center justify-end gap-4">

                @if(!Session::has('auth_token'))
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}"
                            class="text-gray-600 dark:text-gray-300 hover:text-miku-500 font-medium transition-colors">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 bg-miku-500 text-white rounded-full hover:bg-miku-600 transition shadow-md shadow-miku-200 dark:shadow-none">
                            Đăng ký
                        </a>
                    </div>
                @endif

                @if(Session::has('auth_token'))
                    
                    <a href="{{ route('cart.index') }}"
                        class="relative text-gray-700 dark:text-gray-300 hover:text-miku-500 dark:hover:text-miku-400 transition">
                        <i class="fa-solid fa-shopping-cart fa-lg"></i>
                        
                    </a>
                @endif

                <button onclick="toggleTheme()"
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-6 h-6 hidden dark:block text-yellow-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>

                    <svg class="w-6 h-6 block dark:hidden text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                @if(Session::has('auth_token'))
                    <div class="relative ml-1" x-data="{ dropdownOpen: false }">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center gap-2 hover:opacity-80 transition focus:outline-none">
                            <span class="hidden md:block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ Session::get('user_name', 'Tài khoản') }}
                            </span>
                            <div
                                class="h-9 w-9 rounded-full overflow-hidden border border-miku-200 dark:border-gray-600 shadow-sm">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Session::get('user_name', 'U')) }}"
                                    class="h-full w-full object-cover">
                            </div>
                        </button>

                        <div x-show="dropdownOpen" @click.outside="dropdownOpen = false"
                            class="absolute right-0 mt-2 w-60 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50 overflow-hidden transform transition-all duration-200"
                            style="display: none;">
                            
                            <div
                                class="px-4 py-3 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border-b border-green-500/20">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Số dư ví</p>
                                <a href="{{ route('wallet.index') }}" class="flex items-center gap-2 group">
                                    <i class="fa-solid fa-wallet text-green-600 dark:text-green-400"></i>
                                    <span
                                        class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition">
                                        {{ number_format(Session::get('user_balance', 0), 0, ',', '.') }} đ
                                    </span>
                                </a>
                            </div>

                            <div
                                class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase">Tài khoản</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white truncate mt-0.5">
                                    {{ Session::get('user_email', 'user@example.com') }}
                                </p>
                            </div>

                             <div class="py-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-miku-50 dark:hover:bg-gray-700 hover:text-miku-600 dark:hover:text-white transition-colors">
                                     <svg class="w-5 h-5 text-gray-400 group-hover:text-miku-500" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                     </svg>
                                     Hồ sơ cá nhân
                                 </a>

                                 <a href="{{ route('orders.index') }}"
                                     class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-miku-50 dark:hover:bg-gray-700 hover:text-miku-600 dark:hover:text-white transition-colors">
                                     <svg class="w-5 h-5 text-gray-400 group-hover:text-miku-500" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                     </svg>
                                     Đơn hàng của tôi
                                 </a>

                                 <a href="#"
                                     class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-miku-50 dark:hover:bg-gray-700 hover:text-miku-600 dark:hover:text-white transition-colors">
                                     <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-500" fill="none"
                                         stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                             d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                         </path>
                                     </svg>
                                     Danh sách yêu thích
                                 </a>
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</header>
