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
        $response = $this->backend->get('Cart');
        
        if ($response->successful()) {
            $data = $response->json();
            $cartItems = $data['items'] ?? [];
            $total = $data['total'] ?? 0;
            return view('cart.index', compact('cartItems', 'total'));
        }

        return redirect()->back()->with('error', 'Không thể lấy dữ liệu giỏ hàng.');
    }

    
    public function addToCart(Request $request)
    {
        $request->validate([
            'game_id' => 'required'
        ]);

        $response = $this->backend->post('Cart/add', [
            'gameId' => $request->game_id
        ]);

        $data = $response->json();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $data['status'] ?? ($response->successful() ? 'success' : 'error'),
                'message' => $data['message'] ?? 'Có lỗi xảy ra.'
            ], $response->status());
        }

        return back()->with($data['status'] ?? 'info', $data['message'] ?? 'Kết quả không xác định');
    }

    
    public function remove($id)
    {
        $response = $this->backend->delete("Cart/remove/{$id}");
        $data = $response->json();

        return back()->with($data['status'] ?? 'success', $data['message'] ?? 'Đã thực hiện.');
    }

    
    public function clear()
    {
        $response = $this->backend->delete('Cart/clear');
        $data = $response->json();

        return back()->with($data['status'] ?? 'success', $data['message'] ?? 'Đã làm trống giỏ hàng.');
    }
}
