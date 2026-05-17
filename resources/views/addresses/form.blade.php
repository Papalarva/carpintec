@csrf
<div class="space-y-8" x-data="addressAutocomplete()">
    
    <div>
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Ubicación</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            
            <div class="sm:col-span-1">
                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Código Postal</label>
                <div class="relative">
                    <input type="text" name="postal_code" id="postal_code" 
                           x-model="zip" 
                           @input="fetchLocation" 
                           maxlength="5" 
                           inputmode="numeric" 
                           required
                           class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors text-lg tracking-widest font-mono">
                    
                    <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" x-cloak>
                        <svg class="animate-spin h-5 w-5 text-amber-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                @error('postal_code') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <input type="text" name="state" id="state" x-model="state" required maxlength="255"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                @error('state') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">Ciudad o Municipio</label>
                <input type="text" name="city" id="city" x-model="city" required maxlength="255"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                @error('city') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="neighborhood" class="block text-sm font-medium text-gray-700 mb-2">Colonia / Asentamiento</label>
                
                <select name="neighborhood" id="neighborhood" x-show="colonias.length > 0" :required="colonias.length > 0" :disabled="colonias.length === 0"
                        class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors bg-white" x-cloak>
                    <option value="">Selecciona tu colonia...</option>
                    <template x-for="colonia in colonias" :key="colonia">
                        <option :value="colonia" x-text="colonia" :selected="colonia == '{{ old('neighborhood', $address->neighborhood ?? '') }}'"></option>
                    </template>
                </select>

                <input type="text" name="neighborhood_manual" id="neighborhood_manual" 
                       x-show="colonias.length === 0" 
                       :disabled="colonias.length > 0" 
                       :required="colonias.length === 0"
                       maxlength="255"
                       value="{{ old('neighborhood', $address->neighborhood ?? '') }}" 
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400" 
                       placeholder="Escribe tu colonia">
                @error('neighborhood') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="pt-6 border-t border-gray-100">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-widest mb-4">Detalles de Entrega</h3>
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-6">
            
            <div class="sm:col-span-4">
                <label for="street" class="block text-sm font-medium text-gray-700 mb-2">Calle</label>
                <input type="text" name="street" id="street" value="{{ old('street', $address->street ?? '') }}" required maxlength="255"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                @error('street') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="exterior_number" class="block text-sm font-medium text-gray-700 mb-2">No. Ext.</label>
                <input type="text" name="exterior_number" id="exterior_number" value="{{ old('exterior_number', $address->exterior_number ?? '') }}" required maxlength="10"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                @error('exterior_number') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="interior_number" class="block text-sm font-medium text-gray-700 mb-2">Int. (Opc.)</label>
                <input type="text" name="interior_number" id="interior_number" value="{{ old('interior_number', $address->interior_number ?? '') }}" maxlength="10"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                @error('interior_number') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="alias" class="block text-sm font-medium text-gray-700 mb-2">Alias (Ej. Mi Casa, Oficina)</label>
                <input type="text" name="alias" id="alias" value="{{ old('alias', $address->alias ?? '') }}" maxlength="100"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400" placeholder="Opcional">
                @error('alias') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono para recibir entregas</label>
                <input type="tel" name="contact_phone" id="contact_phone" 
                       value="{{ old('contact_phone', $address->contact_phone ?? '') }}" 
                       maxlength="10" 
                       inputmode="numeric"
                       @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                       class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors placeholder-gray-400" placeholder="10 dígitos mínimo">
                @error('contact_phone') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center pt-2">
        <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary', $address->is_primary ?? false) ? 'checked' : '' }}
               class="h-4 w-4 rounded border-gray-300 text-amber-900 focus:ring-amber-800 transition-colors cursor-pointer">
        <label for="is_primary" class="ml-2 block text-sm font-medium text-gray-900 cursor-pointer">Usar como mi dirección principal</label>
    </div>

    <div class="flex flex-col-reverse sm:flex-row justify-end pt-6 border-t border-gray-100 gap-4 mt-6">
        <a href="{{ session('url.intended.address', route('addresses.index')) }}" 
           class="inline-flex justify-center items-center px-8 py-3.5 text-sm font-bold text-gray-600 bg-transparent hover:bg-gray-100 rounded-xl transition-colors focus:outline-none uppercase tracking-widest text-center"
           :class="{ 'opacity-50 pointer-events-none': isSubmitting }">
            Cancelar
        </a>
        
        <button type="submit" 
                @click="if($el.closest('form').checkValidity()) { isSubmitting = true; $el.closest('form').submit(); }"
                :disabled="isSubmitting"
                class="inline-flex justify-center items-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-3.5 transition-colors shadow-sm focus:outline-none disabled:opacity-75 disabled:cursor-not-allowed">
            <span x-show="!isSubmitting">{{ isset($address) ? 'Actualizar' : 'Guardar Dirección' }}</span>
            <span x-show="isSubmitting" x-cloak class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Guardando...
            </span>
        </button>
    </div>
</div>

<script>
function addressAutocomplete() {
    return {
        zip: '{{ old('postal_code', $address->postal_code ?? '') }}',
        city: '{{ old('city', $address->city ?? '') }}',
        state: '{{ old('state', $address->state ?? '') }}',
        colonias: [],
        loading: false,
        isSubmitting: false,

        init() {
            if (this.zip.length === 5) {
                this.fetchLocation();
            }
        },

        async fetchLocation() {
            // Elimina cualquier caracter no numérico al vuelo
            this.zip = this.zip.replace(/[^0-9]/g, '');
            
            if (this.zip.length === 5) {
                this.loading = true;
                
                try {
                    const response = await fetch(`https://api.zippopotam.us/mx/${this.zip}`);
                    
                    if (response.ok) {
                        const data = await response.json();
                        this.state = data.places[0].state;
                        this.city = data.places[0]['place name']; 
                        this.colonias = data.places.map(place => place['place name']);
                    } else {
                        // Si SEPOMEX no lo encuentra, permitimos llenado 100% manual
                        this.colonias = [];
                    }
                } catch (error) {
                    this.colonias = [];
                } finally {
                    this.loading = false;
                }
            } else if (this.zip.length < 5) {
                this.colonias = [];
            }
        }
    }
}
</script>