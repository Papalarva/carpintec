<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta seleccionando explícitamente las columnas de products
        // Esto es crucial para que al hacer JOINs no se sobrescriban IDs.
        $query = Product::select('products.*')->with(['category', 'inventory']);

        // 2. Filtro de Búsqueda
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('products.name', 'ilike', $searchTerm)
                  ->orWhere('products.sku', 'ilike', $searchTerm);
            });
        }

        // 3. Filtro de Papelera
        $showTrashed = $request->boolean('trashed');
        if ($showTrashed) {
            $query->onlyTrashed();
        }

        // 4. Lógica de Ordenamiento Dinámico
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');

        // ¡NUEVO! Agregamos 'quantity' a los campos permitidos
        $allowedSorts = ['sku', 'name', 'category_id', 'price', 'is_active', 'quantity'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

            // Si el usuario quiere ordenar por stock, hacemos el JOIN con inventory
            if ($sort === 'quantity') {
                $query->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
                      ->orderBy('inventory.quantity', $direction);
            } else {
                // Para los demás campos, especificamos la tabla products
                $query->orderBy('products.' . $sort, $direction);
            }
        } else {
            // Orden por defecto
            $query->orderBy('products.created_at', 'desc');
        }

        // 5. Ejecución y Paginación
        $products = $query->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products', 'showTrashed'));
    }


    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('inventory');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function destroy(Product $product)
    {
        $product->delete(); // soft delete
        return back()->with('success', 'Producto movido a papelera.');
    }

    public function restore($id)
    {
        try {
            // Buscamos específicamente en la papelera (trashed)
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->restore();

            return back()->with('success', 'Producto restaurado exitosamente. Ya está visible en el catálogo.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Si alguien intenta restaurar un ID que no existe
            return back()->with('error', 'El producto no fue encontrado en la papelera.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado al intentar restaurar el producto.');
        }
    }

    public function store(Request $request)
    {
        // 1. Inyectamos los booleanos y generamos el slug ANTES de validar
        $request->merge([
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->has('is_active'),
            'is_customizable' => $request->has('is_customizable'),
            'track_inventory' => $request->has('track_inventory'),
        ]);

        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'sku'              => 'required|string|unique:products,sku',
            'name'             => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:products,slug',
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'materials'        => 'nullable|string',
            'dimensions'       => 'nullable|string',
            'weight_kg'        => 'nullable|numeric|min:0',
            'price'            => 'required|numeric|min:0',
            'cost'             => 'nullable|numeric|min:0',
            'is_active'        => 'boolean',
            'is_customizable'  => 'boolean',
            'track_inventory'  => 'boolean',
            'min_quantity'     => 'nullable|integer|min:0',
            'location'         => 'nullable|string',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'sku.unique' => 'Ya existe un producto con este SKU (Código interno).',
            'slug.unique' => 'El nombre del producto genera un enlace (slug) que ya está en uso.',
            'category_id.required' => 'Debes seleccionar una categoría.',
            'name.required' => 'El nombre del producto es obligatorio.',
            'price.required' => 'El precio es obligatorio.',
        ]);

        try {
            // Quitamos lo que no pertenece a la tabla products directamente
            $productData = collect($validated)->except(['images', 'min_quantity', 'location'])->toArray();
            $product = Product::create($productData);

            // Inventario inicial
            if ($product->track_inventory) {
                $product->inventory()->create([
                    'product_id'   => $product->id,
                    'quantity'     => 0,
                    'min_quantity' => $request->input('min_quantity', 0),
                    'location'     => $request->input('location'),
                ]);
            }

            // Imágenes usando Spatie Media Library
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->addMedia($image)->toMediaCollection('product_images');
                }
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto creado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23505') {
                return back()->withInput()->with('error', 'Error de duplicidad: El SKU o Nombre ya existe.');
            }
            return back()->withInput()->with('error', 'Ocurrió un error en la base de datos al guardar el producto.');
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->has('is_active'),
            'is_customizable' => $request->has('is_customizable'),
            'track_inventory' => $request->has('track_inventory'),
        ]);

        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'sku'              => ['required', 'string', Rule::unique('products')->ignore($product->id)],
            'name'             => 'required|string|max:255',
            'slug'             => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'short_description' => 'nullable|string',
            'long_description' => 'nullable|string',
            'materials'        => 'nullable|string',
            'dimensions'       => 'nullable|string',
            'weight_kg'        => 'nullable|numeric|min:0',
            'price'            => 'required|numeric|min:0',
            'cost'             => 'nullable|numeric|min:0',
            'is_active'        => 'boolean',
            'is_customizable'  => 'boolean',
            'track_inventory'  => 'boolean',
            'min_quantity'     => 'nullable|integer|min:0',
            'location'         => 'nullable|string',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images'    => 'nullable|array',
            'delete_images.*'  => 'exists:media,id',
        ], [
            'sku.unique' => 'Ya existe otro producto con este SKU.',
            'slug.unique' => 'El nombre modificado genera un enlace que ya pertenece a otro producto.',
        ]);

        try {
            $productData = collect($validated)->except(['images', 'delete_images', 'min_quantity', 'location'])->toArray();
            $product->update($productData);

            if ($product->track_inventory) {
                $product->inventory()->updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'min_quantity' => $request->input('min_quantity', 0),
                        'location'     => $request->input('location'),
                    ]
                );
            } else {
                $product->inventory()->delete();
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $product->addMedia($image)->toMediaCollection('product_images');
                }
            }

            if ($request->has('delete_images')) {
                $product->media()->whereIn('id', $request->delete_images)->delete();
            }

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto actualizado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withInput()->with('error', 'Ocurrió un error inesperado al actualizar el producto.');
        }
    }

    public function forceDelete($id)
    {
        try {
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->forceDelete();

            return back()->with('success', 'Producto eliminado definitivamente de la base de datos.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Proteger contra eliminación de productos que ya están en Carritos u Órdenes (llave foránea)
            if ($e->getCode() == '23503') {
                return back()->with('error', 'No puedes eliminar este producto definitivamente porque está ligado a pedidos o carritos de clientes.');
            }
            return back()->with('error', 'Error al intentar eliminar el registro físico.');
        }
    }
}
