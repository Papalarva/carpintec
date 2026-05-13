<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Mostrar el listado de compras del cliente autenticado.
     */
    public function index()
    {
        // Traemos solo las órdenes que pertenecen al usuario logueado
        $orders = Order::whereHas('customer', function ($query) {
                $query->where('user_id', Auth::id());
            })
            // Regla de Oro: Evitar N+1. 
            // Cargamos el producto Y la relación 'media' de Spatie en una sola consulta.
            ->with(['items.product.media']) 
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Mostrar el detalle de una compra específica.
     */
    public function show(Order $order)
    {
        // Verificamos que el pedido pertenezca al usuario (Seguridad)
        if ($order->customer->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        // Blindamos también la vista de detalles contra el problema N+1
        $order->load(['items.product.media', 'shipment', 'payments']);

        return view('customer.orders.show', compact('order'));
    }
}