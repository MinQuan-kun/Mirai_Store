<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    
    public function index()
    {
        
        $data = collect([
            (object)[
                'id' => 1,
                'order_number' => 'ORD-998877',
                'total_amount' => 1290000,
                'status' => 'completed',
                'created_at' => now()->subDays(2),
                'items_count' => 1,
                'items' => collect([
                    (object)[
                        'price' => 1290000,
                        'game' => (object)[
                            'name' => 'Black Myth: Wukong',
                            'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
                        ]
                    ]
                ])
            ],
            (object)[
                'id' => 2,
                'order_number' => 'ORD-445566',
                'total_amount' => 990000,
                'status' => 'completed',
                'created_at' => now()->subMonth(),
                'items_count' => 1,
                'items' => collect([
                    (object)[
                        'price' => 990000,
                        'game' => (object)[
                            'name' => 'Elden Ring',
                            'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681146/Game/67375979f46261230006323e_elden-ring-shadow-of-the-erdtree-02.jpg',
                        ]
                    ]
                ])
            ]
        ]);

        
        $currentPage = 1;
        $perPage = 10;
        $orders = new LengthAwarePaginator(
            $data->forPage($currentPage, $perPage),
            $data->count(),
            $perPage,
            $currentPage,
            ['path' => url()->current()]
        );

        return view('orders.index', compact('orders'));
    }

    
    public function show($id)
    {
        
        $order = (object)[
            'id' => $id,
            'order_number' => 'ORD-998877',
            'total_amount' => 1290000,
            'status' => 'completed',
            'created_at' => now()->subDays(2),
            'payment_method' => 'Ví điện tử',
            'items' => collect([
                (object)[
                    'price' => 1290000,
                    'game' => (object)[
                        'name' => 'Black Myth: Wukong',
                        'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
                        'publisher' => 'Game Science',
                        'download_link' => 'https://store.steampowered.com/app/2358720/Black_Myth_Wukong/'
                    ]
                ]
            ])
        ];

        return view('orders.show', compact('order'));
    }

    
    public function checkout(Request $request)
    {
        
        Session::forget('cart_items');
        
        return redirect()->route('orders.index')->with('success', 'Thanh toán đơn hàng thành công! Game đã có trong thư viện của bạn.');
    }
}
