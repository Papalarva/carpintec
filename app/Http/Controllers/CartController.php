<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartManager;
use Illuminate\Http\Request;
use Exception;

class CartController extends Controller
{
    public function __construct(protected CartManager $cart)
    {
    }

    public function index()
    {
        $items = $this->cart->getItems();
        $subtotal = $this->cart->getSubtotal();

        return view('cart.index', compact('items', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $quantity = $request->integer('quantity');

        // BLINDAJE ACUMULATIVO DIRECTO
        // Extraemos la validación aquí para que sea infalible.
        if ($product->track_inventory && $product->inventory) {
            $availableStock = $product->inventory->quantity;
            
            // Usamos las Colecciones de Laravel para buscar el producto en el carrito
            // soportando tanto Objetos (BD) como Arrays (Sesión).
            $currentQtyNode = collect($this->cart->getItems())->first(function ($item) use ($product) {
                $itemId = is_object($item) ? ($item->product_id ?? $item->product?->id ?? null) : ($item['product_id'] ?? $item['id'] ?? null);
                return (string) $itemId === (string) $product->id;
            });
            
            $qtyInCart = $currentQtyNode ? (is_object($currentQtyNode) ? $currentQtyNode->quantity : $currentQtyNode['quantity']) : 0;
            
            // Si la suma supera el stock, rechazamos la petición inmediatamente.
            if (($qtyInCart + $quantity) > $availableStock) {
                $faltantes = $availableStock - $qtyInCart;

                if ($faltantes <= 0) {
                    $mensaje = "Ya tienes todas las unidades disponibles en tu carrito ({$availableStock}).";
                } else {
                    $mensaje = "Solo puedes agregar {$faltantes} unidad(es) más. (Stock total: {$availableStock}).";
                }
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => $mensaje], 422);
                }
                return back()->with('error', $mensaje);
            }
        }

        try {
            $this->cart->addItem($product, $quantity);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Producto agregado al carrito.',
                    'count'   => $this->cart->getTotalCount(),
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Producto agregado al carrito.');

        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $newQuantity = $request->integer('quantity');

        // BLINDAJE DIRECTO AL ACTUALIZAR
        // Evita que peticiones maliciosas (Postman/Console) burlen a Alpine.js
        if ($product->track_inventory && $product->inventory) {
            $availableStock = $product->inventory->quantity;
            if ($newQuantity > $availableStock) {
                $mensaje = "Stock máximo alcanzado ({$availableStock} unidades).";
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['message' => $mensaje], 422);
                }
                return back()->with('error', $mensaje);
            }
        }

        try {
            $this->cart->updateQuantity($product->id, $newQuantity);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message'    => 'Carrito actualizado.',
                    'subtotal'   => number_format($this->cart->getSubtotal(), 2, '.', ''),
                    'totalItems' => $this->cart->getTotalCount(),
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Carrito actualizado.');

        } catch (Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove(Product $product)
    {
        $this->cart->removeItem($product->id);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'message' => 'Producto eliminado del carrito.',
                'subtotal' => number_format($this->cart->getSubtotal(), 2, '.', ''),
                'count' => $this->cart->getTotalCount(),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Producto eliminado.');
    }

    public function count()
    {
        return response()->json(['count' => $this->cart->getTotalCount()]);
    }
}