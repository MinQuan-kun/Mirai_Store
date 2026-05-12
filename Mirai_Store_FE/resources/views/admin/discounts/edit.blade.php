@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div>
            <h2 class="text-2xl font-semibold">Edit Discount</h2>
            <p class="text-sm text-gray-500">Updating this form sends a `PUT` request to the backend API.</p>
        </div>

        @if(!empty($discount))
        <form method="POST" action="{{ route('admin.discounts.update', $discount['id'] ?? $discount['Id'] ?? '') }}" class="space-y-6 rounded-xl bg-white p-6 shadow">
            @csrf
            @method('PUT')

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" name="code" value="{{ old('code', $discount['code'] ?? $discount['Code'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm uppercase focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @php $selectedType = old('type', $discount['type'] ?? $discount['Type'] ?? 'fixed'); @endphp
                        <option value="fixed" @selected($selectedType === 'fixed')>Fixed</option>
                        <option value="percentage" @selected($selectedType === 'percentage')>Percentage</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Value</label>
                    <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $discount['value'] ?? $discount['Value'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Expires At</label>
                    @php
                        $expiresAt = old('expires_at', $discount['expiresAt'] ?? $discount['ExpiresAt'] ?? '');
                        $expiresValue = $expiresAt ? \Illuminate\Support\Carbon::parse($expiresAt)->format('Y-m-d\TH:i') : '';
                    @endphp
                    <input type="datetime-local" name="expires_at" value="{{ $expiresValue }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" value="{{ old('usage_limit', $discount['usageLimit'] ?? $discount['UsageLimit'] ?? '') }}" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Optional limit">
                </div>

                <div class="flex items-end">
                    <p class="text-sm text-gray-500">Used count: {{ $discount['usedCount'] ?? $discount['UsedCount'] ?? 0 }}</p>
                </div>
            </div>

            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active', $discount['isActive'] ?? $discount['IsActive'] ?? true))>
                Active
            </label>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Save Changes</button>
                <a href="{{ route('admin.discounts.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
            </div>
        </form>
        @else
        <div class="rounded-lg bg-yellow-50 p-6 text-sm text-yellow-700">
            <p>Không tìm thấy dữ liệu mã giảm giá. Vui lòng quay lại <a href="{{ route('admin.discounts.index') }}" class="font-semibold underline">danh sách</a>.</p>
        </div>
        @endif
    </div>
@endcomponent
