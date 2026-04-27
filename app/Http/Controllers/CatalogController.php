<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class CatalogController extends Controller
{
    /**
     * Muestra el catálogo de productos con filtros y categorías.
     */
    public function index(Request $request)
    {
        // === Categorías activas para el menú lateral (jerarquía) ===
        $categories = Category::active()
            ->with('children')            // subcategorías
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        // === Consulta base de productos activos ===
        $query = Product::active()
            ->with(['coverImage', 'category']);

        // Filtro por categoría (slug o UUID, aquí usaremos slug para URLs amigables)
        if ($slug = $request->input('category')) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                // Incluir también subcategorías si se desea; por ahora agarramos solo esa categoría exacta
                $query->where('category_id', $category->id);
            }
        }

        // Filtro por texto (nombre, descripción corta, SKU)
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filtro por rango de precio
        if ($min = $request->input('min_price')) {
            $query->where('price', '>=', (float) $min);
        }
        if ($max = $request->input('max_price')) {
            $query->where('price', '<=', (float) $max);
        }

        // Orden y paginación
        $products = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        // Pasar filtros actuales a la vista para rellenar campos
        return view('catalog.index', compact('categories', 'products', 'search', 'min', 'max', 'slug'));
    }
    public function show($slug)
    {
        $product = Product::with(['images', 'category.parent', 'inventory'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Productos relacionados (misma categoría, excluyendo el actual)
        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }

}