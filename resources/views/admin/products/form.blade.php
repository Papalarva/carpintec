@csrf

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h3 class="text-xl font-medium text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Información Básica</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div>
            <label for="sku" class="block text-sm font-medium text-gray-700 mb-2 font-sans">SKU (Código Interno)</label>
            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" required
                   class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans" placeholder="Ej. MES-001">
            @error('sku') <p class="text-rose-600 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Categoría</label>
            <select name="category_id" id="category_id" required
                    class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
                <option value="">Selecciona una categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-rose-600 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Nombre de la Pieza</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required
                   class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans" placeholder="Ej. Mesa Comedor Parota">
            @error('name') <p class="text-rose-600 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Precio Público ($)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" required
                       class="block w-full pl-8 rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
            </div>
            @error('price') <p class="text-rose-600 text-xs mt-2">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="cost" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Costo de Producción ($)</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">$</span>
                </div>
                <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost', $product->cost ?? '') }}"
                       class="block w-full pl-8 rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h3 class="text-xl font-medium text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Especificaciones Físicas</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
        <div>
            <label for="materials" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Materiales (Maderas/Herrería)</label>
            <input type="text" name="materials" id="materials" value="{{ old('materials', $product->materials ?? '') }}"
                   class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
        </div>
        <div>
            <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Dimensiones</label>
            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $product->dimensions ?? '') }}"
                   class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans" placeholder="Ej. 120x60x75 cm">
        </div>
        <div>
            <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Peso Estimado (kg)</label>
            <input type="number" step="0.01" name="weight_kg" id="weight_kg" value="{{ old('weight_kg', $product->weight_kg ?? '') }}"
                   class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">
        </div>
    </div>

    <div class="mb-6">
        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Descripción Corta (Catálogo)</label>
        <textarea name="short_description" id="short_description" rows="2"
                  class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">{{ old('short_description', $product->short_description ?? '') }}</textarea>
    </div>

    <div>
        <label for="long_description" class="block text-sm font-medium text-gray-700 mb-2 font-sans">Historia y Detalles Técnicos</label>
        <textarea name="long_description" id="long_description" rows="5"
                  class="block w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-3.5 focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm font-sans">{{ old('long_description', $product->long_description ?? '') }}</textarea>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h3 class="text-xl font-medium text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Configuración de Venta y Galería</h3>
    
    <div class="flex flex-wrap gap-8 mb-8">
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="track_inventory" id="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory ?? false) ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-800 bg-gray-50 transition-colors">
            <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 font-sans">Rastrear Inventario Bodega</span>
        </label>
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="is_customizable" id="is_customizable" value="1" {{ old('is_customizable', $product->is_customizable ?? false) ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-800 bg-gray-50 transition-colors">
            <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 font-sans">Permitir Medidas a Medida (Cotizable)</span>
        </label>
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                   class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-800 bg-gray-50 transition-colors">
            <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 font-sans">Visible en Tienda Online</span>
        </label>
    </div>

    {{-- Área de subida de imágenes estilizada con Previsualización Alpine.js --}}
    <div class="mb-8" x-data="{ 
            imagesPreviews: [],
            handleFiles(event) {
                // Limpiamos previsualizaciones anteriores
                this.imagesPreviews = []; 
                const files = event.target.files;
                for(let i = 0; i < files.length; i++) {
                    this.imagesPreviews.push({
                        name: files[i].name,
                        url: URL.createObjectURL(files[i])
                    });
                }
            }
        }">
        <label class="block text-sm font-medium text-gray-700 mb-2 font-sans">Fotografías del Mueble</label>
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors relative group">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-amber-800 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" stroke-width="1.5">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600 justify-center">
                    <label for="images" class="relative cursor-pointer bg-transparent rounded-md font-medium text-amber-900 hover:text-amber-800 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-800">
                        <span>Sube archivos</span>
                        <input id="images" name="images[]" type="file" multiple accept="image/*" class="sr-only"
                               @change="handleFiles($event)">
                    </label>
                    <p class="pl-1">o arrastra y suelta aquí</p>
                </div>
                <p class="text-xs text-gray-500 font-sans">PNG, JPG, WEBP hasta 5MB</p>
            </div>
        </div>

        {{-- Grid de Previsualización Dinámica (Sólo aparece si hay imágenes cargadas en el input) --}}
        <div x-show="imagesPreviews.length > 0" class="mt-6" x-cloak>
            <p class="text-sm font-medium text-amber-900 mb-3 font-sans border-b border-amber-100 pb-2">Nuevas imágenes a subir:</p>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <template x-for="(image, index) in imagesPreviews" :key="index">
                    <div class="relative rounded-xl overflow-hidden shadow-sm border border-gray-100 bg-white p-1">
                        <img :src="image.url" :alt="image.name" class="w-full h-24 object-cover rounded-lg">
                        <p class="mt-1 text-[10px] text-gray-500 truncate px-1 font-sans" x-text="image.name"></p>
                    </div>
                </template>
            </div>
        </div>
        
        @error('images.*') <p class="text-rose-600 text-xs mt-2 font-sans">{{ $message }}</p> @enderror
    </div>
    @if(isset($product) && $product->getMedia('product_images')->count())
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-4 font-sans">Imágenes Actuales (Selecciona para eliminar)</label>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach($product->getMedia('product_images') as $img)
                    <div class="relative group rounded-xl overflow-hidden shadow-sm border border-gray-100">
                        <img src="{{ $img->getUrl() }}" class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105">
                        <div class="absolute inset-0 bg-red-900/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <label class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg shadow-sm text-xs text-red-700 font-bold cursor-pointer border border-red-100 flex items-center gap-2 hover:bg-red-50 transition-colors">
                            <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="text-red-600 rounded focus:ring-red-600 border-gray-300 w-4 h-4 cursor-pointer"> 
                            Eliminar
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>