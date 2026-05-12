<?php

namespace App\Http\Controllers;

use App\Services\BackendService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            
            $response = $this->backend->post('Auth/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                
                
                
                if (isset($data['token'])) {
                    Session::put('auth_token', $data['token']);
                    Session::put('user', $data['user'] ?? null);
                    Session::put('user_name', $data['user']['name'] ?? $data['user']['Name'] ?? $request->email);
                    Session::put('user_email', $data['user']['email'] ?? $data['user']['Email'] ?? $request->email);
                    Session::put('user_balance', $data['user']['balance'] ?? $data['user']['Balance'] ?? 0);
                        // store role for quick access in views
                        $roleVal = $data['user']['role'] ?? $data['user']['Role'] ?? ($data['user']['roles'] ?? null);
                        Session::put('user_role', $roleVal);
                    
                    return redirect()->intended('/')->with('success', 'Đăng nhập thành công!');
                }
            }

            return back()->withErrors([
                'email' => 'Thông tin đăng nhập không chính xác hoặc lỗi hệ thống.',
            ])->withInput($request->only('email', 'remember'));

        } catch (\Exception $e) {
            return back()->with('error', 'Không thể kết nối tới máy chủ: ' . $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = $this->backend->post('Auth/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            }

            $error = $response->json()['message'] ?? 'Đăng ký thất bại.';
            return back()->withErrors(['email' => $error])->withInput();

        } catch (\Exception $e) {
            return back()->with('error', 'Không thể kết nối tới máy chủ: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        Session::forget(['auth_token', 'user']);
        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }
}
