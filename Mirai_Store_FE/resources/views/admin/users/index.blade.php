@component('components.admin-layout')
    <div class="py-6">
        <h2 class="text-2xl font-semibold mb-4">Users</h2>

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <p class="text-sm text-gray-600">The backend currently does not expose an admin user list endpoint. This page remains a placeholder so the navigation stays valid.</p>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-miku-600 hover:underline">Back to dashboard</a>
        </div>
    </div>
@endcomponent
