@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Categories</h2>
                <p class="text-sm text-gray-500">Loaded from `GET /api/admin/categories` via the backend API.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <i class="fa-solid fa-plus mr-2"></i>
                New Category
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Description</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($categories ?? [] as $cat)
                        <tr>
                            <td class="px-4 py-4 align-top">
                                <div class="font-medium text-gray-900">{{ $cat['name'] ?? $cat['Name'] ?? 'Untitled' }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $cat['id'] ?? $cat['Id'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4 align-top text-sm text-gray-700">{{ \Illuminate\Support\Str::limit($cat['description'] ?? $cat['Description'] ?? '', 120) }}</td>
                            <td class="px-4 py-4 align-top text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.categories.edit', $cat['id'] ?? $cat['Id'] ?? '') }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat['id'] ?? $cat['Id'] ?? '') }}" onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">No categories returned from the backend API.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endcomponent
