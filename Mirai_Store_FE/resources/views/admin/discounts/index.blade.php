@component('components.admin-layout')
    <div class="py-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold">Discounts</h2>
                <p class="text-sm text-gray-500">Loaded from `GET /api/admin/discounts` via the backend API.</p>
            </div>
            <a href="{{ route('admin.discounts.create') }}" class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                <i class="fa-solid fa-plus mr-2"></i>
                New Discount
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Code</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Value</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Expires</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($discounts ?? [] as $discount)
                        <tr>
                            <td class="px-4 py-4 align-top">
                                <div class="font-medium text-gray-900">{{ $discount['code'] ?? $discount['Code'] ?? 'NO-CODE' }}</div>
                                <div class="text-xs text-gray-400">ID: {{ $discount['id'] ?? $discount['Id'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4 align-top text-sm text-gray-700">{{ $discount['type'] ?? $discount['Type'] ?? '-' }}</td>
                            <td class="px-4 py-4 align-top text-sm text-gray-700">
                                @php
                                    $value = $discount['value'] ?? $discount['Value'] ?? 0;
                                    $type = $discount['type'] ?? $discount['Type'] ?? 'fixed';
                                @endphp
                                {{ $type === 'percentage' ? rtrim(rtrim(number_format((float) $value, 2, '.', ''), '0'), '.') . '%' : number_format((float) $value, 0, ',', '.') . ' đ' }}
                            </td>
                            <td class="px-4 py-4 align-top text-sm text-gray-700">{{ $discount['expiresAt'] ?? $discount['ExpiresAt'] ?? '-' }}</td>
                            <td class="px-4 py-4 align-top">
                                @php
                                    $active = $discount['isActive'] ?? $discount['IsActive'] ?? true;
                                @endphp
                                @if($active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 align-top text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.discounts.edit', $discount['id'] ?? $discount['Id'] ?? '') }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Edit</a>
                                    <form method="POST" action="{{ route('admin.discounts.destroy', $discount['id'] ?? $discount['Id'] ?? '') }}" onsubmit="return confirm('Delete this discount?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-red-300 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No discounts returned from the backend API.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endcomponent
