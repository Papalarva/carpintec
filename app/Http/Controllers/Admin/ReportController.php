<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $salesByMonth = DB::table('orders')
            ->whereNotIn('status_id', [
                OrderStatus::CANCELLED->value,
                OrderStatus::RETURNED->value
            ])
            ->selectRaw("DATE_TRUNC('month', created_at) as month, SUM(total) as revenue, COUNT(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(fn ($row) => [
                'month'   => Carbon::parse($row->month)->translatedFormat('F Y'), // Ej: "mayo 2026"
                'revenue' => (float) $row->revenue,
                'count'   => (int) $row->count,
            ]);

        $chartData = [
            'labels' => $salesByMonth->pluck('month')->map(fn($m) => ucfirst($m))->toArray(),
            'revenues' => $salesByMonth->pluck('revenue')->toArray(),
        ];

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereNotIn('orders.status_id', [
                OrderStatus::CANCELLED->value,
                OrderStatus::RETURNED->value
            ])
            ->selectRaw('products.name, products.sku, SUM(order_items.quantity) as total_sold, SUM(order_items.quantity * order_items.unit_price) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        return view('admin.reports.index', compact('salesByMonth', 'chartData', 'topProducts'));
    }
}