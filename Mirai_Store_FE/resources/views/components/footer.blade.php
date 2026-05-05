<footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

            
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="h-12 w-auto group-hover:scale-105 transition-transform duration-300 -mt-2">
                        <img src="{{ asset('img/Logo.png') }}" alt="Mirai Logo"
                            class="h-full w-auto object-contain drop-shadow-sm">
                    </div>
                </a>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Đây chỉ là sản phẩm cho mục đích học tập, không phục vụ thương mại.
                </p>
                <div class="flex space-x-4">
                    <a href="#"
                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-miku-500 hover:text-white dark:hover:bg-miku-500 dark:hover:text-white transition-all">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#"
                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-miku-500 hover:text-white dark:hover:bg-miku-500 dark:hover:text-white transition-all">
                        <i class="fa-brands fa-discord"></i>
                    </a>
                    <a href="#"
                        class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:bg-miku-500 hover:text-white dark:hover:bg-miku-500 dark:hover:text-white transition-all">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>
            </div>

            
            <div>
                <h3 class="text-gray-900 dark:text-white font-bold uppercase tracking-wider mb-4 text-sm">Khám Phá</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('shop.index') }}"
                            class="text-gray-500 dark:text-gray-400 hover:text-miku-600 dark:hover:text-miku-400 transition-colors text-sm">
                            Cửa hàng Game
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.index') }}"
                            class="text-gray-500 dark:text-gray-400 hover:text-miku-600 dark:hover:text-miku-400 transition-colors text-sm">
                            Top Bán Chạy
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('shop.index', ['sort' => 'newest']) }}"
                            class="text-gray-500 dark:text-gray-400 hover:text-miku-600 dark:hover:text-miku-400 transition-colors text-sm">
                            Game Mới Phát Hành
                        </a>
                    </li>
                </ul>
            </div>

            
            <div>
                <h3 class="text-gray-900 dark:text-white font-bold uppercase tracking-wider mb-4 text-sm">Hỗ Trợ</h3>
                <ul class="space-y-3">
                    <ul class="space-y-3">
                        <li>
                            <a href="#" @click.prevent="activeModal = 'help'"
                                class="text-gray-500 dark:text-gray-400 hover:text-miku-600 dark:hover:text-miku-400 transition-colors text-sm">
                                Trung Tâm Trợ Giúp
                            </a>
                        </li>
                        <li>
                            <a href="#" @click.prevent="activeModal = 'privacy'"
                                class="text-gray-500 dark:text-gray-400 hover:text-miku-600 dark:hover:text-miku-400 transition-colors text-sm">
                                Chính Sách Bảo Mật
                            </a>
                        </li>
                        <li>
                            <a href="#" @click.prevent="activeModal = 'terms'"
                                class="text-gray-500 dark:text-gray-400 hover:text-miku-600 dark:hover:text-miku-400 transition-colors text-sm">
                                Điều Khoản Dịch Vụ
                            </a>
                        </li>
                    </ul>
            </div>

            
            <div>
                <h3 class="text-gray-900 dark:text-white font-bold uppercase tracking-wider mb-4 text-sm">Nhận Tin Mới
                </h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Đừng bỏ lỡ các ưu đãi game miễn phí
                    hàng tuần.</p>
                <form class="space-y-2">
                    <div class="relative">
                        <input type="email" placeholder="Email của bạn..."
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-miku-500 focus:border-miku-500 block p-2.5">
                    </div>
                    <button type="button"
                        class="w-full text-white bg-miku-500 hover:bg-miku-600 focus:ring-4 focus:ring-miku-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-md shadow-miku-500/30">
                        Đăng Ký Ngay
                    </button>
                </form>
            </div>
        </div>

        
        <div
            class="border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 dark:text-gray-500 text-sm text-center md:text-left">
                &copy; {{ date('Y') }} <span class="text-miku-600 dark:text-miku-400 font-bold">Mirai Store</span>.
                All rights reserved.
            </p>

            <div class="flex items-center gap-6">
                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Đối tác thanh toán</span>
                <div class="flex gap-2 opacity-60 grayscale hover:grayscale-0 transition-all">
                    <i class="fa-brands fa-cc-visa text-2xl text-blue-600"></i>
                    <i class="fa-brands fa-cc-mastercard text-2xl text-red-500"></i>
                    <i class="fa-brands fa-cc-paypal text-2xl text-blue-800"></i>
                </div>
            </div>
        </div>
    </div>
</footer>
