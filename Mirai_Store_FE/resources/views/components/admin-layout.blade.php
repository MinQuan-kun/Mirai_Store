@props(['slot'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: true }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .sidebar-transition { transition: all 0.3s ease; }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="sidebar-transition bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col z-40">
            
            <!-- Logo area -->
            <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-gray-700 overflow-hidden">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-miku-500 rounded-lg flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-black text-xl tracking-tight whitespace-nowrap">
                        ADMIN <span class="text-miku-500">PANEL</span>
                    </span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
                <x-admin-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" icon="fa-chart-pie">
                    Dashboard
                </x-admin-nav-link>

                <x-admin-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" icon="fa-users">
                    Users
                </x-admin-nav-link>

                <x-admin-nav-link href="{{ route('admin.games.index') }}" :active="request()->routeIs('admin.games.*')" icon="fa-gamepad">
                    Games
                </x-admin-nav-link>

                <x-admin-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" icon="fa-layer-group">
                    Categories
                </x-admin-nav-link>

                <x-admin-nav-link href="{{ route('admin.discounts.index') }}" :active="request()->routeIs('admin.discounts.*')" icon="fa-ticket">
                    Discounts
                </x-admin-nav-link>
            </nav>

            <!-- Bottom Action -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('home') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition group">
                    <i class="fa-solid fa-store w-5 text-center group-hover:text-miku-500"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Back to Shop</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fa-solid fa-bars text-gray-500"></i>
                    </button>
                    <h2 class="text-lg font-semibold hidden md:block">
                        @yield('header_title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Dark Mode -->
                    <button @click="darkMode = !darkMode"
                        class="w-10 h-10 flex items-center justify-center rounded-xl text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 transition border border-gray-100 dark:border-gray-700">
                        <i x-show="!darkMode" class="fa-solid fa-moon"></i>
                        <i x-show="darkMode" class="fa-solid fa-sun text-yellow-400"></i>
                    </button>

                    <!-- Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center gap-3 p-1 pl-3 pr-2 rounded-xl bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition border border-gray-200 dark:border-gray-600">
                            <span class="text-sm font-bold truncate max-w-[100px]">
                                {{ optional(Auth::user())->name ?? session('user_name', 'Tài khoản') }}
                            </span>
                            <div class="w-8 h-8 rounded-lg bg-miku-500 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr(optional(Auth::user())->name ?? 'A', 0, 1)) }}
                            </div>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 py-2 z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm hover:bg-miku-50 dark:hover:bg-miku-900/20 transition">
                                <i class="fa-solid fa-user-circle text-gray-400"></i> Hồ sơ
                            </a>
                            <div class="h-px bg-gray-100 dark:bg-gray-700 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-3 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 transition">
                                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                         class="mb-6 flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-lg">
                        <div class="flex items-center">
                            <i class="fa-solid fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false"><i class="fa-solid fa-times text-green-400"></i></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg flex items-center">
                        <i class="fa-solid fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>