<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- Nombre -->
    <div class="md:col-span-1">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Categoría</label>
        <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
               class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors" placeholder="Ej. Comedores">
        
        @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        @error('slug') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Categoría Padre -->
    <div class="md:col-span-1">
        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría Padre (Opcional)</label>
        <select name="parent_id" id="parent_id" class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
            <option value="">Ninguna (Es categoría principal)</option>
            @foreach($parents ?? [] as $parent)
                <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id ?? '') == $parent->id) ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Descripción (Ocupa ambas columnas) -->
    <div class="md:col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
        <textarea name="description" id="description" rows="3"
                  class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors" placeholder="Breve descripción de los productos en esta categoría...">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

    <!-- Orden y Estado -->
    <div class="md:col-span-1 flex items-center gap-6">
        <div class="w-1/2">
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Orden de visualización</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
        </div>
        
        <div class="w-1/2 flex items-center mt-6">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-[#C15C3D] focus:ring-[#C15C3D] bg-gray-50">
            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Categoría Activa</label>
        </div>
    </div>
</div>