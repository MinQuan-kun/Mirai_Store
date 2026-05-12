<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackendService;
use Illuminate\Http\Request;

class GameAdminController extends Controller
{
    public function __construct(private BackendService $backend)
    {
    }

    public function index()
    {
        $response = $this->backend->get('admin/games');
        $games = $response->ok() ? ($response->json('data') ?? []) : [];

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $categories = $this->loadCategories();

        return view('admin.games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'image_url' => 'nullable|url',
        ]);

        $payload = [
            'Title' => $validated['title'],
            'Description' => $validated['description'] ?? '',
            'Price' => $validated['price'],
            'CategoryId' => $validated['category_id'],
            'ImageUrl' => $validated['image_url'] ?? null,
        ];

        $file = $request->file('image');
        $response = $file
            ? $this->backend->multipartPost('admin/games', $payload, 'ImageFile', $file->getRealPath(), $file->getClientOriginalName())
            : $this->backend->postForm('admin/games', $payload);

        if ($response->successful()) {
            return redirect()->route('admin.games.index')->with('success', $response->json('message') ?? 'Thêm game thành công!');
        }

        return back()->withInput()->with('error', $this->extractMessage($response));
    }

    public function edit(string $id)
    {
        if (empty($id)) {
            return redirect()->route('admin.games.index')->with('error', 'Game ID không hợp lệ');
        }

        $gameResponse = $this->backend->get("admin/games/{$id}");
        
        if (!$gameResponse->ok()) {
            \Log::error('Game edit fetch failed', [
                'status' => $gameResponse->status(),
                'body' => $gameResponse->body(),
                'url' => "admin/games/{$id}"
            ]);
            return redirect()->route('admin.games.index')->with('error', 'Không thể tải dữ liệu game: ' . $this->extractMessage($gameResponse));
        }

        $game = $gameResponse->json('data') ?? [];
        if (empty($game)) {
            return redirect()->route('admin.games.index')->with('error', 'Game data trống từ backend');
        }

        $categories = $this->loadCategories();

        return view('admin.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'image_url' => 'nullable|url',
        ]);

        $payload = [
            'Title' => $validated['title'],
            'Description' => $validated['description'] ?? '',
            'Price' => $validated['price'],
            'CategoryId' => $validated['category_id'],
            'ImageUrl' => $validated['image_url'] ?? null,
        ];

        $file = $request->file('image');
        $response = $file
            ? $this->backend->multipartPut("admin/games/{$id}", $payload, 'ImageFile', $file->getRealPath(), $file->getClientOriginalName())
            : $this->backend->putForm("admin/games/{$id}", $payload);

        if ($response->successful()) {
            return redirect()->route('admin.games.index')->with('success', $response->json('message') ?? 'Cập nhật game thành công!');
        }

        return back()->withInput()->with('error', $this->extractMessage($response));
    }

    public function destroy(string $id)
    {
        $response = $this->backend->delete("admin/games/{$id}");

        if ($response->successful()) {
            return redirect()->route('admin.games.index')->with('success', $response->json('message') ?? 'Đã xóa game thành công!');
        }

        return back()->with('error', $this->extractMessage($response));
    }

    public function toggleStatus(string $id)
    {
        $response = $this->backend->patch("admin/games/{$id}/toggle-status");

        if ($response->successful()) {
            return redirect()->route('admin.games.index')->with('success', $response->json('message') ?? 'Đã cập nhật trạng thái game!');
        }

        return back()->with('error', $this->extractMessage($response));
    }

    private function loadCategories(): array
    {
        $response = $this->backend->get('admin/categories');
        return $response->ok() ? ($response->json('data') ?? []) : [];
    }

    private function extractMessage($response): string
    {
        return $response?->json('message')
            ?? $response?->json('Message')
            ?? $response?->body()
            ?? 'Backend trả về lỗi không xác định.';
    }
}
