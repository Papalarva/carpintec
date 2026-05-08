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
        $filter = $request->query('filter'); // Nuevo filtro

        // KPIs Rápidos para el Dashboard de Inventario
        $totalTracked = Product::where('track_inventory', true)->count();
        $lowStockCount = Product::where('track_inventory', true)
            ->whereHas('inventory', function($q) {
                $q->whereColumn('quantity', '<=', 'min_quantity');
            })->count();

        $products = Product::with('inventory')
            ->where('track_inventory', true)
            ->when($search, fn ($q) =>
                $q->where(fn($sq) => 
                    $sq->where('name', 'ilike', "%{$search}%")
                       ->orWhere('sku', 'ilike', "%{$search}%")
                )
            )
            ->when($filter === 'low_stock', fn ($q) =>
                $q->whereHas('inventory', fn($iq) => $iq->whereColumn('quantity', '<=', 'min_quantity'))
            )
            ->orderBy('name')
            ->paginate(20)
            ->appends(['search' => $search, 'filter' => $filter]);

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
        // Ahora permitimos cantidad 0, por si solo quieren cambiar la ubicación o el mínimo
        $validated = $request->validate([
            'quantity'     => 'required|integer', 
            'reference'    => 'nullable|string|max:255',
            'min_quantity' => 'required|integer|min:0',
            'location'     => 'nullable|string|max:255',
        ]);

        try {
            // INICIA TRANSACCIÓN DE BASE DE DATOS (Todo o Nada)
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

            // Solo registramos el movimiento histórico si de verdad sumaron o restaron piezas
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

            // Actualizamos piezas, stock mínimo y ubicación al mismo tiempo
            $inventory->update([
                'quantity'     => $newQuantity,
                'min_quantity' => $validated['min_quantity'],
                'location'     => $validated['location'],
            ]);

            // SI TODO SALIÓ BIEN, GUARDAMOS EN POSTGRESQL
            DB::commit();

            return redirect()->route('admin.inventory.index')
                             ->with('success', 'Ficha de inventario actualizada correctamente.');

        } catch (\Exception $e) {
            // SI ALGO FALLA, REVERTIMOS TODO PARA NO CORROMPER EL INVENTARIO
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error crítico al procesar el inventario.');
        }
    }
}