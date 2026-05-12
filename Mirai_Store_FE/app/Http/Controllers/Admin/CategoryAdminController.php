<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackendService;
use Illuminate\Http\Request;

class CategoryAdminController extends Controller
{
    public function __construct(private BackendService $backend)
    {
    }

    public function index()
    {
        $response = $this->backend->get('admin/categories');
        $categories = $response->ok() ? ($response->json('data') ?? []) : [];

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $payload = [
            'Name' => $validated['name'],
            'Description' => $validated['description'] ?? '',
        ];

        $response = $this->backend->post('admin/categories', $payload);

        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', $response->json('message') ?? 'Tạo danh mục thành công!');
        }

        return back()->withInput()->with('error', $this->extractMessage($response));
    }

    public function edit(string $id)
    {
        if (empty($id)) {
            return redirect()->route('admin.categories.index')->with('error', 'Category ID không hợp lệ');
        }

        $resp = $this->backend->get("admin/categories/{$id}");
        if (!$resp->ok()) {
            \Log::error('Category edit fetch failed', ['status' => $resp->status(), 'body' => $resp->body(), 'url' => "admin/categories/{$id}"]);
            return redirect()->route('admin.categories.index')->with('error', 'Không thể tải dữ liệu danh mục: ' . $this->extractMessage($resp));
        }

        $category = $resp->json('data') ?? [];
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $payload = [
            'Name' => $validated['name'],
            'Description' => $validated['description'] ?? '',
        ];

        $response = $this->backend->put("admin/categories/{$id}", $payload);

        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', $response->json('message') ?? 'Cập nhật danh mục thành công!');
        }

        return back()->withInput()->with('error', $this->extractMessage($response));
    }

    public function destroy(string $id)
    {
        $response = $this->backend->delete("admin/categories/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.categories.index')->with('success', $response->json('message') ?? 'Đã xóa danh mục thành công!');
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
