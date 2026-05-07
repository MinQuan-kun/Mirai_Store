<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function edit()
    {
        
        $user = new \stdClass();
        $user->name = Session::get('user_name', 'Người dùng');
        $user->email = Session::get('user_email', 'user@example.com');
        $user->avatar = null;
        $user->balance = Session::get('user_balance', 0);
        $user->id = Session::get('user_id', 1);

        
        $purchasedGames = collect([
            (object)[
                'name' => 'Black Myth: Wukong',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681024/Game/673758fff46261230006323c_maxresdefault.jpg',
                'publisher' => 'Game Science',
                'download_link' => '#'
            ],
            (object)[
                'name' => 'Elden Ring',
                'image' => 'https://res.cloudinary.com/davfujasj/image/upload/v1731681146/Game/67375979f46261230006323e_elden-ring-shadow-of-the-erdtree-02.jpg',
                'publisher' => 'FromSoftware',
                'download_link' => '#'
            ]
        ]);

        $transactions = collect([
            (object)[
                'type' => 'deposit',
                'amount' => 500000,
                'payment_method' => 'MoMo',
                'status' => 'completed',
                'created_at' => now()->subDays(2)
            ],
            (object)[
                'type' => 'purchase',
                'amount' => 1290000,
                'order_id' => 'ORD12345678',
                'status' => 'completed',
                'created_at' => now()->subDay()
            ]
        ]);

        return view('profile.edit', compact('user', 'purchasedGames', 'transactions'));
    }

    public function update(Request $request)
    {
        return back()->with('status', 'profile-updated');
    }

    public function updateAvatar(Request $request)
    {
        return back()->with('status', 'avatar-updated');
    }

    public function destroy(Request $request)
    {
        return redirect('/')->with('success', 'Tài khoản đã được xóa (giả lập)');
    }
}
