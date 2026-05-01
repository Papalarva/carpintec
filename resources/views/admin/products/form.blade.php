@csrf

<div class="grid grid-cols-2 gap-4">
    <div>
        <label>SKU</label>
        <input name="sku" value="{{ old('sku', $product->sku ?? '') }}" required>
    </div>
    <div>
        <label>Categoría</label>
        <select name="category_id" required>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Nombre</label>
        <input name="name" value="{{ old('name', $product->name ?? '') }}" required>
    </div>
    <div>
        <label>Precio</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required>
    </div>
    <div>
        <label>Costo</label>
        <input type="number" step="0.01" name="cost" value="{{ old('cost', $product->cost ?? '') }}">
    </div>
    <div>
        <label>Materiales</label>
        <input name="materials" value="{{ old('materials', $product->materials ?? '') }}">
    </div>
    <div>
        <label>Dimensiones</label>
        <input name="dimensions" value="{{ old('dimensions', $product->dimensions ?? '') }}">
    </div>
    <div>
        <label>Peso (kg)</label>
        <input type="number" step="0.01" name="weight_kg" value="{{ old('weight_kg', $product->weight_kg ?? '') }}">
    </div>
</div>

<div class="mt-4">
    <label>Descripción Corta</label>
    <textarea name="short_description" rows="3">{{ old('short_description', $product->short_description ?? '') }}</textarea>
</div>

<div class="mt-4">
    <label>Descripción Larga</label>
    <textarea name="long_description" rows="5">{{ old('long_description', $product->long_description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4 mt-4">
    <div>
        <label>
            <input type="checkbox" name="track_inventory" value="1" {{ old('track_inventory', $product->track_inventory ?? false) ? 'checked' : '' }}>
            Rastrear inventario
        </label>
    </div>
    <div>
        <label>
            <input type="checkbox" name="is_customizable" value="1" {{ old('is_customizable', $product->is_customizable ?? false) ? 'checked' : '' }}>
            Personalizable
        </label>
    </div>
    <div>
        <label>
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            Activo
        </label>
    </div>
</div>

<div class="mt-4">
    <label>Imágenes</label>
    <input type="file" name="images[]" multiple accept="image/*">
</div>

@if(isset($product) && $product->getMedia('product_images')->count())
    <div class="mt-4 grid grid-cols-4 gap-4">
        @foreach($product->getMedia('product_images') as $img)
            <div class="relative border p-2 rounded shadow-sm">
                <img src="{{ $img->getUrl() }}" class="w-full h-32 object-cover rounded">
                <label class="absolute top-2 right-2 bg-white px-2 py-1 rounded shadow text-sm text-red-600 font-bold cursor-pointer">
                    {{-- Al usar getMedia() garantizamos que Laravel lea el UUID completo --}}
                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}" class="mr-1"> 
                    Eliminar
                </label>
            </div>
        @endforeach
    </div>
@endif