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
                    'created_at' => Carbon::parse($o['createdAt'] ?? now()),
                    'items_count' => $o['itemsCount'] ?? $o['items_count'] ?? 0,
                ]);
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
        try {
            $response = $this->backend->get("orders/{$id}");

            if ($response->successful()) {
                $orderData = $response->json()['data'];
                $o = $orderData['order'];
                $items = collect($orderData['items'] ?? []);

                $order = (object)[
                    'id' => $o['id'],
                    'order_number' => $o['orderNumber'],
                    'total_amount' => $o['totalAmount'],
                    'status' => $o['status'],
                    'created_at' => Carbon::parse($o['createdAt'] ?? now()),
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
    }
}
