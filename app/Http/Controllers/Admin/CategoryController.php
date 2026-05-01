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
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id'   => 'nullable|exists:categories,id',
            'sort_order'  => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        $parents = Category::where('id', '!=', $category->id)->orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => 'nullable|string',
            'parent_id'   => ['nullable', 'exists:categories,id', Rule::notIn([$category->id])],
            'sort_order'  => 'integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $category->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'parent_id'   => $validated['parent_id'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        // Solo se permite si no tiene productos asociados (opcional)
        if ($category->products()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría con productos.');
        }
        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}