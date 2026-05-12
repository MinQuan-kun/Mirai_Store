@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div>
            <h2 class="text-2xl font-semibold">Edit Category</h2>
            <p class="text-sm text-gray-500">Updating this form sends a `PUT` request to the backend API.</p>
        </div>

        @if(!empty($category))
        <form method="POST" action="{{ route('admin.categories.update', $category['id'] ?? $category['Id'] ?? '') }}" class="space-y-6 rounded-xl bg-white p-6 shadow">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $category['name'] ?? $category['Name'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $category['description'] ?? $category['Description'] ?? '') }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save Changes</button>
                <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
        @else
        <div class="rounded-lg bg-yellow-50 p-6 text-sm text-yellow-700">
            <p>Không tìm thấy dữ liệu danh mục. Vui lòng quay lại <a href="{{ route('admin.categories.index') }}" class="font-semibold underline">danh sách</a>.</p>
        </div>
        @endif
    </div>
@endcomponent
