<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class OrderController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

<<<<<<< Updated upstream
    
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
=======
    /**
     * [GetMyOrders] - Xem lịch sử các game đã mua
     */
    public function index(Request $request)
    {
        try {
            $response = $this->backend->get('orders/my-orders');

            if ($response->successful()) {
                $rawOrders = collect($response->json()['data'] ?? []);
                
                $data = $rawOrders->map(fn($o) => (object)[
                    'id' => $o['id'],
                    'order_number' => $o['orderNumber'],
                    'total_amount' => $o['totalAmount'],
                    'status' => $o['status'],
                    'created_at' => Carbon::parse($o['id'] ? null : now()), // Giả định mapping thời gian nếu Backend không trả về field riêng
                    'items_count' => 0, // Sẽ được cập nhật nếu Backend trả về chi tiết hoặc items
                ]);
>>>>>>> Stashed changes

                // Phân trang Client-side (cho đơn giản vì API đang trả về list)
                $currentPage = $request->get('page', 1);
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

            return view('orders.index', ['orders' => new LengthAwarePaginator([], 0, 10)])
                ->with('error', 'Không thể lấy danh sách đơn hàng.');

        } catch (\Exception $e) {
            return view('orders.index', ['orders' => new LengthAwarePaginator([], 0, 10)])
                ->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }

    
    public function show($id)
    {
<<<<<<< Updated upstream
        
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
=======
        try {
            $response = $this->backend->get("orders/{$id}");
>>>>>>> Stashed changes

            if ($response->successful()) {
                $orderData = $response->json()['data'];
                $o = $orderData['order'];
                $items = collect($orderData['items'] ?? []);

                $order = (object)[
                    'id' => $o['id'],
                    'order_number' => $o['orderNumber'],
                    'total_amount' => $o['totalAmount'],
                    'status' => $o['status'],
                    'created_at' => Carbon::parse($o['id'] ? null : now()), // Mock thời gian nếu thiếu
                    'payment_method' => $o['paymentMethod'] ?? 'Ví điện tử',
                    'items' => $items->map(fn($i) => (object)[
                        'price' => $i['item']['price'],
                        'game' => (object)[
                            'name' => $i['game']['name'],
                            'image' => $i['game']['image'],
                            'publisher' => $i['game']['publisher'] ?? 'N/A',
                            'download_link' => $i['game']['downloadLink'] ?? '#',
                        ]
                    ])
                ];

                return view('orders.show', compact('order'));
            }

            abort(404);

        } catch (\Exception $e) {
            abort(500, 'Lỗi kết nối Backend');
        }
    }

    
    public function checkout(Request $request)
    {
<<<<<<< Updated upstream
        
        Session::forget('cart_items');
        
        return redirect()->route('orders.index')->with('success', 'Thanh toán đơn hàng thành công! Game đã có trong thư viện của bạn.');
=======
        try {
            $response = $this->backend->post('orders/process-checkout', [
                'discountCode' => $request->discount_code
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return redirect()->route('orders.show', $data['orderId'])
                    ->with('success', 'Thanh toán đơn hàng thành công! Game đã có trong thư viện của bạn.');
            }

            $errorMessage = $response->json()['message'] ?? 'Thanh toán thất bại.';
            return back()->with('error', $errorMessage);

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
>>>>>>> Stashed changes
    }
}
