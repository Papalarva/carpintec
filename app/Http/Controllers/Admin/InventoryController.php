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

        // KPIs
        $totalTracked = Product::where('track_inventory', true)->count();
        $lowStockCount = Product::where('track_inventory', true)
            ->whereHas('inventory', function($q) {
                $q->whereColumn('quantity', '<=', 'min_quantity');
            })->count();

        // 1. Consulta Base con select('products.*') para evitar colisión de IDs al hacer JOIN
        $query = Product::select('products.*')
            ->with('inventory')
            ->where('track_inventory', true);

        // 2. Búsqueda
        if ($search) {
            $query->where(function($sq) use ($search) {
                $sq->where('products.name', 'ilike', "%{$search}%")
                   ->orWhere('products.sku', 'ilike', "%{$search}%");
            });
        }

        // 3. Filtro Bajo Stock
        if ($filter === 'low_stock') {
            $query->whereHas('inventory', fn($iq) => $iq->whereColumn('quantity', '<=', 'min_quantity'));
        }

        // 4. Ordenamiento Dinámico
        $allowedSorts = ['sku', 'name', 'quantity', 'min_quantity', 'location'];
        
        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            
            // Si la columna pertenece a la tabla inventory, hacemos el JOIN al vuelo
            if (in_array($sort, ['quantity', 'min_quantity', 'location'])) {
                $query->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
                      ->orderBy("inventory.{$sort}", $direction);
            } else {
                // Columnas propias de products
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
