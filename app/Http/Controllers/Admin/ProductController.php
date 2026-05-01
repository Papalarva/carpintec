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
        $search = $request->query('search');
        $showTrashed = $request->boolean('trashed');

        $products = Product::query()
            ->when($search, fn ($q) => $q->search($search))
            ->when($showTrashed, fn ($q) => $q->onlyTrashed())
            ->with(['category', 'inventory'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(compact('search', 'showTrashed'));

        return view('admin.products.index', compact('products', 'search', 'showTrashed'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'sku'              => 'required|string|unique:products,sku',
            'name'             => 'required|string|max:255',
            'short_description'=> 'nullable|string',
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
            'images.*'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // máx 2MB
        ]);

        $product = Product::create($validated);

        // Inventario inicial
        if ($product->track_inventory) {
            $product->inventory()->create([
                'product_id'   => $product->id,
                'quantity'     => 0, // el stock real se maneja con movimientos
                'min_quantity' => $request->input('min_quantity', 0),
                'location'     => $request->input('location'),
            ]);
        }

        // Imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('product_images');
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $product->load('inventory');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'sku'              => ['required', 'string', Rule::unique('products')->ignore($product->id)],
            'name'             => 'required|string|max:255',
            'short_description'=> 'nullable|string',
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
        ]);

        $product->update(collect($validated)->except(['images', 'delete_images', 'min_quantity', 'location'])->toArray());

        // Sincronizar inventario
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

        // Imágenes nuevas
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $product->addMedia($image)->toMediaCollection('product_images');
            }
        }

        // Eliminar imágenes marcadas
        if ($request->has('delete_images')) {
            $product->media()->whereIn('id', $request->delete_images)->delete();
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $product->delete(); // soft delete
        return back()->with('success', 'Producto movido a papelera.');
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return back()->with('success', 'Producto restaurado.');
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete(); // elimina también imágenes y media
        return back()->with('success', 'Producto eliminado definitivamente.');
    }
}