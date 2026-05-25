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
        $query = Category::select('categories.*')->with('parent');
        $search = $request->query('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('categories.name', 'ilike', "%{$search}%")
                    ->orWhere('categories.description', 'ilike', "%{$search}%");
            });
        }
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'asc');

        $allowedSorts = ['name', 'sort_order', 'is_active', 'parent_name'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

            if ($sort === 'parent_name') {
                $query->leftJoin('categories as parents', 'categories.parent_id', '=', 'parents.id')
                    ->orderBy('parents.name', $direction);
            } else {
                $query->orderBy('categories.' . $sort, $direction);
            }
        } else {
            $query->orderBy('categories.sort_order', 'asc')->orderBy('categories.name', 'asc');
        }

        $categories = $query->paginate(15)->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    public function create()
    {
        $parents = Category::orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'slug' => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'slug.unique' => 'Ya existe una categoría similar. El nombre ingresado genera un identificador duplicado.',
        ]);

        try {
            Category::create($validated);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Categoría creada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // 23505 = Unique violation en PostgreSQL
            if ($e->getCode() == '23505') {
                return back()->withInput()->with('error', 'Error de duplicidad: Ya existe un registro idéntico en el sistema.');
            }

            return back()->withInput()->with('error', 'Ocurrió un error inesperado al intentar guardar la categoría.');
        }
    }

    public function update(Request $request, Category $category)
    {
        $request->merge([
            'slug' => Str::slug($request->name),
            'is_active' => $request->has('is_active'),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                \Illuminate\Validation\Rule::notIn([$category->id])
            ],
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'slug.unique' => 'Ya existe otra categoría con un nombre similar.',
            'parent_id.not_in' => 'Una categoría no puede ser subcategoría de sí misma.',
        ]);

        try {
            $category->update($validated);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Categoría actualizada correctamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '23505') {
                return back()->withInput()->with('error', 'Error de duplicidad al actualizar la base de datos.');
            }
            return back()->withInput()->with('error', 'Ocurrió un error inesperado al actualizar la categoría.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'Categoría movida a la papelera.');
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == '23503') {
                return redirect()->route('admin.categories.index')
                    ->with('error', 'No se puede eliminar esta categoría porque aún tiene productos o subcategorías asociadas. Por favor, reasígnalos primero.');
            }

            return redirect()->route('admin.categories.index')
                ->with('error', 'Ocurrió un error en la base de datos al intentar eliminar el registro.');
        }
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return back()->with('success', 'Categoría restaurada correctamente.');
    }
}
