<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::select('products.*')->with(['category', 'inventory', 'media']);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('products.name', 'ilike', $searchTerm)
                    ->orWhere('products.sku', 'ilike', $searchTerm);
            });
        }

        $showTrashed = $request->boolean('trashed');
        if ($showTrashed) {
            $query->onlyTrashed();
        }

        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');

        $allowedSorts = ['sku', 'name', 'category_id', 'price', 'is_active', 'quantity'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

            if ($sort === 'quantity') {
                $query->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
                    ->orderBy('inventory.quantity', $direction);
            } else {
                $query->orderBy('products.' . $sort, $direction);
            }
        } else {
            $query->orderBy('products.created_at', 'desc');
        }

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
        $product->load(['inventory', 'media']);
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Producto movido a papelera.');
    }

    public function restore($id)
    {
        try {
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->restore();
            return back()->with('success', 'Producto restaurado exitosamente. Ya está visible en el catálogo.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'El producto no fue encontrado en la papelera.');
        } catch (\Exception $e) {
            return back()->with('error', 'Ocurrió un error inesperado al intentar restaurar el producto.');
        }
    }

    // Traducciones Centralizadas para este Módulo
    protected function validationMessages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe ser texto.',
            'max' => 'El campo :attribute no debe ser mayor a :max caracteres.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'min' => 'El campo :attribute debe ser al menos :min.',
            'boolean' => 'El campo :attribute debe ser verdadero o falso.',
            'exists' => 'El :attribute seleccionado no es válido.',
            'image' => 'El archivo debe ser una imagen.',
            'mimes' => 'La imagen debe ser de tipo: :values.',
            'images.*.max' => 'La imagen no debe pesar más de 2MB.',
            'sku.unique' => 'Ya existe un producto con este SKU (Código interno).',
            'slug.unique' => 'El nombre del producto genera un enlace que ya está en uso. Modifica ligeramente el nombre.',
            'category_id.required' => 'Debes seleccionar una categoría.',
        ];
    }

    public function store(Request $request)
    { 
        $request->merge([
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->has('is_active'),
            'track_inventory' => true,
            'is_customizable' => true,
        ]);

        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'sku'              => 'required|string|unique:products,sku',
            'name'             => 'required|string|max:255',
            'slug'             => 'required|string|max:255|unique:products,slug',
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string|max:5000',
            'materials'        => 'required|string|max:255',
            'dimensions'       => 'required|string|max:255',
            'weight_kg'        => 'required|numeric|min:0',
            'price'            => 'required|numeric|min:0',
            'cost'             => 'required|numeric|min:0',
            'is_active'        => 'boolean', 
            'track_inventory'  => 'boolean',
            'is_customizable'  => 'boolean',
            'location'         => 'nullable|string|max:255', // Nuevo campo en inventario
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_order'      => 'nullable|string',
        ], $this->validationMessages());

        try {
            DB::transaction(function () use ($validated, $request) {
                // Separar la data del producto de la data extraída
                $productData = collect($validated)->except(['images', 'location', 'image_order'])->toArray();
                $productData['track_inventory'] = true;
                $productData['is_customizable'] = true;
                $product = Product::create($productData);

                // Siempre crear registro de inventario con base al DDL
                $product->inventory()->create([
                    'product_id' => $product->id,
                    'quantity'   => 0,
                    'location'   => $request->input('location'),
                ]);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $product->addMedia($image)->toMediaCollection('product_images');
                    }
                }

                $this->syncProductImagesOrder($product, $request->input('image_order'));
            });

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto creado correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23505') {
                return back()->withInput()->with('error', 'Error de duplicidad: El SKU o Nombre ya existe.');
            }
            return back()->withInput()->with('error', 'Ocurrió un error en la base de datos al guardar el producto.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Product $product)
    {
        $request->merge([
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'is_active' => $request->has('is_active'),
            'track_inventory' => true,
            'is_customizable' => true,
        ]);

        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'sku'              => ['required', 'string', Rule::unique('products')->ignore($product->id)],
            'name'             => 'required|string|max:255',
            'slug'             => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'short_description' => 'nullable|string|max:500',
            'long_description' => 'nullable|string|max:5000',
            'materials'        => 'required|string|max:255',
            'dimensions'       => 'required|string|max:255',
            'weight_kg'        => 'required|numeric|min:0',
            'price'            => 'required|numeric|min:0',
            'cost'             => 'required|numeric|min:0',
            'is_active'        => 'boolean',
            'track_inventory'  => 'boolean',
            'is_customizable'  => 'boolean',
            'location'         => 'nullable|string|max:255',
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images'    => 'nullable|array',
            'delete_images.*'  => 'exists:media,id',
            'image_order'      => 'nullable|string',
        ], $this->validationMessages());

        try {
            DB::transaction(function () use ($validated, $request, $product) {
                $productData = collect($validated)->except(['images', 'delete_images', 'location', 'image_order'])->toArray();
                $productData['track_inventory'] = true;
                $productData['is_customizable'] = true;
                $product->update($productData);

                // Actualizar o crear registro de inventario y su ubicación
                $product->inventory()->updateOrCreate(
                    ['product_id' => $product->id],
                    ['location'   => $request->input('location')]
                );

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $product->addMedia($image)->toMediaCollection('product_images');
                    }
                }

                if ($request->has('delete_images')) {
                    $product->media()->whereIn('id', $request->delete_images)->delete();
                }

                $this->syncProductImagesOrder($product, $request->input('image_order'));
            });

            return redirect()->route('admin.products.index')
                ->with('success', 'Producto actualizado correctamente.');
        } catch (\Exception $e) {
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
            if ($e->getCode() == '23503') {
                return back()->with('error', 'No puedes eliminar este producto definitivamente porque está ligado a pedidos o carritos de clientes.');
            }
            return back()->with('error', 'Error al intentar eliminar el registro físico.');
        }
    }

    protected function syncProductImagesOrder(Product $product, ?string $encodedOrder): void
    {
        $mediaIds = $product->getMedia('product_images')->pluck('id')->all();

        if (empty($mediaIds)) {
            return;
        }

        $requestedOrder = json_decode($encodedOrder ?? '[]', true);
        $requestedOrder = is_array($requestedOrder) ? array_values(array_filter($requestedOrder)) : [];

        $orderedIds = array_values(array_intersect($requestedOrder, $mediaIds));
        $remainingIds = array_values(array_diff($mediaIds, $orderedIds));
        $finalOrder = array_merge($orderedIds, $remainingIds);

        Media::setNewOrder($finalOrder);
    }
}
