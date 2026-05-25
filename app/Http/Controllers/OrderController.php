<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::whereHas('customer', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['items.product.media'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->customer->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        $order->load(['items.product.media', 'shipment', 'payments']);

        return view('customer.orders.show', compact('order'));
    }
}
