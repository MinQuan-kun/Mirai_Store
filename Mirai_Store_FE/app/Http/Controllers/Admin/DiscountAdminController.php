<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackendService;
use Illuminate\Http\Request;

class DiscountAdminController extends Controller
{
    public function __construct(private BackendService $backend)
    {
    }

    public function index()
    {
        $response = $this->backend->get('admin/discounts');
        $discounts = $response->ok() ? ($response->json('data') ?? []) : [];

        return view('admin.discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('admin.discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'required|date',
            'is_active' => 'nullable|boolean',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $payload = [
            'Code' => strtoupper($validated['code']),
            'Type' => $validated['type'],
            'Value' => $validated['value'],
            'ExpiresAt' => $validated['expires_at'],
            'IsActive' => $request->boolean('is_active', true),
            'UsageLimit' => $validated['usage_limit'] ?? null,
        ];

        $response = $this->backend->post('admin/discounts', $payload);

        if ($response->successful()) {
            return redirect()->route('admin.discounts.index')->with('success', $response->json('message') ?? 'Tạo mã giảm giá thành công!');
        }

        return back()->withInput()->with('error', $this->extractMessage($response));
    }

    public function edit(string $id)
    {
        if (empty($id)) {
            return redirect()->route('admin.discounts.index')->with('error', 'Discount ID không hợp lệ');
        }

        $response = $this->backend->get("admin/discounts/{$id}");
        if (!$response->ok()) {
            \Log::error('Discount edit fetch failed', ['status' => $response->status(), 'body' => $response->body(), 'url' => "admin/discounts/{$id}"]);
            return redirect()->route('admin.discounts.index')->with('error', 'Không thể tải dữ liệu mã giảm giá: ' . $this->extractMessage($response));
        }

        $discount = $response->json('data') ?? [];
        return view('admin.discounts.edit', compact('discount'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expires_at' => 'required|date',
            'is_active' => 'nullable|boolean',
            'usage_limit' => 'nullable|integer|min:1',
        ]);

        $payload = [
            'Code' => strtoupper($validated['code']),
            'Type' => $validated['type'],
            'Value' => $validated['value'],
            'ExpiresAt' => $validated['expires_at'],
            'IsActive' => $request->boolean('is_active', true),
            'UsageLimit' => $validated['usage_limit'] ?? null,
        ];

        $response = $this->backend->put("admin/discounts/{$id}", $payload);

        if ($response->successful()) {
            return redirect()->route('admin.discounts.index')->with('success', $response->json('message') ?? 'Cập nhật mã giảm giá thành công!');
        }

        return back()->withInput()->with('error', $this->extractMessage($response));
    }

    public function destroy(string $id)
    {
        $response = $this->backend->delete("admin/discounts/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.discounts.index')->with('success', $response->json('message') ?? 'Đã xóa mã giảm giá thành công!');
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
