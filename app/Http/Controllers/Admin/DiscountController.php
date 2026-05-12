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
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'desc');

        $query = Discount::query();

        // 1. Filtro de Búsqueda
        if ($search) {
            $query->where('name', 'ilike', "%{$search}%");
        }

        // 2. Ordenamiento Dinámico Segurizado
        $allowedSorts = ['name', 'type', 'value', 'applies_to', 'starts_at', 'ends_at', 'is_active'];
        
        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sort, $direction);
        } else {
            $query->latest();
        }

        // 3. Paginación preservando URL
        $discounts = $query->paginate(15)->withQueryString();

        return view('admin.discounts.index', compact('discounts', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => ['required', Rule::enum(DiscountType::class)],
            
            // VALIDACIÓN ESTRICTA Y CONDICIONAL
            'value'      => [
                'required',
                'numeric',
                'min:0.01', // No permitimos descuentos en cero
                function ($attribute, $value, $fail) use ($request) {
                    // Si es porcentaje, el valor jamás debe superar 100
                    if ($request->input('type') === \App\Enums\DiscountType::PERCENTAGE->value && $value > 100) {
                        $fail('Un descuento porcentual no puede ser mayor al 100%.');
                    }
                }
            ],
            
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

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => ['required', Rule::enum(DiscountType::class)],
            
            // VALIDACIÓN ESTRICTA Y CONDICIONAL
            'value'      => [
                'required',
                'numeric',
                'min:0.01', // No permitimos descuentos en cero
                function ($attribute, $value, $fail) use ($request) {
                    // Si es porcentaje, el valor jamás debe superar 100
                    if ($request->input('type') === \App\Enums\DiscountType::PERCENTAGE->value && $value > 100) {
                        $fail('Un descuento porcentual no puede ser mayor al 100%.');
                    }
                }
            ],
            
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

    public function destroy(Discount $discount)
    {
        try {
            // 1. Eliminamos manualmente los cupones asociados para sortear la restricción de PostgreSQL
            \Illuminate\Support\Facades\DB::table('coupons')->where('discount_id', $discount->id)->delete();
            
            // 2. Ahora sí, eliminamos el descuento de forma segura
            $discount->delete();
            
            return back()->with('success', 'El descuento y todos sus cupones asociados fueron eliminados de la tienda.');

        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Ocurrió un error inesperado en la base de datos al intentar eliminar el descuento.');
        }
    }

    public function create()
    {
        $discount   = new Discount(); 
        $products   = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        // CORRECCIÓN: Traemos al usuario incluso si está en la papelera para evitar el error "null"
        $customers  = Customer::with(['user' => function($query) {
            $query->withTrashed();
        }])->get()->sortBy(function($customer) {
            return $customer->user ? $customer->user->first_name : 'Z'; // Z para mandarlos al final
        });
        
        $types      = DiscountType::cases();
        $appliesOptions = [
            'all' => 'Todos los productos', 
            'products' => 'Productos específicos', 
            'categories' => 'Categorías específicas', 
            'customers' => 'Clientes específicos'
        ];

        return view('admin.discounts.create', compact('discount', 'products', 'categories', 'customers', 'types', 'appliesOptions'));
    }

    public function edit(Discount $discount)
    {
        $products   = Product::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        
        $customers  = Customer::with(['user' => function($query) {
            $query->withTrashed();
        }])->get()->sortBy(function($customer) {
            return $customer->user ? $customer->user->first_name : 'Z';
        });
        
        $types      = DiscountType::cases();
        $appliesOptions = [
            'all'        => 'Todos los productos', 
            'products'   => 'Productos específicos', 
            'categories' => 'Categorías específicas', 
            'customers'  => 'Clientes específicos' // <--- ¡AQUÍ ESTABA EL ERROR! (Faltaba la 's')
        ];

        $discount->load('products', 'categories', 'customers');

        return view('admin.discounts.edit', compact('discount', 'products', 'categories', 'customers', 'types', 'appliesOptions'));
    }
}