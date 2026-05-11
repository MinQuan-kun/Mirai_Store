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
            $profileResponse = $this->backend->get('User/profile');
            $purchasedGameIds = [];

            if ($profileResponse->successful()) {
                $purchasedGameIds = collect($profileResponse->json()['purchasedGames'] ?? [])
                    ->pluck('id')
                    ->filter()
                    ->values()
                    ->all();
            }

            if ($response->successful()) {
                $data = $response->json();
                
                $games = collect($data['data'] ?? [])->map(fn($g) => (object)[
                    'id' => $g['id'],
                    'name' => $g['title'],
                    'image' => $g['imageUrl'],
                    'price' => $g['price'],
                    'category' => $g['categoryName'],
                    'download_link' => $g['downloadLink'] ?? $g['download_link'] ?? null,
                ]);

                $recommendedGames = $games->take(4);

                return view('welcome', compact('games', 'recommendedGames', 'purchasedGameIds'));
            }

            return view('welcome', ['games' => collect(), 'recommendedGames' => collect(), 'purchasedGameIds' => $purchasedGameIds]);

        } catch (\Exception $e) {
            return view('welcome', ['games' => collect(), 'recommendedGames' => collect(), 'purchasedGameIds' => []])
                ->with('error', 'Lỗi kết nối Backend: ' . $e->getMessage());
        }
    }

    public function shop(Request $request)
    {
        try {
            $profileResponse = $this->backend->get('User/profile');
            $purchasedGameIds = [];

            if ($profileResponse->successful()) {
                $purchasedGameIds = collect($profileResponse->json()['purchasedGames'] ?? [])
                    ->pluck('id')
                    ->filter()
                    ->values()
                    ->all();
            }

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
                    'category' => $g['categoryName'],
                    'download_link' => $g['downloadLink'] ?? $g['download_link'] ?? null,
                ]);

                $categories = collect([
                    (object)['id' => 'action', 'name' => 'Hành động'],
                    (object)['id' => 'rpg', 'name' => 'Nhập vai'],
                    (object)['id' => 'adventure', 'name' => 'Phiêu lưu']
                ]);

                return view('shop.index', compact('games', 'categories', 'purchasedGameIds'));
            }

            return view('shop.index', ['games' => collect(), 'categories' => collect(), 'purchasedGameIds' => $purchasedGameIds]);

        } catch (\Exception $e) {
            return view('shop.index', ['games' => collect(), 'categories' => collect(), 'purchasedGameIds' => []]);
        }
    }

    public function show($id)
    {
        try {
            $profileResponse = $this->backend->get('User/profile');
            $purchasedGameIds = [];

            if ($profileResponse->successful()) {
                $purchasedGameIds = collect($profileResponse->json()['purchasedGames'] ?? [])
                    ->pluck('id')
                    ->filter()
                    ->values()
                    ->all();
            }

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
                    'download_link' => $g['downloadLink'] ?? $g['download_link'] ?? null,
                ];

                $relatedResponse = $this->backend->get('games', ['pageSize' => 4]);
                $relatedGames = collect($relatedResponse->json()['data'] ?? [])->map(fn($rg) => (object)[
                    'id' => $rg['id'],
                    'name' => $rg['title'],
                    'image' => $rg['imageUrl'],
                    'price' => $rg['price'],
                    'download_link' => $rg['downloadLink'] ?? $rg['download_link'] ?? null,
                ]);

                return view('games.show', compact('game', 'relatedGames', 'purchasedGameIds'));
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

    public function gacha()
    {
        try {
            $response = $this->backend->get('games/gacha');

            if ($response->successful()) {
                $g = $response->json()['data'];

                $game = (object)[
                    'id' => $g['id'],
                    'name' => $g['name'],
                    'image' => $g['image'],
                    'price' => $g['price'],
                    'publisher' => $g['publisher'] ?? 'N/A',
                    'description' => $g['description'] ?? '',
                    'download_link' => $g['downloadLink'] ?? $g['download_link'] ?? null,
                ];

                return view('gacha.index', compact('game'));
            }

            return view('gacha.index', ['game' => null])->with('error', 'Không thể quay gacha lúc này.');
        } catch (\Exception $e) {
            return view('gacha.index', ['game' => null])->with('error', 'Lỗi kết nối Backend');
        }
    }

    public function wishlist()
    {
        try {
            $response = $this->backend->get('wishlist');
            $profileResponse = $this->backend->get('User/profile');
            $purchasedGameIds = [];

            if ($profileResponse->successful()) {
                $purchasedGameIds = collect($profileResponse->json()['purchasedGames'] ?? [])
                    ->pluck('id')
                    ->filter()
                    ->values()
                    ->all();
            }

            if ($response->successful()) {
                $wishlistItems = collect($response->json()['data'] ?? [])->map(fn($g) => (object)[
                    'id' => $g['id'],
                    'game' => (object)[
                        'id' => $g['id'],
                        'name' => $g['name'],
                        'image' => $g['image'],
                        'price' => $g['price'],
                        'publisher' => $g['publisher'] ?? 'N/A',
                        'download_link' => $g['downloadLink'] ?? $g['download_link'] ?? null,
                    ],
                ]);

                return view('wishlist.index', compact('wishlistItems', 'purchasedGameIds'));
            }

            return view('wishlist.index', [
                'wishlistItems' => collect(),
                'purchasedGameIds' => $purchasedGameIds,
            ])
                ->with('error', 'Không thể lấy danh sách yêu thích.');
        } catch (\Exception $e) {
            return view('wishlist.index', [
                'wishlistItems' => collect(),
                'purchasedGameIds' => [],
            ])
                ->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }

    public function addToWishlist($gameId)
    {
        try {
            $response = $this->backend->post("wishlist/add/{$gameId}");

            if ($response->successful()) {
                return back()->with('success', $response->json()['message'] ?? 'Đã thêm vào danh sách yêu thích.');
            }

            return back()->with('error', $response->json()['message'] ?? 'Không thể thêm vào danh sách yêu thích.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }

    public function removeFromWishlist($gameId)
    {
        try {
            $response = $this->backend->delete("wishlist/remove/{$gameId}");

            if ($response->successful()) {
                return back()->with('success', $response->json()['message'] ?? 'Đã xóa khỏi danh sách yêu thích.');
            }

            return back()->with('error', $response->json()['message'] ?? 'Không thể xóa khỏi danh sách yêu thích.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }
}
