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
            $response = $this->backend->get('admin/stats');
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
        $stats = $data['stats'] ?? [];
        $charts = $data['charts'] ?? [];

        return view('admin.dashboard', [
            'totalRevenue' => $stats['revenue'] ?? 0,
            'totalOrders' => $stats['orders'] ?? 0,
            'totalUsers' => $stats['users'] ?? 0,
            'totalGames' => $stats['games'] ?? 0,
            'chartWeek' => $charts['week'] ?? [],
            'chartMonth' => $charts['month'] ?? [],
            'chartQuarter' => $charts['quarter'] ?? [],
            'recentOrders' => $data['recentOrders'] ?? [],
            'dashboardError' => null,
        ]);
    }
}
