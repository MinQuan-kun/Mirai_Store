<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    
    public function index()
    {
<<<<<<< Updated upstream
        
        if (!Session::has('cart_items')) {
            $this->initializeMockCart();
        }

        
        $cartItems = collect(Session::get('cart_items'))->map(function($item) {
            $itemObj = (object)$item;
            if (isset($itemObj->game) && is_array($itemObj->game)) {
                $itemObj->game = (object)$itemObj->game;
=======
        try {
            $response = $this->backend->get('Cart');

            if ($response->successful()) {
                $data = $response->json();
                
                // Map dữ liệu từ .NET API ({ items: [...], total: ... })
                $cartItems = collect($data['items'] ?? [])->map(fn($item) => (object)[
                    'id' => $item['id'],
                    'game_id' => $item['gameId'],
                    'price_at_time' => $item['priceAtTime'],
                    'quantity' => $item['quantity'],
                    'game' => (object)[
                        'id' => $item['gameId'],
                        'name' => $item['gameName'],
                        'image' => $item['gameImage'],
                        'publisher' => 'N/A' // .NET hiện chưa trả về publisher trong CartItemDto
                    ]
                ]);

                $total = $data['total'] ?? 0;

                return view('cart.index', compact('cartItems', 'total'));
>>>>>>> Stashed changes
            }

            return view('cart.index', ['cartItems' => collect(), 'total' => 0])
                ->with('error', 'Không thể lấy dữ liệu giỏ hàng.');

        } catch (\Exception $e) {
            return view('cart.index', ['cartItems' => collect(), 'total' => 0])
                ->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }

    
    public function addToCart(Request $request)
    {
        $request->validate([
            'game_id' => 'required|string'
        ]);

        try {
            $response = $this->backend->post('Cart/add', [
                'gameId' => $request->game_id
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return back()->with($data['status'] ?? 'success', $data['message']);
            }

            return back()->with('error', 'Không thể thêm vào giỏ hàng.');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
<<<<<<< Updated upstream

        $cart = Session::get('cart_items');
        
        
        $newGame = (object)[
            'id' => uniqid(), 
            'price_at_time' => 500000, 
            'game' => (object)[
                'id' => $request->game_id,
                'name' => 'Game Mới Thêm',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
                'publisher' => 'Publisher'
            ]
        ];

        $cart[] = $newGame;
        Session::put('cart_items', $cart);

        return back()->with('success', 'Đã thêm game vào giỏ hàng!');
=======
>>>>>>> Stashed changes
    }

    
    public function remove($id)
    {
<<<<<<< Updated upstream
        $cart = Session::get('cart_items', []);
        
        
        $newCart = array_filter($cart, function($item) use ($id) {
            $itemId = is_object($item) ? $item->id : ($item['id'] ?? null);
            return $itemId != $id;
        });
=======
        try {
            $response = $this->backend->delete("Cart/remove/{$id}");
>>>>>>> Stashed changes

            if ($response->successful()) {
                return back()->with('success', 'Đã xóa game khỏi giỏ hàng!');
            }

            return back()->with('error', 'Không thể xóa sản phẩm.');

<<<<<<< Updated upstream
    
    private function initializeMockCart()
    {
        $initialItems = [
            (object)[
                'id' => 1,
                'price_at_time' => 1290000,
                'game' => (object)[
                    'id' => 1,
                    'name' => 'Black Myth: Wukong',
                    'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
                    'publisher' => 'Game Science'
                ]
            ],
            (object)[
                'id' => 2,
                'price_at_time' => 990000,
                'game' => (object)[
                    'id' => 2,
                    'name' => 'Elden Ring',
                    'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681146/Game/67375979f46261230006323e_elden-ring-shadow-of-the-erdtree-02.jpg',
                    'publisher' => 'FromSoftware'
                ]
            ]
        ];
        Session::put('cart_items', $initialItems);
=======
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
>>>>>>> Stashed changes
    }

    public function clear()
    {
        try {
            $response = $this->backend->delete("Cart/clear");

            if ($response->successful()) {
                return back()->with('success', 'Đã làm trống giỏ hàng!');
            }

            return back()->with('error', 'Không thể làm trống giỏ hàng.');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }
}
