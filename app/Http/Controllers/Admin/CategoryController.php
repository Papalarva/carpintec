<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $categories = Category::query()
            ->when($search, fn ($q) =>
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%")
            )
            ->with('parent')
            ->orderBy('sort_order')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        // 1. Validar antes de hacer cualquier cosa
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ], [
            // Mensajes amigables y personalizados
            'name.unique' => 'Ya existe una categoría registrada con este nombre. Por favor, elige otro.',
            'name.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        // 2. Aquí va tu lógica de creación (asumiendo que generas el slug a partir del nombre)
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        
        // Si no mandan el checkbox, forzamos a 0
        $validated['is_active'] = $request->has('is_active'); 

        Category::create($validated);

        // 3. Redirigir con un mensaje de éxito
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría creada exitosamente.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            // Ignoramos el ID de la categoría actual para que permita guardar si no cambian el nombre
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
        ], [
            'name.unique' => 'Ya existe otra categoría registrada con este nombre.',
            'name.required' => 'El nombre de la categoría es obligatorio.',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría actualizada correctamente.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }


    public function destroy(Category $category)
    {
        try {
            // Intentamos eliminar la categoría
            $category->delete();

            // Si funciona, mostramos el Toast verde
            return redirect()->route('admin.categories.index')
                             ->with('success', 'Categoría eliminada correctamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            
            // El código 23503 en PostgreSQL significa "Violación de Llave Foránea"
            if ($e->getCode() == '23503') {
                return redirect()->route('admin.categories.index')
                                 ->with('error', 'No se puede eliminar esta categoría porque aún tiene productos o subcategorías asociadas. Por favor, reasígnalos primero.');
            }

            // Si es otro error de base de datos inesperado
            return redirect()->route('admin.categories.index')
                             ->with('error', 'Ocurrió un error en la base de datos al intentar eliminar el registro.');
        }
    }
}