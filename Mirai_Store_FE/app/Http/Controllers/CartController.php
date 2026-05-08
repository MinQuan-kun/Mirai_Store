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
        
        if (!Session::has('cart_items')) {
            $this->initializeMockCart();
        }

        
        $cartItems = collect(Session::get('cart_items'))->map(function($item) {
            $itemObj = (object)$item;
            if (isset($itemObj->game) && is_array($itemObj->game)) {
                $itemObj->game = (object)$itemObj->game;
            }
            return $itemObj;
        });
        $total = $cartItems->sum('price_at_time');

        return view('cart.index', compact('cartItems', 'total'));
    }

    
    public function addToCart(Request $request)
    {
        if (!Session::has('cart_items')) {
            $this->initializeMockCart();
        }

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
    }

    
    public function remove($id)
    {
        $cart = Session::get('cart_items', []);
        
        
        $newCart = array_filter($cart, function($item) use ($id) {
            $itemId = is_object($item) ? $item->id : ($item['id'] ?? null);
            return $itemId != $id;
        });

        Session::put('cart_items', array_values($newCart));

        return back()->with('success', 'Đã xóa game khỏi giỏ hàng!');
    }

    
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
    }

    public function clear()
    {
        Session::forget('cart_items');
        return back()->with('success', 'Đã làm trống giỏ hàng!');
    }
}
