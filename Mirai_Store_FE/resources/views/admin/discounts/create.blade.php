@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div>
            <h2 class="text-2xl font-semibold">Create Discount</h2>
            <p class="text-sm text-gray-500">This form posts JSON to the backend API through Laravel.</p>
        </div>

        <form method="POST" action="{{ route('admin.discounts.store') }}" class="space-y-6 rounded-xl bg-white p-6 shadow">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm uppercase focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="fixed" @selected(old('type') === 'fixed')>Fixed</option>
                        <option value="percentage" @selected(old('type') === 'percentage')>Percentage</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Value</label>
                    <input type="number" step="0.01" min="0" name="value" value="{{ old('value') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Expires At</label>
                    <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional limit">
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active', true))>
                Active
            </label>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Create</button>
                <a href="{{ route('admin.discounts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
    </div>
@endcomponent
