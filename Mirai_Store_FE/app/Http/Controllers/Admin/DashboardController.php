<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGames = Game::count();
        $totalOrders = Order::count();
        $totalRevenue = $this->castToFloat(Order::where('status', 'completed')->sum('total_amount'));

        $chartWeek = ['labels' => [], 'data' => []];
        $startOfWeek = Carbon::now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $val = Order::where('status', 'completed')->whereDate('created_at', $date)->sum('total_amount');

            $chartWeek['labels'][] = $date->format('d/m') . ' (' . $this->getDayName($date->dayOfWeek) . ')';
            $chartWeek['data'][] = $this->castToFloat($val);
        }

        $chartMonth = ['labels' => [], 'data' => []];
        $daysInMonth = Carbon::now()->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::now()->setDay($i);
            $val = Order::where('status', 'completed')->whereDate('created_at', $date)->sum('total_amount');

            $chartMonth['labels'][] = $i . '/' . Carbon::now()->format('m');
            $chartMonth['data'][] = $this->castToFloat($val);
        }

        $chartQuarter = ['labels' => [], 'data' => []];
        $startOfQuarter = Carbon::now()->startOfQuarter();
        $endOfQuarter = Carbon::now()->endOfQuarter();

        $currentMonth = $startOfQuarter->copy();
        while ($currentMonth <= $endOfQuarter) {
            $start = $currentMonth->copy()->startOfMonth();
            $end = $currentMonth->copy()->endOfMonth();

            $val = Order::where('status', 'completed')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');

            $chartQuarter['labels'][] = 'Tháng ' . $currentMonth->format('m');
            $chartQuarter['data'][] = $this->castToFloat($val);

            $currentMonth->addMonth();
        }

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGames',
            'totalOrders',
            'totalRevenue',
            'chartWeek',
            'chartMonth',
            'chartQuarter',
            'recentOrders'
        ));
    }

    public function exportCsv()
    {
        $fileName = 'bao_cao_' . date('Ymd_Hi') . '.csv';
        $orders = Order::with('user')->where('status', 'completed')->get();

        return response()->stream(function () use ($orders) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['Mã Đơn', 'Khách Hàng', 'Ngày Mua', 'Số Tiền', 'Trạng Thái']);
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'N/A',
                    $order->created_at->format('d/m/Y H:i'),
                    number_format($this->castToFloat($order->total_amount), 0, ',', '.'),
                    $order->status
                ]);
            }
            fclose($file);
        }, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache"
        ]);
    }

    private function castToFloat($value)
    {
        if (is_numeric($value)) return (float) $value;
        if (is_object($value) && method_exists($value, '__toString')) return (float) $value->__toString();
        return 0.0;
    }

    private function getDayName($dayIndex)
    {
        $days = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
        return $days[$dayIndex] ?? '';
    }
}