<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation.Response;

class AuthCustom
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('auth_token')) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        return $next($request);
    }
}
