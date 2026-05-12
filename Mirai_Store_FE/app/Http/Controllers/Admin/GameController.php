<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with('categories')->latest()->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.games.create', compact('categories'));
    }

    // --- HÀM STORE  ---
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,_id',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
            'download_link' => 'nullable|url',
            'publisher' => 'required|string|max:255',
            'platforms' => 'required|array',
            'languages' => 'required|array',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();

        $data['slug'] = Str::slug($request->name) . '-' . time();
        $data['is_active'] = $request->has('is_active');

        if (in_array('Khác', $request->languages) && $request->other_language) {
            $langs = $request->languages;
            $langs = array_filter($langs, fn($l) => $l !== 'Khác');
            $langs[] = $request->other_language;
            $data['languages'] = array_values($langs);
        }

        // Xử lý Upload ảnh lên Cloudinary
        if ($request->hasFile('image')) {
            Configuration::instance(env('CLOUDINARY_URL'));
            $upload = new UploadApi();
            $result = $upload->upload($request->file('image')->getRealPath(), [
                'folder' => 'Shop_Game/games',
                'public_id' => 'game_' . $data['slug'],
                'overwrite' => true,
            ]);
            $data['image'] = $result['secure_url'];
        }

        Game::create($data);

        return redirect()->route('admin.games.index')->with('success', 'Thêm game thành công!');
    }

    public function edit(Game $game)
    {
        $categories = Category::all();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    // --- HÀM UPDATE ---
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,_id',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'download_link' => 'nullable|url',
            'publisher' => 'nullable|string|max:255',
            'platforms' => 'nullable|array',
            'languages' => 'required|array',
            'description' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        if (in_array('Khác', $request->languages) && $request->other_language) {
            $langs = $request->languages;
            $langs = array_filter($langs, fn($l) => $l !== 'Khác');
            $langs[] = $request->other_language;
            $data['languages'] = array_values($langs);
        }

        // XỬ LÝ UPLOAD ẢNH KHI SỬA
        if ($request->hasFile('image')) {
            Configuration::instance(env('CLOUDINARY_URL'));
            $upload = new UploadApi();


            $result = $upload->upload($request->file('image')->getRealPath(), [
                'folder' => 'Shop_Game/games', // Thư mục con
                'public_id' => 'game_' . $data['slug'] . '_' . time(),
                'overwrite' => true,
            ]);

            $data['image'] = $result['secure_url'];
        }

        $game->update($data);

        return redirect()->route('admin.games.index')->with('success', 'Cập nhật game thành công!');
    }

    public function destroy(Game $game)
    {

        $game->delete();
        return redirect()->route('admin.games.index')->with('success', 'Đã xóa game thành công!');
    }
}