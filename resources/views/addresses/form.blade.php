@csrf
<div class="space-y-8" x-data="addressAutocomplete()">
    
    <div>
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 border-b border-gray-100 pb-2">Ubicación</h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-3">
            
            <div class="sm:col-span-1">
                <label for="postal_code" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Código Postal</label>
                <div class="relative">
                    <input type="text" id="postal_code" name="postal_code"
                           x-model="zip" 
                           @input="fetchLocation" 
                           maxlength="5" 
                           inputmode="numeric" 
                           required
                           class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors text-lg tracking-widest font-mono text-gray-900 font-bold">
                    
                    <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" x-cloak>
                        <svg class="animate-spin h-5 w-5 text-amber-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                @error('postal_code') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="state" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Estado</label>
                <input type="text" name="state" id="state" x-model="state" required maxlength="255"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
                @error('state') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="city" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Ciudad o Municipio</label>
                <input type="text" name="city" id="city" x-model="city" required maxlength="255"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
                @error('city') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="neighborhood" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Colonia / Asentamiento</label>
                
                {{-- FIX LOGICO: Ambos usan x-model="coloniaValue" pero solo uno envía el 'name="neighborhood"' al servidor según el estado --}}
                <select id="neighborhood_select" x-show="colonias.length > 0" :name="colonias.length > 0 ? 'neighborhood' : ''" x-model="coloniaValue" :required="colonias.length > 0" :disabled="colonias.length === 0"
                        class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors cursor-pointer font-sans" x-cloak>
                    <option value="">Selecciona tu colonia...</option>
                    <template x-for="colonia in colonias" :key="colonia">
                        <option :value="colonia" x-text="colonia"></option>
                    </template>
                </select>

                <input type="text" id="neighborhood_input" x-show="colonias.length === 0" :name="colonias.length === 0 ? 'neighborhood' : ''" x-model="coloniaValue" :disabled="colonias.length > 0" :required="colonias.length === 0"
                       maxlength="255"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors placeholder-gray-400 font-sans" 
                       placeholder="Escribe tu colonia">
                @error('neighborhood') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="pt-6">
        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-5 border-b border-gray-100 pb-2">Detalles de Entrega</h3>
        <div class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-6">
            
            <div class="sm:col-span-4">
                <label for="street" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Calle</label>
                <input type="text" name="street" id="street" value="{{ old('street', $address->street ?? '') }}" required maxlength="255"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
                @error('street') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="exterior_number" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">No. Ext.</label>
                <input type="text" name="exterior_number" id="exterior_number" value="{{ old('exterior_number', $address->exterior_number ?? '') }}" required maxlength="10"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans font-mono uppercase">
                @error('exterior_number') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-1">
                <label for="interior_number" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Int. (Opc.)</label>
                <input type="text" name="interior_number" id="interior_number" value="{{ old('interior_number', $address->interior_number ?? '') }}" maxlength="10"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans font-mono uppercase">
                @error('interior_number') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="alias" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Alias <span class="normal-case tracking-normal font-medium text-gray-400">(Ej. Mi Casa, Oficina)</span></label>
                <input type="text" name="alias" id="alias" value="{{ old('alias', $address->alias ?? '') }}" maxlength="100"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors placeholder-gray-400 font-sans" placeholder="Opcional">
                @error('alias') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label for="contact_phone" class="block text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1.5">Teléfono Directo de Recepción</label>
                <input type="tel" name="contact_phone" id="contact_phone" 
                       value="{{ old('contact_phone', $address->contact_phone ?? '') }}" 
                       maxlength="10" 
                       inputmode="numeric"
                       @input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors placeholder-gray-400 font-sans" placeholder="10 dígitos">
                @error('contact_phone') <p class="text-[11px] font-bold text-rose-600 tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="flex items-center pt-2 pb-4">
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="is_primary" id="is_primary" value="1" {{ old('is_primary', $address->is_primary ?? false) ? 'checked' : '' }}
                   class="h-5 w-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-gray-50 transition-colors cursor-pointer shadow-sm">
            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Usar como mi dirección principal</span>
        </label>
    </div>

    <div class="flex flex-col-reverse sm:flex-row justify-end pt-6 border-t border-gray-100 gap-4 mt-6">
        <a href="{{ session('url.intended.address', route('addresses.index')) }}" 
           class="inline-flex justify-center items-center px-10 py-4 text-xs font-bold text-gray-600 hover:text-gray-900 bg-transparent hover:bg-gray-50 border border-transparent hover:border-gray-200 rounded-xl transition-colors focus:outline-none uppercase tracking-widest text-center"
           :class="{ 'opacity-50 pointer-events-none': isSubmitting }">
            Cancelar
        </a>
        
        <button type="submit" 
                @click="if($el.closest('form').checkValidity()) { isSubmitting = true; $el.closest('form').submit(); }"
                :disabled="isSubmitting"
                class="inline-flex justify-center items-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none disabled:opacity-75 disabled:cursor-not-allowed">
            <span x-show="!isSubmitting">{{ isset($address) ? 'Actualizar' : 'Guardar Dirección' }}</span>
            <span x-show="isSubmitting" x-cloak class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Procesando...
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
        coloniaValue: '{{ old('neighborhood', $address->neighborhood ?? '') }}',
        colonias: [],
        loading: false,
        isSubmitting: false,

        init() {
            if (this.zip.length === 5) {
                this.fetchLocation(true); // true = es la carga inicial, no borrar coloniaValue
            }
        },

        async fetchLocation(isInit = false) {
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
                        
                        // Si no es la carga inicial (ej. el usuario está escribiendo un nuevo CP), limpiamos la colonia.
                        if (!isInit && !this.colonias.includes(this.coloniaValue)) {
                             this.coloniaValue = '';
                        }
                    } else {
                        this.colonias = [];
                        if(!isInit) { this.coloniaValue = ''; }
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