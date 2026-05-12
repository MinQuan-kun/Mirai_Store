@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div>
            <h2 class="text-2xl font-semibold">Edit Game</h2>
            <p class="text-sm text-gray-500">Updating this form sends a `PUT` request to the backend MongoDB API.</p>
            @if(empty($game))
                <div class="mt-2 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    Lỗi: Dữ liệu game trống. Vui lòng kiểm tra logs.
                </div>
            @endif
        </div>

        @if(!empty($game))
        <form method="POST" action="{{ route('admin.games.update', $game['id'] ?? $game['Id'] ?? '') }}" enctype="multipart/form-data" class="space-y-6 rounded-xl bg-white p-6 shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" value="{{ old('title', $game['title'] ?? $game['Title'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $game['description'] ?? $game['Description'] ?? '') }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $game['price'] ?? $game['Price'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Category</label>
                    <select name="category_id" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">Select category</option>
                        @foreach(($categories ?? []) as $category)
                            @php
                                $categoryId = $category['id'] ?? $category['Id'] ?? '';
                                $selectedCategoryId = old('category_id', $game['categoryId'] ?? $game['CategoryId'] ?? $game['categoryIds'][0] ?? '');
                            @endphp
                            <option value="{{ $categoryId }}" @selected((string)$selectedCategoryId === (string)$categoryId)>
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
                    <input type="url" name="image_url" value="{{ old('image_url', $game['imageUrl'] ?? $game['ImageUrl'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional fallback URL">
                </div>
            </div>

            @if(!empty($game['imageUrl'] ?? $game['ImageUrl'] ?? null))
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-700">Current Image</p>
                    <img src="{{ $game['imageUrl'] ?? $game['ImageUrl'] }}" alt="Game image" class="h-40 rounded-lg object-cover shadow">
                </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save Changes</button>
                <a href="{{ route('admin.games.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
        @else
        <div class="rounded-lg bg-yellow-50 p-6 text-sm text-yellow-700">
            <p>Không tìm thấy dữ liệu game. Vui lòng quay lại <a href="{{ route('admin.games.index') }}" class="font-semibold underline">danh sách game</a>.</p>
        </div>
        @endif
    </div>
@endcomponent
