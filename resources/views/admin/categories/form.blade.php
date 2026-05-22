<div class="grid grid-cols-1 md:grid-cols-12 gap-x-8 gap-y-7">
    
    {{-- 1. Nombre de la Categoría (Mayor peso visual - 8 columnas) --}}
    <div class="md:col-span-8">
        <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Nombre de la Categoría</label>
        <input type="text" name="name" id="name" value="{{ old('name', $category->name ?? '') }}"
               class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans" placeholder="Ej. Comedores">
        
        @error('name') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
        @error('slug') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
    </div>

    {{-- 2. Categoría Padre (Menor peso visual - 4 columnas) --}}
    <div class="md:col-span-4">
        <label for="parent_id" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Categoría Padre (Opcional)</label>
        <select name="parent_id" id="parent_id" class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans cursor-pointer">
            <option value="">Ninguna (Principal)</option>
            @foreach($parents ?? [] as $parent)
                <option value="{{ $parent->id }}" {{ (old('parent_id', $category->parent_id ?? '') == $parent->id) ? 'selected' : '' }}>
                    {{ $parent->name }}
                </option>
            @endforeach
        </select>
        @error('parent_id') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
    </div>

    {{-- 3. Descripción (Fila Panorámica - 12 columnas) --}}
    <div class="md:col-span-12">
        <label for="description" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Descripción</label>
        <textarea name="description" id="description" rows="4"
                  class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans resize-none" placeholder="Breve descripción de los productos en esta categoría...">{{ old('description', $category->description ?? '') }}</textarea>
        @error('description') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
    </div>

    {{-- 4. Ajustes Técnicos (Distribución asimétrica invertida inferior) --}}
    <div class="md:col-span-4">
        <label for="sort_order" class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Orden (Visualización)</label>
        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
               class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans font-mono">
        @error('sort_order') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p> @enderror
    </div>
    
    <div class="md:col-span-8 flex items-center md:mt-6 bg-gray-50/30 rounded-xl px-4 border border-transparent hover:border-gray-100 transition-colors">
        <label class="flex items-center cursor-pointer group w-full py-3">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-white transition-colors cursor-pointer shadow-sm">
            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Categoría Activa en Catálogo</span>
        </label>
    </div>
</div>