<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;

class ProfileController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function edit()
    {
        try {
            // Gọi sang Backend .NET để lấy dữ liệu profile thực tế
            $response = $this->backend->get('User/profile');

            if ($response->successful()) {
                $profileData = $response->json();

                // Map dữ liệu từ .NET sang định dạng mà View (Blade) đang mong đợi
                $user = (object)($profileData['user'] ?? []);

                $purchasedGames = collect($profileData['purchasedGames'] ?? [])->map(fn($g) => (object)[
                    'id' => $g['id'],
                    'name' => $g['name'],
                    'image' => $g['image'],
                    'publisher' => $g['publisher'],
                    'download_link' => $g['downloadLink'] ?? '#',
                ]);

                $transactions = collect($profileData['transactions'] ?? [])->map(fn($t) => (object)[
                    'id' => $t['id'],
                    'type' => $t['type'],
                    'amount' => $t['amount'],
                    'status' => $t['status'],
                    'payment_method' => $t['paymentMethod'] ?? 'N/A',
                    'order_id' => $t['orderId'] ?? null,
                    'created_at' => Carbon::parse($t['createdAt']),
                ]);

                return view('profile.edit', compact('user', 'purchasedGames', 'transactions'));
            }

            // Nếu API lỗi (ví dụ chưa đăng nhập bên .NET), fallback về dữ liệu session hoặc báo lỗi
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập lại để xem hồ sơ.');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể kết nối tới máy chủ .NET: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        // Bạn có thể mở rộng để gọi $this->backend->patch(...) tại đây
        return back()->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request)
    {
        // Bạn có thể mở rộng để gọi $this->backend->post(...) tại đây
        return back()->with('status', 'avatar-updated');
    }

    public function destroy(Request $request)
    {
        // Bạn có thể mở rộng để gọi $this->backend->delete(...) tại đây
        return redirect('/')->with('success', 'Tài khoản đã được xóa');
    }
}
