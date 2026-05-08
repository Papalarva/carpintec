@csrf

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4 font-playfair border-b pb-2">Información Básica</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div>
            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU (Código Interno)</label>
            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" required
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors" placeholder="Ej. MES-001">
            @error('sku') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <select name="category_id" id="category_id" required
                    class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
                <option value="">Selecciona una categoría</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
            <input type="text" name="name" id="name" value="{{ old('name', $product->name ?? '') }}" required
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors" placeholder="Ej. Mesa de Parota">
            @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            @error('slug') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio Público ($)</label>
            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price ?? '') }}" required
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
            @error('price') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Costo de Producción ($)</label>
            <input type="number" step="0.01" name="cost" id="cost" value="{{ old('cost', $product->cost ?? '') }}"
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4 font-playfair border-b pb-2">Especificaciones</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <label for="materials" class="block text-sm font-medium text-gray-700 mb-1">Materiales</label>
            <input type="text" name="materials" id="materials" value="{{ old('materials', $product->materials ?? '') }}"
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors" placeholder="Ej. Madera de pino, Acero">
        </div>
        <div>
            <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-1">Dimensiones</label>
            <input type="text" name="dimensions" id="dimensions" value="{{ old('dimensions', $product->dimensions ?? '') }}"
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors" placeholder="Ej. 120x60x75 cm">
        </div>
        <div>
            <label for="weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Peso (kg)</label>
            <input type="number" step="0.01" name="weight_kg" id="weight_kg" value="{{ old('weight_kg', $product->weight_kg ?? '') }}"
                   class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">
        </div>
    </div>

    <div class="mb-4">
        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción Corta (Resumen)</label>
        <textarea name="short_description" id="short_description" rows="2"
                  class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">{{ old('short_description', $product->short_description ?? '') }}</textarea>
    </div>

    <div>
        <label for="long_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción Larga (Detalles técnicos)</label>
        <textarea name="long_description" id="long_description" rows="4"
                  class="block w-full rounded-lg border-gray-200 bg-gray-50 text-sm focus:ring-[#C15C3D] focus:border-[#C15C3D] transition-colors">{{ old('long_description', $product->long_description ?? '') }}</textarea>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4 font-playfair border-b pb-2">Configuración y Multimedia</h3>
    
    <div class="flex flex-wrap gap-6 mb-6">
        <div class="flex items-center">
            <input type="checkbox" name="track_inventory" id="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory ?? false) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-[#C15C3D] focus:ring-[#C15C3D] bg-gray-50">
            <label for="track_inventory" class="ml-2 text-sm font-medium text-gray-700">Rastrear Inventario</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_customizable" id="is_customizable" value="1" {{ old('is_customizable', $product->is_customizable ?? false) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-[#C15C3D] focus:ring-[#C15C3D] bg-gray-50">
            <label for="is_customizable" class="ml-2 text-sm font-medium text-gray-700">Permitir Cotización (Personalizable)</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-gray-300 text-[#C15C3D] focus:ring-[#C15C3D] bg-gray-50">
            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">Producto Activo en Tienda</label>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Subir Imágenes</label>
        <input type="file" name="images[]" multiple accept="image/*"
               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-[#C15C3D] hover:file:bg-gray-100 transition-colors cursor-pointer">
        @error('images.*') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
    </div>

    @if(isset($product) && $product->getMedia('product_images')->count())
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imágenes Actuales (Selecciona para eliminar)</label>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($product->getMedia('product_images') as $img)
                    <div class="relative border border-gray-200 p-1 rounded-lg shadow-sm bg-gray-50 group hover:border-red-300 transition-colors">
                        <img src="{{ $img->getUrl() }}" class="w-full h-24 object-cover rounded-md">
                        <div class="absolute inset-0 bg-red-900/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg"></div>
                        <label class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded shadow-sm text-xs text-red-600 font-bold cursor-pointer border border-red-100 flex items-center gap-1 hover:bg-red-50">
                            <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="text-red-500 rounded focus:ring-red-500"> 
                            Borrar
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>