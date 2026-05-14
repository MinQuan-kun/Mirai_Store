@props(['title', 'value', 'icon', 'color'])

<div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center gap-5 group hover:shadow-md transition-shadow">
    <div class="w-14 h-14 rounded-2xl {{ $color }} flex items-center justify-center text-white text-2xl shadow-lg {{ str_replace('bg-', 'shadow-', $color) }}/20 group-hover:scale-110 transition-transform">
        <i class="fa-solid {{ $icon }}"></i>
    </div>
    <div>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $title }}</p>
        <h4 class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $value }}</h4>
    </div>
</div>
