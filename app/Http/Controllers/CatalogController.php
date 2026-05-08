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
        // 1. Recolectamos las variables de la petición
        $search = $request->input('search');
        $min = $request->input('min_price');
        $max = $request->input('max_price');
        $slug = $request->input('category');

        // 2. Categorías activas para el menú lateral
        // (Usamos where('is_active', true) en lugar de active() por si no tienes el scope configurado)
        $categories = Category::where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true);
            }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        // 3. Consulta base: Productos activos y con categoría activa
        $query = Product::query()
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            });

        // --- APLICACIÓN DE FILTROS ---

        // Filtro por categoría (slug)
        if ($slug) {
            $category = Category::where('slug', $slug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Filtro por texto (Búsqueda en nombre, descripción o SKU)
        if ($search) {
            $query->where(function($q) use ($search) {
                // Usamos 'ilike' para PostgreSQL (ignora mayúsculas/minúsculas y acentos si la BD está bien configurada)
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('short_description', 'ilike', "%{$search}%")
                  ->orWhere('sku', 'ilike', "%{$search}%");
            });
        }

        // Filtro por rango de precio
        if ($min) {
            $query->where('price', '>=', (float) $min);
        }
        if ($max) {
            $query->where('price', '<=', (float) $max);
        }

        // 4. Ejecución final con relaciones y paginación
        $products = $query->with('category', 'media')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends($request->all()); // Esto es VITAL: mantiene los filtros (?search=mesa&min_price=100) al cambiar a la página 2

        // 5. Enviamos TODAS las variables a la vista
        return view('catalog.index', compact('products', 'categories', 'search', 'min', 'max'));
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