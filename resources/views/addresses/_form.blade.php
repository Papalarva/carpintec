<!-- resources\views\addresses\_form.blade.php -->
@csrf
<div class="space-y-6">
    <div>
        <label for="alias" class="block text-sm font-medium text-gray-700">Alias (opcional)</label>
        <input type="text" name="alias" id="alias" value="{{ old('alias', $address->alias ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
        <div class="sm:col-span-4">
            <label for="street" class="block text-sm font-medium text-gray-700">Calle</label>
            <input type="text" name="street" id="street" value="{{ old('street', $address->street ?? '') }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-1">
            <label for="exterior_number" class="block text-sm font-medium text-gray-700">Núm. Ext.</label>
            <input type="text" name="exterior_number" id="exterior_number" value="{{ old('exterior_number', $address->exterior_number ?? '') }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>

        <div class="sm:col-span-1">
            <label for="interior_number" class="block text-sm font-medium text-gray-700">Int.</label>
            <input type="text" name="interior_number" id="interior_number" value="{{ old('interior_number', $address->interior_number ?? '') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
    </div>

    <div>
        <label for="neighborhood" class="block text-sm font-medium text-gray-700">Colonia</label>
        <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood', $address->neighborhood ?? '') }}" required
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div>
            <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
            <input type="text" name="city" id="city" value="{{ old('city', $address->city ?? '') }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div>
            <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
            <input type="text" name="state" id="state" value="{{ old('state', $address->state ?? '') }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
        <div>
            <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal</label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" required
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <div>
            <label for="country" class="block text-sm font-medium text-gray-700">País</label>
            <input type="text" name="country" id="country" value="{{ old('country', $address->country ?? 'México') }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
    </div>

    <div>
        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Teléfono de contacto</label>
        <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $address->contact_phone ?? '') }}"
               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
    </div>

    <div class="flex items-center">
        <input type="checkbox" name="is_primary" id="is_primary" value="1"
               {{ old('is_primary', $address->is_primary ?? false) ? 'checked' : '' }}
               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
        <label for="is_primary" class="ml-2 block text-sm text-gray-900">Establecer como dirección principal</label>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('addresses.index') }}" class="mr-4 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancelar</a>
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
            {{ isset($address) ? 'Actualizar dirección' : 'Guardar dirección' }}
        </button>
    </div>
</div>