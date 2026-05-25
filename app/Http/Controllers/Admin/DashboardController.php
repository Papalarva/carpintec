<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Inventory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (optional($user)->hasRole('admin')) {

            $totalRevenue = Order::sum('total') ?? 0;
            $totalOrders = Order::count();
            $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)->count();
            $pendingQuotations = Quotation::where('status', 'pending')->count();
            $lowStockProducts = Product::join('inventory', 'products.id', '=', 'inventory.product_id')
                ->whereColumn('inventory.quantity', '<=', 'inventory.min_quantity')
                ->count();

            $salesChartConfig = [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'data' => [12000, 19000, 15000, 22000, 18000, 25000],
            ];

            $funnelData = [
                'visits' => 4500,
                'carts' => 850,
                'checkouts' => 320,
                'purchases' => $totalOrders
            ];

            $topProducts = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->whereNotIn('orders.status_id', [
                    OrderStatus::CANCELLED->value,
                    OrderStatus::RETURNED->value,
                ])
                ->selectRaw('products.name, products.sku, SUM(order_items.quantity) as total_sold, SUM(order_items.quantity * order_items.unit_price) as total_revenue')
                ->groupBy('products.id', 'products.name', 'products.sku')
                ->orderByDesc('total_revenue')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'name' => $item->name,
                        'sku' => $item->sku,
                        'sales' => (int) $item->total_sold,
                        'revenue' => (float) $item->total_revenue,
                        'total_sold' => (int) $item->total_sold,
                        'total_revenue' => (float) $item->total_revenue,
                    ];
                });

            return view('admin.dashboard', compact(
                'totalRevenue',
                'totalOrders',
                'newCustomersThisMonth',
                'pendingQuotations',
                'lowStockProducts',
                'salesChartConfig',
                'funnelData',
                'topProducts'
            ));
        }

        // 👷‍♂️ ZONA DEL WORKER (Operaciones Diarias)
        if (optional($user)->hasRole('worker')) {

            $pendingQuotations = Quotation::where('status', 'pending')->count();
            $lowStockProducts = Product::join('inventory', 'products.id', '=', 'inventory.product_id')
                ->whereColumn('inventory.quantity', '<=', 'inventory.min_quantity')
                ->count();

            $pendingOrders = Order::where('status_id', OrderStatus::PENDING->value)->count();
            $completedToday = Order::where('status_id', OrderStatus::DELIVERED->value)
                ->whereDate('updated_at', today())
                ->count();

            $dailyGoal = 15;
            $progressPercentage = $dailyGoal > 0 ? min(100, ($completedToday / $dailyGoal) * 100) : 0;

            return view('admin.dashboard', compact(
                'pendingQuotations',
                'lowStockProducts',
                'pendingOrders',
                'completedToday',
                'dailyGoal',
                'progressPercentage'
            ));
        }

        return redirect()->route('home');
    }
}
