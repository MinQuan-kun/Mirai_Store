<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function index()
    {
        try {
            $response = $this->backend->get('games', [
                'pageSize' => 12
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $games = collect($data['data'] ?? [])->map(fn($g) => (object)[
                    'id' => $g['id'],
                    'name' => $g['title'],
                    'image' => $g['imageUrl'],
                    'price' => $g['price'],
                    'category' => $g['categoryName']
                ]);

                $recommendedGames = $games->take(4);

                return view('welcome', compact('games', 'recommendedGames'));
            }

            return view('welcome', ['games' => collect(), 'recommendedGames' => collect()]);

        } catch (\Exception $e) {
            return view('welcome', ['games' => collect(), 'recommendedGames' => collect()])
                ->with('error', 'Lỗi kết nối Backend: ' . $e->getMessage());
        }
    }

    public function shop(Request $request)
    {
        try {
            $params = [
                'search' => $request->search,
                'category' => $request->category,
                'minPrice' => $request->min_price,
                'maxPrice' => $request->max_price,
                'publisher' => $request->publisher,
                'platform' => $request->platform,
                'sort' => $request->sort,
                'page' => $request->page ?? 1,
                'pageSize' => 12
            ];

            $response = $this->backend->get('games', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                $games = collect($data['data'] ?? [])->map(fn($g) => (object)[
                    'id' => $g['id'],
                    'name' => $g['title'],
                    'image' => $g['imageUrl'],
                    'price' => $g['price'],
                    'category' => $g['categoryName']
                ]);

                $categories = collect([
                    (object)['id' => 'action', 'name' => 'Hành động'],
                    (object)['id' => 'rpg', 'name' => 'Nhập vai'],
                    (object)['id' => 'adventure', 'name' => 'Phiêu lưu']
                ]);

                return view('shop.index', compact('games', 'categories'));
            }

            return view('shop.index', ['games' => collect(), 'categories' => collect()]);

        } catch (\Exception $e) {
            return view('shop.index', ['games' => collect(), 'categories' => collect()]);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->backend->get("games/{$id}");

            if ($response->successful()) {
                $g = $response->json()['data'];
                
                $game = (object)[
                    'id' => $g['id'],
                    'name' => $g['name'],
                    'image' => $g['image'],
                    'price' => $g['price'],
                    'publisher' => $g['publisher'] ?? 'N/A',
                    'release_date' => 'N/A',
                    'description' => $g['description'] ?? '',
                ];

                $relatedResponse = $this->backend->get('games', ['pageSize' => 4]);
                $relatedGames = collect($relatedResponse->json()['data'] ?? [])->map(fn($rg) => (object)[
                    'id' => $rg['id'],
                    'name' => $rg['title'],
                    'image' => $rg['imageUrl'],
                    'price' => $rg['price']
                ]);

                return view('games.show', compact('game', 'relatedGames'));
            }

            abort(404);

        } catch (\Exception $e) {
            abort(500, 'Lỗi kết nối Backend');
        }
    }

    public function chatbotSend(Request $request)
    {
        try {
            $response = $this->backend->post('chatbot/chat', [
                'message' => $request->message
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Lỗi kết nối tới chatbot.']);
        }
    }
}
