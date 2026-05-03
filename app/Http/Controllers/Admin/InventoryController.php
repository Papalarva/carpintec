<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $products = Product::with('inventory')
            ->when($search, fn ($q) =>
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('sku', 'ilike', "%{$search}%")
            )
            ->where('track_inventory', true) // solo los que controlan inventario
            ->orderBy('name')
            ->paginate(20)
            ->appends(['search' => $search]);

        return view('admin.inventory.index', compact('products', 'search'));
    }

    public function showMovements(Product $product)
    {
        $movements = $product->inventoryMovements()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.inventory.movements', compact('product', 'movements'));
    }

    public function createAdjustment(Product $product)
    {
        $product->load('inventory');
        return view('admin.inventory.adjust', compact('product'));
    }

    public function storeAdjustment(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity'  => 'required|integer|not_in:0',
            'reference' => 'nullable|string|max:255',
        ]);

        // 🔥 LA SOLUCIÓN: Si no hay inventario, lo inicializamos en 0 en automático
        $inventory = \App\Models\Inventory::firstOrCreate(
            ['product_id' => $product->id],
            ['quantity' => 0, 'min_quantity' => 0, 'location' => 'Por definir']
        );

        $adjustment = (int) $validated['quantity'];
        $newQuantity = $inventory->quantity + $adjustment;

        if ($newQuantity < 0) {
            return back()->with('error', 'El ajuste resulta en stock negativo.');
        }

        // Creamos el historial del movimiento
        InventoryMovement::create([
            'product_id'         => $product->id,
            'movement_type'      => InventoryMovement::TYPE_ADJUSTMENT,
            'quantity'           => $adjustment,
            'resulting_quantity' => $newQuantity,
            'reference'          => $validated['reference'] ?? 'Ajuste manual (Inicialización)',
            'user_id'            => Auth::id(),
        ]);

        // Actualizamos la cantidad (ya sea que existía antes o la acabamos de crear)
        $inventory->update(['quantity' => $newQuantity]);

        return redirect()->route('admin.inventory.index')
                         ->with('success', 'Stock ajustado correctamente.');
    }
}