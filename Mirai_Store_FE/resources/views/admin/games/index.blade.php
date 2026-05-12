@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Games</h2>
                <p class="text-sm text-gray-500">Loaded from `GET /api/admin/games` via the backend API.</p>
            </div>
            <a href="{{ route('admin.games.create') }}"
                class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <i class="fa-solid fa-plus mr-2"></i>
                New Game
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($games ?? [] as $game)
                        <tr>
                            <td class="px-4 py-4 align-top">
                                <div class="font-medium text-gray-900">{{ $game['title'] ?? $game['name'] ?? 'Untitled game' }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $game['id'] ?? $game['Id'] ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500 line-clamp-2">{{ $game['description'] ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 align-top text-sm text-gray-700">{{ $game['categoryName'] ?? $game['category_name'] ?? 'No category' }}</td>
                            <td class="px-4 py-4 align-top text-sm text-gray-700">{{ number_format((float)($game['price'] ?? 0), 0, ',', '.') }} đ</td>
                            <td class="px-4 py-4 align-top">
                                @if(($game['isActive'] ?? $game['is_active'] ?? true))
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Hidden</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.games.edit', $game['id'] ?? $game['Id'] ?? '') }}"
                                        class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                    <form method="POST" action="{{ route('admin.games.toggle-status', $game['id'] ?? $game['Id'] ?? '') }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Toggle</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.games.destroy', $game['id'] ?? $game['Id'] ?? '') }}" onsubmit="return confirm('Delete this game?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No games returned from the backend API.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endcomponent
