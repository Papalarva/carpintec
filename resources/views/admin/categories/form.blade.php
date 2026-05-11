<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <div class="md:col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Nombre de la Categoría</label>
        <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
               class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans" placeholder="Ej. Comedores">
        
        @error('name') <p class="text-rose-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
        @error('slug') <p class="text-rose-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1">
        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Categoría Padre (Opcional)</label>
        <select name="parent_id" id="parent_id" class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
            <option value="">Ninguna (Es categoría principal)</option>
            @foreach($parents ?? [] as $parent)
                <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id ?? '') == $parent->id) ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id') <p class="text-rose-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Descripción</label>
        <textarea name="description" id="description" rows="4"
                  class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans" placeholder="Breve descripción de los productos en esta categoría...">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description') <p class="text-rose-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-1 flex flex-col sm:flex-row items-start sm:items-center gap-8">
        <div class="w-full sm:w-1/2">
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Orden (Visualización)</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                   class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
            @error('sort_order') <p class="text-rose-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
        </div>
        
        <div class="w-full sm:w-1/2 flex items-center sm:mt-8">
            <label class="flex items-center cursor-pointer group">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-800 bg-gray-50 transition-colors">
                <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 font-sans">Categoría Activa</span>
            </label>
        </div>
    </div>
</div>