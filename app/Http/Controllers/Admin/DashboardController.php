<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = DB::table('orders')->count();

        $totalRevenue = DB::table('orders')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereNotIn('order_statuses.name', ['CANCELLED', 'REFUNDED'])
            ->sum('total');

        $newCustomersThisMonth = DB::table('customers')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingQuotations = DB::table('quotations')
            ->where('status', 'pending')
            ->count();

        $lowStockProducts = DB::table('inventory')
            ->whereColumn('quantity', '<', 'min_quantity')
            ->count();

        // Ventas de los últimos 6 meses (sin cancelados ni reembolsados)
        $sales = DB::table('orders')
            ->join('order_statuses', 'orders.status_id', '=', 'order_statuses.id')
            ->whereNotIn('order_statuses.name', ['CANCELLED', 'REFUNDED'])
            ->selectRaw("to_char(orders.created_at, 'YYYY-MM') as month, sum(orders.total) as revenue")
            ->where('orders.created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month');

        $labels = [];
        $data   = [];
        $start  = now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $month    = $start->copy()->addMonths($i)->format('Y-m');
            $labels[] = $start->copy()->addMonths($i)->translatedFormat('M Y');
            $data[]   = $sales->get($month, 0);
        }

        $salesChartConfig = [
            'type' => 'line',
            'data' => [
                'labels'   => $labels,
                'datasets' => [
                    [
                        'label'           => 'Ingresos mensuales',
                        'data'            => $data,
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'borderColor'     => 'rgba(59, 130, 246, 1)',
                        'borderWidth'     => 2,
                        'tension'         => 0.3,
                    ],
                ],
            ],
            'options' => [
                'responsive'          => true,
                'maintainAspectRatio' => false,
                'scales'              => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'newCustomersThisMonth',
            'pendingQuotations',
            'lowStockProducts',
            'salesChartConfig'
        ));
    }
}