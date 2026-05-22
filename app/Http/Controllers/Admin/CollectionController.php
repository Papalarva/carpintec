<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');

        // withCount crea automáticamente la columna virtual 'products_count'
        $query = Collection::withCount('products');

        // 1. Filtro de Búsqueda
        if ($search) {
            $query->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
        }

        // 2. Ordenamiento Dinámico
        $allowedSorts = ['name', 'products_count', 'is_active'];
        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sort, $direction);
        } else {
            // Orden por defecto
            $query->latest();
        }

        // 3. Paginación preservando parámetros
        $collections = $query->paginate(15)->appends(compact('search', 'sort', 'direction'));

        return view('admin.collections.index', compact('collections', 'search'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('admin.collections.create', compact('products'));
    }

    public function destroy(Collection $collection)
    {
        try {
            $collection->delete();
            return redirect()->route('admin.collections.index')
                             ->with('success', 'Colección eliminada.');
        } catch (\Exception $e) {
            return redirect()->route('admin.collections.index')
                             ->with('error', 'No se pudo eliminar la colección.');
        }
    }

    public function edit(Collection $collection)
    {
        // Traemos todos los productos activos y cargamos los IDs de los productos ya vinculados
        $products = Product::where('is_active', true)->get();
        $collectionProducts = $collection->products->pluck('id')->toArray();
        
        return view('admin.collections.edit', compact('collection', 'products', 'collectionProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name',
            'description' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id' // Validamos que los IDs no estén alterados
        ], [
            'name.unique' => 'Ya existe una colección con este nombre.',
            'products.required' => 'Debes seleccionar al menos un producto para la colección.'
        ]);

        try {
            $collection = Collection::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'is_active' => $request->has('is_active'),
            ]);

            $collection->products()->sync($request->products);

            return redirect()->route('admin.collections.index')
                             ->with('success', 'Colección creada exitosamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            return back()->withInput()->with('error', 'Ocurrió un error en la base de datos al guardar la colección.');
        }
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:collections,name,' . $collection->id,
            'description' => 'nullable|string',
            'products' => 'required|array',
            'products.*' => 'exists:products,id'
        ], [
            'name.unique' => 'Ya existe otra colección con este nombre.',
            'products.required' => 'Debes seleccionar al menos un producto para la colección.'
        ]);

        try {
            $collection->update([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'description' => $validated['description'],
                'is_active' => $request->has('is_active'),
            ]);

            $collection->products()->sync($request->products);

            return redirect()->route('admin.collections.index')
                             ->with('success', 'Colección actualizada exitosamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23505') { // Violación Unique
                return back()->withInput()->with('error', 'Error de duplicidad detectado.');
            }
            return back()->withInput()->with('error', 'No se pudo actualizar la colección debido a un error interno.');
        }
    }
}