<div class="mb-4">
    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
    <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
           class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
    @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
    <textarea name="description" id="description" rows="3"
              class="mt-1 block w-full rounded border-gray-300 shadow-sm">{{ old('description', $category->description ?? '') }}</textarea>
</div>

<div class="mb-4">
    <label for="parent_id" class="block text-sm font-medium text-gray-700">Categoría Padre</label>
    <select name="parent_id" id="parent_id" class="mt-1 block w-full rounded border-gray-300">
        <option value="">Ninguna</option>
        @foreach($parents ?? [] as $parent)
            <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id ?? '') == $parent->id) ? 'selected' : '' }}>
                {{ $parent->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="sort_order" class="block text-sm font-medium text-gray-700">Orden</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
               class="mt-1 block w-full rounded border-gray-300">
    </div>
    <div class="flex items-center mt-5">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
        <label for="is_active" class="ml-2 text-sm text-gray-600">Activo</label>
    </div>
</div>