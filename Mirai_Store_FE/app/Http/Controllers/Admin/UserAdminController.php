<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackendService;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    public function __construct(private BackendService $backend)
    {
    }

    public function index()
    {
        $response = $this->backend->get('admin/users');
        $users = $response->ok() ? ($response->json('data') ?? []) : [];

        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(string $id)
    {
        $response = $this->backend->patch("admin/users/{$id}/toggle-status");

        if ($response->successful()) {
            return redirect()->route('admin.users.index')->with('success', $response->json('message') ?? 'Đã cập nhật trạng thái!');
        }

        return back()->with('error', $this->extractMessage($response));
    }

    public function updateRole(Request $request, string $id)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:user,admin',
        ]);

        $response = $this->backend->patch("admin/users/{$id}/role", [
            'role' => $validated['role'],
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.users.index')->with('success', $response->json('message') ?? 'Đã cập nhật quyền!');
        }

        return back()->with('error', $this->extractMessage($response));
    }

    public function resetPassword(string $id)
    {
        $response = $this->backend->patch("admin/users/{$id}/reset-password");

        if ($response->successful()) {
            return redirect()->route('admin.users.index')->with('success', $response->json('message') ?? 'Đã reset mật khẩu!');
        }

        return back()->with('error', $this->extractMessage($response));
    }

    public function destroy(string $id)
    {
        $response = $this->backend->delete("admin/users/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.users.index')->with('success', $response->json('message') ?? 'Đã xóa người dùng!');
        }

        return back()->with('error', $this->extractMessage($response));
    }

    private function extractMessage($response): string
    {
        return $response?->json('message')
            ?? $response?->json('Message')
            ?? $response?->body()
            ?? 'Backend trả về lỗi không xác định.';
    }
}
