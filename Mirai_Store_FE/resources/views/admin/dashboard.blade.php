@component('components.admin-layout')
    <div class="py-6">
        <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
        <p class="text-sm text-gray-500 mb-6">Admin pages are loaded from the backend API, not from local Laravel tables.</p>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <a href="{{ route('admin.games.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-lg font-medium">Games</h3>
                <p class="text-sm text-gray-500">{{ $stats['games'] ?? 0 }} items</p>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-lg font-medium">Categories</h3>
                <p class="text-sm text-gray-500">{{ $stats['categories'] ?? 0 }} items</p>
            </a>

            <a href="{{ route('admin.discounts.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-lg font-medium">Discounts</h3>
                <p class="text-sm text-gray-500">{{ $stats['discounts'] ?? 0 }} items</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="p-4 bg-white rounded-lg shadow hover:shadow-md transition">
                <h3 class="text-lg font-medium">Users</h3>
                <p class="text-sm text-gray-500">{{ $stats['users'] ?? 0 }} accounts</p>
            </a>
        </div>
    </div>
@endcomponent
