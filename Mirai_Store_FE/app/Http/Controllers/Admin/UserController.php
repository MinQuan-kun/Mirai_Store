<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);
        return view('admin.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'được mở khóa' : 'đã bị khóa';

        return redirect()->route('admin.users.index')->with('success', "Tài khoản {$user->name} đã $status thành công!");
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Tạo tài khoản với quyền Admin cố định
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 'active',
            'balance' => 0,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Đã thêm quản trị viên mới thành công!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // CHẶN XÓA CHÍNH MÌNH
        if ($user->id == Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Đã xóa người dùng thành công.');
    }

    public function resetPassword(User $user)
    {
        // Reset mật khẩu về 123456
        $user->password = bcrypt('123456');
        $user->save();

        return redirect()->route('admin.users.index')->with('success', "Đã reset mật khẩu của {$user->name} về '123456' thành công!");
    }
}
