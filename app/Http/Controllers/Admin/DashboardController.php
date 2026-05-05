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

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 👑 ZONA DEL ADMINISTRADOR (Analítica y Finanzas)
        if ($user->hasRole('admin')) {
            
            // 1. Consultas Reales a tu Base de Datos:
            $totalRevenue = Order::sum('total') ?? 0;
            $totalOrders = Order::count();
            
            // Nuevos clientes registrados en el mes actual
            $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)->count();

            $pendingQuotations = Quotation::where('status', 'pending')->count();
            $lowStockProducts = Product::join('inventory', 'products.id', '=', 'inventory.product_id')
                           ->whereColumn('inventory.quantity', '<=', 'inventory.min_quantity')
                           ->count();

            // 2. Configuración temporal para el componente de la gráfica
            // (En el futuro esto vendrá de un query agrupado por fechas)
            $salesChartConfig = [
                'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                'data' => [12000, 19000, 15000, 22000, 18000, 25000],
            ];

            // Retornamos la vista inyectando TODAS las variables
            return view('admin.dashboard', compact(
                'totalRevenue',
                'totalOrders',
                'newCustomersThisMonth',
                'pendingQuotations',
                'lowStockProducts',
                'salesChartConfig'
            ));
        }

        // 👷‍♂️ ZONA DEL WORKER (Operaciones Diarias)
        if ($user->hasRole('worker')) {
            
            // 1. Consultas Ligeras (Solo lo que necesitan para trabajar)
            $pendingQuotations = Quotation::where('status', 'pending')->count();
            $lowStockProducts = Product::join('inventory', 'products.id', '=', 'inventory.product_id')
                           ->whereColumn('inventory.quantity', '<=', 'inventory.min_quantity')
                           ->count();
            
            // Retornamos la misma vista, pero solo con la data operativa
            return view('admin.dashboard', compact(
                'pendingQuotations',
                'lowStockProducts'
            ));
        }

        // Si por alguna extraña razón un cliente normal logró pasar el middleware
        return redirect()->route('home');
    }
}