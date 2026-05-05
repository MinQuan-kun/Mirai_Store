<x-shop-layout>
    <div class="min-h-screen flex items-stretch text-white overflow-hidden transition-colors duration-300" style="min-height: 100vh;">
        
        <!-- CỘT TRÁI (Banner - Giống Login nhưng đổi text) -->
        <div class="lg:flex w-1/2 hidden bg-gray-500 bg-no-repeat relative items-center" 
             style="background-image: url('https://res.cloudinary.com/davfujasj/image/upload/v1764930652/miku_lfnyju.jpg'); background-size: cover; background-position: center center;">
            
            <div class="absolute inset-0 bg-black opacity-40"></div> 
            
            <div class="w-full px-24 z-10 relative"> 
                <h1 class="text-5xl font-bold text-left tracking-wide text-white">Mirai Store</h1>
                <p class="text-3xl my-4 text-white">Tham gia ngay,<br>trải nghiệm bất tận.</p>
                <p class="text-sm my-4 text-miku-300 font-semibold">Cộng đồng game hàng đầu</p>
            </div>
        </div>

        <!-- CỘT PHẢI (Form Đăng Ký) -->
        <div class="lg:w-1/2 w-full flex items-center justify-center text-center md:px-16 px-0 z-0 bg-white dark:bg-gray-900 relative transition-colors duration-300">
            
            <!-- Banner ẩn cho Mobile -->
            <div class="absolute lg:hidden z-10 inset-0 bg-gray-500 bg-no-repeat items-center" 
                 style="background-image: url('https://images4.alphacoders.com/936/936378.jpg'); background-size: cover; background-position: center center;">
                <div class="absolute inset-0 bg-black opacity-80"></div>
            </div>

            <!-- Container Form -->
            <div class="w-full py-6 z-20 px-6 md:px-12 bg-white/95 dark:bg-gray-900/95 h-full flex flex-col justify-center transition-colors duration-300 overflow-y-auto">
                
                <div class="py-6 space-x-2 flex justify-center items-center">
                    <span class="w-10 h-10 rounded-full bg-miku-500 shadow-lg shadow-miku-200/50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    </span>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white transition-colors">Đăng Ký</h2>
                </div>

                <p class="text-gray-500 dark:text-gray-400 mb-6 transition-colors">Tạo tài khoản mới tại Mirai Store</p>

                <form method="POST" action="{{ route('register') }}" class="sm:w-2/3 w-full px-4 lg:px-0 mx-auto">
                    @csrf

                    <!-- Name -->
                    <div class="pb-2 text-left">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 ml-1 transition-colors">Tên tài khoản</label>
                        <div class="relative">
                            <input type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nhập tên tài khoản"
                                class="block w-full p-3 pl-4 text-lg rounded-lg 
                                       bg-gray-50 dark:bg-gray-800 
                                       border border-gray-300 dark:border-gray-700 
                                       placeholder-gray-400 dark:placeholder-gray-500 
                                       text-gray-900 dark:text-white 
                                       focus:outline-none focus:border-miku-500 focus:ring-2 focus:ring-miku-200 dark:focus:ring-miku-500/30 
                                       transition-colors duration-200">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-left" />
                    </div>

                    <!-- Email -->
                    <div class="pb-2 pt-4 text-left">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 ml-1 transition-colors">Email</label>
                        <div class="relative">
                            <input type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com"
                                class="block w-full p-3 pl-4 text-lg rounded-lg 
                                       bg-gray-50 dark:bg-gray-800 
                                       border border-gray-300 dark:border-gray-700 
                                       placeholder-gray-400 dark:placeholder-gray-500 
                                       text-gray-900 dark:text-white 
                                       focus:outline-none focus:border-miku-500 focus:ring-2 focus:ring-miku-200 dark:focus:ring-miku-500/30 
                                       transition-colors duration-200">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-left" />
                    </div>

                    <!-- Password -->
                    <div class="pb-2 pt-4 text-left">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 ml-1 transition-colors">Mật khẩu</label>
                        <div class="relative">
                            <input type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                                class="block w-full p-3 pl-4 text-lg rounded-lg 
                                       bg-gray-50 dark:bg-gray-800 
                                       border border-gray-300 dark:border-gray-700 
                                       placeholder-gray-400 dark:placeholder-gray-500 
                                       text-gray-900 dark:text-white 
                                       focus:outline-none focus:border-miku-500 focus:ring-2 focus:ring-miku-200 dark:focus:ring-miku-500/30 
                                       transition-colors duration-200">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-left" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="pb-2 pt-4 text-left">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2 ml-1 transition-colors">Xác nhận mật khẩu</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                                class="block w-full p-3 pl-4 text-lg rounded-lg 
                                       bg-gray-50 dark:bg-gray-800 
                                       border border-gray-300 dark:border-gray-700 
                                       placeholder-gray-400 dark:placeholder-gray-500 
                                       text-gray-900 dark:text-white 
                                       focus:outline-none focus:border-miku-500 focus:ring-2 focus:ring-miku-200 dark:focus:ring-miku-500/30 
                                       transition-colors duration-200">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-left" />
                    </div>

                    <!-- Submit Button -->
                    <div class="px-4 pb-2 pt-6">
                        <button type="submit" class="uppercase block w-full p-3 text-lg rounded-full bg-miku-500 hover:bg-miku-600 focus:outline-none font-bold text-white shadow-lg shadow-miku-200/50 dark:shadow-none transition transform hover:-translate-y-1">
                            Đăng Ký Tài Khoản
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-6 text-gray-600 dark:text-gray-400 transition-colors">
                        Đã có tài khoản? <a href="{{ route('login') }}" class="text-miku-600 dark:text-miku-400 hover:underline font-bold transition-colors">Đăng nhập ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-shop-layout>