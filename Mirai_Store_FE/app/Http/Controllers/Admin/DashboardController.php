<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\BackendService;

class DashboardController extends Controller
{
    protected $backend;

    public function __construct(BackendService $backend)
    {
        $this->backend = $backend;
    }

    public function index()
    {
        try {
            $response = $this->backend->get('admin/dashboard');
        } catch (\Exception $e) {
            return view('admin.dashboard', [
                'stats' => [],
                'charts' => [],
                'recentOrders' => [],
                'dashboardError' => 'Không thể kết nối tới backend API: ' . $e->getMessage(),
            ]);
        }

        if (!$response->ok()) {
            return view('admin.dashboard', [
                'stats' => [],
                'charts' => [],
                'recentOrders' => [],
                'dashboardError' => $response->json('message') ?? 'Không thể tải thống kê từ backend API.',
            ]);
        }

        $data = $response->json('data') ?? [];

        return view('admin.dashboard', [
            'stats' => $data['stats'] ?? [],
            'charts' => $data['charts'] ?? [],
            'recentOrders' => $data['recentOrders'] ?? [],
            'dashboardError' => null,
        ]);
    }
}
