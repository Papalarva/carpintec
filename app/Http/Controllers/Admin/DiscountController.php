<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountType;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->paginate(15);
        return view('admin.discounts.index', compact('discounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => ['required', Rule::enum(DiscountType::class)],
            'value'      => 'required|numeric|min:0',
            'starts_at'  => 'nullable|date',
            'ends_at'    => 'nullable|date|after_or_equal:starts_at',
            'is_active'  => 'boolean',
            'applies_to' => 'required|in:all,products,categories,customers',
            'product_ids'   => 'array|exists:products,id',
            'category_ids'  => 'array|exists:categories,id',
            'customer_ids'  => 'array|exists:customers,id',
        ]);

        $discount = Discount::create([
            'name'       => $validated['name'],
            'type'       => $validated['type'],
            'value'      => $validated['value'],
            'starts_at'  => $validated['starts_at'] ?? null,
            'ends_at'    => $validated['ends_at'] ?? null,
            'is_active'  => $request->boolean('is_active', true),
            'applies_to' => $validated['applies_to'],
        ]);

        // Sincronizar relaciones según type
        match ($validated['applies_to']) {
            'products'   => $discount->products()->sync($validated['product_ids'] ?? []),
            'categories' => $discount->categories()->sync($validated['category_ids'] ?? []),
            'customers'  => $discount->customers()->sync($validated['customer_ids'] ?? []),
            default      => null, // 'all' → sin relaciones específicas
        };

        return redirect()->route('admin.discounts.index')->with('success', 'Descuento creado.');
    }

    public function edit(Discount $discount)
    {
        $products   = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $customers  = Customer::with('user')->get()->sortBy('user.full_name');
        $types      = DiscountType::cases();
        $appliesOptions = ['all' => 'Todos los productos', 'products' => 'Productos específicos', 'categories' => 'Categorías específicas', 'customers' => 'Clientes específicos'];

        $discount->load('products', 'categories', 'customers');

        return view('admin.discounts.edit', compact('discount', 'products', 'categories', 'customers', 'types', 'appliesOptions'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => ['required', Rule::enum(DiscountType::class)],
            'value'      => 'required|numeric|min:0',
            'starts_at'  => 'nullable|date',
            'ends_at'    => 'nullable|date|after_or_equal:starts_at',
            'is_active'  => 'boolean',
            'applies_to' => 'required|in:all,products,categories,customers',
            'product_ids'   => 'array|exists:products,id',
            'category_ids'  => 'array|exists:categories,id',
            'customer_ids'  => 'array|exists:customers,id',
        ]);

        $discount->update([
            'name'       => $validated['name'],
            'type'       => $validated['type'],
            'value'      => $validated['value'],
            'starts_at'  => $validated['starts_at'] ?? null,
            'ends_at'    => $validated['ends_at'] ?? null,
            'is_active'  => $request->boolean('is_active'),
            'applies_to' => $validated['applies_to'],
        ]);

        // Re-sincronizar
        $discount->products()->sync([]);
        $discount->categories()->sync([]);
        $discount->customers()->sync([]);

        match ($validated['applies_to']) {
            'products'   => $discount->products()->sync($validated['product_ids'] ?? []),
            'categories' => $discount->categories()->sync($validated['category_ids'] ?? []),
            'customers'  => $discount->customers()->sync($validated['customer_ids'] ?? []),
            default      => null,
        };

        return redirect()->route('admin.discounts.index')->with('success', 'Descuento actualizado.');
    }

    public function create()
    {
        // 1. Instanciamos un descuento vacío para enviarlo a la vista y evitar errores de variable indefinida
        $discount   = new Discount(); 
        
        $products   = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $customers  = Customer::with('user')->get()->sortBy('user.full_name');
        $types      = DiscountType::cases();
        $appliesOptions = [
            'all' => 'Todos los productos', 
            'products' => 'Productos específicos', 
            'categories' => 'Categorías específicas', 
            'customers' => 'Clientes específicos'
        ];

        // Añadimos $discount al compact
        return view('admin.discounts.create', compact('discount', 'products', 'categories', 'customers', 'types', 'appliesOptions'));
    }
 

    public function destroy(Discount $discount)
    {
        try {
            $discount->delete();
            return back()->with('success', 'Descuento eliminado correctamente del sistema.');

        } catch (\Illuminate\Database\QueryException $e) {
            
            // 2. Blindamos contra la restricción de llave foránea de los cupones (coupons_discount_id_fkey)
            if ($e->getCode() == '23503') {
                return back()->with('error', 'No puedes eliminar este descuento porque ya tiene Cupones asociados. Te recomendamos simplemente desactivarlo.');
            }

            return back()->with('error', 'Ocurrió un error inesperado en la base de datos al intentar eliminar el descuento.');
        }
    }
}