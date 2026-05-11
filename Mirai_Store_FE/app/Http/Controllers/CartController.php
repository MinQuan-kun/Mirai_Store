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
    }

    
    public function remove($id)
    {
        try {
            $response = $this->backend->delete("Cart/remove/{$id}");

            if ($response->successful()) {
                return back()->with('success', 'Đã xóa game khỏi giỏ hàng!');
            }

            return back()->with('error', 'Không thể xóa sản phẩm.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi kết nối: ' . $e->getMessage());
        }
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
