<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        
        $user = new \stdClass();
        $user->balance = Session::get('user_balance', 5000000); 

        
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

        return view('checkout.index', compact('user', 'cartItems', 'total'));
    }

    public function process(Request $request)
    {
        
        return redirect()->route('home')->with('success', 'Thanh toán thành công! Game đã được thêm vào thư viện.');
    }

    public function validateDiscount(Request $request)
    {
        $code = $request->code;
        $total = $request->total;

        if (strtoupper($code) === 'GAME10') {
            $discount = $total * 0.1;
            return response()->json([
                'valid' => true,
                'discount_amount' => $discount,
                'final_total' => $total - $discount,
                'message' => 'Áp dụng mã giảm giá 10% thành công!'
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
        ]);
    }
}
