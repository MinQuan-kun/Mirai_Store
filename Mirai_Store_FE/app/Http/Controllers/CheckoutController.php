<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function index()
    {
        try {
            $response = $this->backend->get('orders/checkout-data');

            if ($response->successful()) {
                $data = $response->json()['data'];
                
                $user = (object)[
                    'balance' => $data['userBalance']
                ];

                $cartItems = collect($data['items'] ?? [])->map(fn($i) => (object)[
                    'id' => $i['game']['id'],
                    'price_at_time' => $i['priceAtTime'],
                    'game' => (object)[
                        'id' => $i['game']['id'],
                        'name' => $i['game']['name'],
                        'image' => $i['game']['image'],
                        'publisher' => $i['game']['publisher'] ?? 'N/A'
                    ]
                ]);

                $total = $data['subtotal'];

                return view('checkout.index', compact('user', 'cartItems', 'total'));
            }

            return redirect()->route('cart.index')->with('error', 'Không thể tải dữ liệu thanh toán.');

        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
    }

    public function process(Request $request)
    {
        // Chuyển hướng sang OrderController@checkout để xử lý thanh toán tập trung
        return app(OrderController::class)->checkout($request);
    }

    public function validateDiscount(Request $request)
    {
        try {
            $response = $this->backend->post('orders/validate-discount', [
                'code' => $request->code,
                'total' => $request->total
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['valid' => false, 'message' => 'Lỗi kết nối.']);
        }
    }
}
