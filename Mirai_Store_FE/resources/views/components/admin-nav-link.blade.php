@props(['href', 'active', 'icon'])

<a href="{{ $href }}" 
   class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 group
   {{ $active 
      ? 'bg-miku-500 text-white shadow-lg shadow-miku-500/30' 
      : 'text-gray-500 dark:text-gray-400 hover:bg-miku-50 dark:hover:bg-miku-900/10 hover:text-miku-600 dark:hover:text-miku-400' }}">
    
    <div class="w-8 h-8 flex items-center justify-center shrink-0">
        <i class="fa-solid {{ $icon }} text-lg {{ $active ? 'text-white' : 'group-hover:scale-110 transition-transform' }}"></i>
    </div>
    
    <span x-show="sidebarOpen" 
          x-transition:enter="transition ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-x-[-10px]"
          x-transition:enter-end="opacity-100 translate-x-0"
          class="text-sm font-bold tracking-wide whitespace-nowrap">
        {{ $slot }}
    </span>

    <template x-if="active && sidebarOpen">
        <div class="ml-auto w-1.5 h-1.5 rounded-full bg-white"></div>
    </template>
</a>
