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
            // Eliminamos 'status_id' del array de carga ansiosa
            ->with(['items.product']) 
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

        // Eliminamos 'status_id' del array de carga ansiosa
        $order->load(['items.product', 'shipment', 'payments']);

        return view('customer.orders.show', compact('order'));
    }
}