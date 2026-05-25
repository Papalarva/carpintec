<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{ 
    public function index(Request $request)
    { 
        $search = $request->input('search');
        $min = $request->input('min_price');
        $max = $request->input('max_price');
        $slug = $request->input('category'); 

        // Optimización: Solo cargamos el árbol de categorías activas
        $categories = Category::where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get(); 

        $query = Product::query()
            ->where('is_active', true)
            ->whereHas('category', function ($q) {
                $q->where('is_active', true);
            }); 

        // 👇 CORRECCIÓN: Filtro jerárquico de Categorías 👇
        if ($slug) {
            $category = Category::where('slug', $slug)->first();
            
            if ($category) {
                // Recolectamos el ID actual y los IDs de todos sus hijos directos
                $categoryIds = Category::where('parent_id', $category->id)
                    ->where('is_active', true)
                    ->pluck('id')
                    ->push($category->id); // Agregamos el ID del padre a la colección
                
                $query->whereIn('category_id', $categoryIds);
            }
        } 

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('short_description', 'ilike', "%{$search}%")
                  ->orWhere('sku', 'ilike', "%{$search}%");
            });
        } 

        if ($min) {
            $query->where('price', '>=', (float) $min);
        }
        if ($max) {
            $query->where('price', '<=', (float) $max);
        }

        $products = $query->with('category', 'media')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends($request->query()); // Prevención: query() es más seguro que all()
            
        return view('catalog.index', compact('products', 'categories', 'search', 'min', 'max'));
    }
    
    public function show($slug)
    {
        $product = Product::with(['media', 'category.parent', 'inventory'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail(); 

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }
}