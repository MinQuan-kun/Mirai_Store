<?php

namespace App\Providers;

use App\Services\BackendService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

    
    public function boot(): void
    {
        View::composer('components.shop-header', function ($view) {
            if (!Session::has('auth_token')) {
                return;
            }

            try {
                $backend = app(BackendService::class);
                $response = $backend->get('wallet/balance');

                if ($response->successful()) {
                    $data = $response->json();
                    $balance = $data['balance'] ?? $data['Balance'] ?? 0;
                    Session::put('user_balance', $balance);
                }
            } catch (\Throwable $e) {
                // Giữ số dư trong session nếu backend tạm thời lỗi.
            }
        });
    }
}
