<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    
    public function index()
    {
        
        $cartItems = collect([
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
        ]);

        $total = $cartItems->sum('price_at_time');

        return view('cart.index', compact('cartItems', 'total'));
    }

    
    public function addToCart(Request $request)
    {
        return back()->with('success', 'Đã thêm vào giỏ hàng (Mock)');
    }

    
    public function remove($id)
    {
        return back()->with('success', 'Đã xóa khỏi giỏ hàng (Mock)');
    }

    
    public function clear()
    {
        return back()->with('success', 'Đã làm trống giỏ hàng (Mock)');
    }
}
