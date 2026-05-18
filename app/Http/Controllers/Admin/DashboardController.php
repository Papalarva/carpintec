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

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 👑 ZONA DEL ADMINISTRADOR (Analítica y Finanzas)
        if ($user->hasRole('admin')) {

            $totalRevenue = Order::sum('total') ?? 0;
            $totalOrders = Order::count();
            $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)->count();
            $pendingQuotations = Quotation::where('status', 'pending')->count();
            $lowStockProducts = Product::join('inventory', 'products.id', '=', 'inventory.product_id')
                ->whereColumn('inventory.quantity', '<=', 'inventory.min_quantity')
                ->count();

            // Configuración de la gráfica principal
            $salesChartConfig = [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'data' => [12000, 19000, 15000, 22000, 18000, 25000],
            ];

            // NUEVO: Datos para el Embudo de Ventas (Ejemplo con datos estáticos iniciales)
            $funnelData = [
                'visits' => 4500,
                'carts' => 850,
                'checkouts' => 320,
                'purchases' => $totalOrders
            ];

            // NUEVO: Top 3 Muebles más vendidos (Consulta de ejemplo)
            /* $topProducts = Product::withCount('orders')
                                 ->orderBy('orders_count', 'desc')
                                 ->take(3)
                                 ->get(); */
            // Mock temporal para la vista:
            $topProducts = [
                (object)['name' => 'Mesa Comedor Nogal', 'sales' => 24, 'revenue' => 45000, 'image' => null],
                (object)['name' => 'Silla Minimalista Roble', 'sales' => 18, 'revenue' => 12500, 'image' => null],
                (object)['name' => 'Credenza Parota', 'sales' => 12, 'revenue' => 38000, 'image' => null],
            ];

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
        if ($user->hasRole('worker')) {

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
