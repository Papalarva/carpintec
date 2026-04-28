<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartManager;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(protected CartManager $cart)
    {
        // El middleware de auth podría aplicarse en rutas, aquí no global.
    }

    /**
     * Muestra el contenido del carrito.
     */
    public function index()
    {
        $items = $this->cart->getItems();
        $subtotal = $this->cart->getSubtotal();

        return view('cart.index', compact('items', 'subtotal'));
    }

    /**
     * Agrega un producto al carrito.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'integer|min:1|max:99'
        ]);

        $quantity = $request->input('quantity', 1);
        $this->cart->addItem($product, $quantity);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Producto agregado al carrito.',
                'count' => $this->cart->getTotalCount(),
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Producto agregado al carrito.');
    }

    /**
     * Actualiza la cantidad de un producto en el carrito.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:99'
        ]);

        $this->cart->updateQuantity($product->id, $request->quantity);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Carrito actualizado.',
                'subtotal' => number_format($this->cart->getSubtotal(), 2),
                'count' => $this->cart->getTotalCount(),
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Carrito actualizado.');
    }

    /**
     * Elimina un producto del carrito.
     */
    public function remove(Product $product)
    {
        $this->cart->removeItem($product->id);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Producto eliminado del carrito.',
                'subtotal' => number_format($this->cart->getSubtotal(), 2),
                'count' => $this->cart->getTotalCount(),
            ]);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Producto eliminado del carrito.');
    }

    /**
     * Devuelve la cantidad actual de productos en el carrito (para navbar dinámico).
     */
    public function count()
    {
        return response()->json([
            'count' => $this->cart->getTotalCount(),
        ]);
    }
}