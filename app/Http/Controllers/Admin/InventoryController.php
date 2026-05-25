<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $filter = $request->query('filter');
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');
        $totalTracked = Product::where('track_inventory', true)->count();
        $lowStockCount = Product::where('track_inventory', true)
            ->whereHas('inventory', function ($q) {
                $q->whereColumn('quantity', '<=', 'min_quantity');
            })->count();

        $query = Product::select('products.*')
            ->with('inventory')
            ->where('track_inventory', true);

        if ($search) {
            $query->where(function ($sq) use ($search) {
                $sq->where('products.name', 'ilike', "%{$search}%")
                    ->orWhere('products.sku', 'ilike', "%{$search}%");
            });
        }

        if ($filter === 'low_stock') {
            $query->whereHas('inventory', fn($iq) => $iq->whereColumn('quantity', '<=', 'min_quantity'));
        }

        $allowedSorts = ['sku', 'name', 'quantity', 'min_quantity', 'location'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

            if (in_array($sort, ['quantity', 'min_quantity', 'location'])) {
                $query->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
                    ->orderBy("inventory.{$sort}", $direction);
            } else {
                $query->orderBy("products.{$sort}", $direction);
            }
        } else {
            $query->orderBy('products.name', 'asc');
        }

        $products = $query->paginate(20)->withQueryString();

        return view('admin.inventory.index', compact('products', 'search', 'filter', 'totalTracked', 'lowStockCount'));
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
            'quantity'     => 'required|integer',
            'reference'    => 'nullable|string|max:255',
            'min_quantity' => 'required|integer|min:0',
            'location'     => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $inventory = \App\Models\Inventory::firstOrCreate(
                ['product_id' => $product->id],
                ['quantity' => 0, 'min_quantity' => 0, 'location' => 'Por definir']
            );

            $adjustment = (int) $validated['quantity'];
            $newQuantity = $inventory->quantity + $adjustment;

            if ($newQuantity < 0) {
                return back()->withInput()->with('error', 'El ajuste resulta en stock negativo. Operación cancelada.');
            }

            if ($adjustment !== 0) {
                InventoryMovement::create([
                    'product_id'         => $product->id,
                    'movement_type'      => 'adjustment', // Valor según DDL
                    'quantity'           => $adjustment,
                    'resulting_quantity' => $newQuantity,
                    'reference'          => $validated['reference'] ?? 'Ajuste manual (Administrador)',
                    'user_id'            => Auth::id(),
                ]);
            }

            $inventory->update([
                'quantity'     => $newQuantity,
                'min_quantity' => $validated['min_quantity'],
                'location'     => $validated['location'],
            ]);

            DB::commit();

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Ficha de inventario actualizada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error crítico al procesar el inventario.');
        }
    }
}
