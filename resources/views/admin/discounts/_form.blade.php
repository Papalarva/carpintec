@php
    // Usamos el operador Nullsafe (?->) para prevenir errores cuando $discount es nuevo/vacío
    $selectedAppliesTo = old('applies_to', $discount->applies_to ?? 'all');
    $selectedProducts  = old('product_ids', $discount?->products?->pluck('id')->toArray() ?? []);
    $selectedCategories = old('category_ids', $discount?->categories?->pluck('id')->toArray() ?? []);
    $selectedCustomers = old('customer_ids', $discount?->customers?->pluck('id')->toArray() ?? []);
@endphp

<div class="mb-4">
    <label class="block text-sm font-medium">Nombre</label>
    <input type="text" name="name" value="{{ old('name', $discount->name ?? '') }}" required class="w-full rounded border-gray-300">
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium">Tipo</label>
        <select name="type" required class="w-full rounded border-gray-300">
            @foreach($types as $type)
                <option value="{{ $type->value }}" {{ old('type', $discount->type->value ?? '') == $type->value ? 'selected' : '' }}>{{ $type->label() }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium">Valor</label>
        <input type="number" step="0.01" min="0" name="value" value="{{ old('value', $discount->value ?? '') }}" required class="w-full rounded border-gray-300">
    </div>
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium">Inicio</label>
        <input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($discount->starts_at ?? null)->format('Y-m-d\TH:i')) }}" class="w-full rounded border-gray-300">
    </div>
    <div>
        <label class="block text-sm font-medium">Fin</label>
        <input type="datetime-local" name="ends_at" value="{{ old('ends_at', optional($discount->ends_at ?? null)->format('Y-m-d\TH:i')) }}" class="w-full rounded border-gray-300">
    </div>
</div>

<div class="mb-4">
    <label class="inline-flex items-center">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $discount->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
        <span class="ml-2 text-sm">Activo</span>
    </label>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium">Aplica a</label>
    <select name="applies_to" id="applies_to" class="w-full rounded border-gray-300" onchange="toggleTargetSelector()">
        @foreach($appliesOptions as $key => $label)
            <option value="{{ $key }}" {{ $selectedAppliesTo == $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div id="products-selector" class="{{ $selectedAppliesTo == 'products' ? '' : 'hidden' }} mb-4">
    <label class="block text-sm font-medium">Productos</label>
    <select name="product_ids[]" multiple class="w-full rounded border-gray-300 h-32">
        @foreach($products as $product)
            <option value="{{ $product->id }}" {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>{{ $product->name }}</option>
        @endforeach
    </select>
</div>

<div id="categories-selector" class="{{ $selectedAppliesTo == 'categories' ? '' : 'hidden' }} mb-4">
    <label class="block text-sm font-medium">Categorías</label>
    <select name="category_ids[]" multiple class="w-full rounded border-gray-300 h-32">
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ in_array($cat->id, $selectedCategories) ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
</div>

<div id="customers-selector" class="{{ $selectedAppliesTo == 'customers' ? '' : 'hidden' }} mb-4">
    <label class="block text-sm font-medium">Clientes</label>
    <select name="customer_ids[]" multiple class="w-full rounded border-gray-300 h-32">
        @foreach($customers as $cust)
            <option value="{{ $cust->id }}" {{ in_array($cust->id, $selectedCustomers) ? 'selected' : '' }}>{{ $cust->user->first_name }} {{ $cust->user->last_name }}</option>
        @endforeach
    </select>
</div>

<script>
function toggleTargetSelector() {
    const val = document.getElementById('applies_to').value;
    document.getElementById('products-selector').classList.toggle('hidden', val !== 'products');
    document.getElementById('categories-selector').classList.toggle('hidden', val !== 'categories');
    document.getElementById('customers-selector').classList.toggle('hidden', val !== 'customers');
}
</script>