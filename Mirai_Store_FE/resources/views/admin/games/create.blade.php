@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div>
            <h2 class="text-2xl font-semibold">Create Game</h2>
            <p class="text-sm text-gray-500">This form posts to the backend MongoDB API through Laravel.</p>
        </div>

        <form method="POST" action="{{ route('admin.games.store') }}" enctype="multipart/form-data" class="space-y-6 rounded-xl bg-white p-6 shadow">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select category</option>
                        @foreach(($categories ?? []) as $category)
                            <option value="{{ $category['id'] ?? $category['Id'] ?? '' }}" @selected(old('category_id') === ($category['id'] ?? $category['Id'] ?? ''))>
                                {{ $category['name'] ?? $category['Name'] ?? 'Untitled category' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Image File</label>
                    <input type="file" name="image" accept="image/*" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Image URL</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional fallback URL">
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Create</button>
                <a href="{{ route('admin.games.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endcomponent
