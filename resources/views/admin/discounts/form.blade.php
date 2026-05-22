@php
    $selectedAppliesTo = old('applies_to', $discount->applies_to ?? 'all');
    $selectedType = old('type', $discount->type->value ?? \App\Enums\DiscountType::PERCENTAGE->value);
    
    // Fechas formateadas para inputs datetime-local
    $selectedStartsAt = old('starts_at', optional($discount->starts_at ?? null)->format('Y-m-d\TH:i'));
    $selectedEndsAt = old('ends_at', optional($discount->ends_at ?? null)->format('Y-m-d\TH:i'));
    
    // Garantiza que JavaScript reciba un Array y no un Objeto.
    $selectedProducts  = collect(old('product_ids', $discount->id ? $discount->products->pluck('id') : []))->map(fn($id)=>(string)$id)->values()->toArray();
    $selectedCategories = collect(old('category_ids', $discount->id ? $discount->categories->pluck('id') : []))->map(fn($id)=>(string)$id)->values()->toArray();
    $selectedCustomers = collect(old('customer_ids', $discount->id ? $discount->customers->pluck('id') : []))->map(fn($id)=>(string)$id)->values()->toArray();
@endphp

{{-- El Cerebro Reactivo con Alpine.js --}}
<div x-data="{ 
    appliesTo: '{{ $selectedAppliesTo }}',
    discountType: '{{ $selectedType }}',
    discountValue: '{{ old('value', $discount->value ?? '') }}',
    startsAt: '{{ $selectedStartsAt }}',
    endsAt: '{{ $selectedEndsAt }}',
    selProducts: @js($selectedProducts),
    selCategories: @js($selectedCategories),
    selCustomers: @js($selectedCustomers),
    
    // Función para auto-corregir el valor si es porcentaje
    enforceValueLimits() {
        if (this.discountType === '{{ \App\Enums\DiscountType::PERCENTAGE->value }}') {
            let val = parseFloat(this.discountValue);
            if (val > 100) {
                this.discountValue = '100.00';
            }
        }
    },
    
    // Función para auto-corregir la fecha de fin
    enforceDateLogic() {
        if (this.startsAt && this.endsAt && this.endsAt < this.startsAt) {
            this.endsAt = this.startsAt;
        }
    }
}" 
x-init="
    $watch('discountValue', () => enforceValueLimits());
    $watch('discountType', (newType) => { enforceValueLimits(); });
    $watch('startsAt', () => enforceDateLogic());
    $watch('endsAt', () => enforceDateLogic());
">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7 mb-10 font-sans">
        
        {{-- Nombre de la Campaña --}}
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nombre de la Campaña / Descuento</label>
            <input type="text" name="name" value="{{ old('name', $discount->name ?? '') }}" required 
                   class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm" placeholder="Ej. Hot Sale Verano">
            @error('name') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Tipo de Descuento --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tipo de Descuento</label>
            <select name="type" x-model="discountType" required class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm cursor-pointer">
                @foreach($types as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                @endforeach
            </select>
            @error('type') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
        </div>
        
        {{-- Valor --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Valor</label>
            <div class="relative flex items-center">
                {{-- Símbolo de moneda dinámico --}}
                <span x-cloak x-show="discountType !== '{{ \App\Enums\DiscountType::PERCENTAGE->value }}'" class="absolute left-4 text-gray-500 font-sans font-medium pointer-events-none">$</span>
                
                {{-- Input reactivo blindado --}}
                <input type="number" step="0.01" min="0.01" name="value" x-model="discountValue" required 
                       :max="discountType === '{{ \App\Enums\DiscountType::PERCENTAGE->value }}' ? 100 : null"
                       class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-base font-bold text-gray-900 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans" 
                       :class="discountType === '{{ \App\Enums\DiscountType::PERCENTAGE->value }}' ? 'pl-4 pr-10' : 'pl-8 pr-4'"
                       placeholder="0.00">
                
                {{-- Símbolo de porcentaje dinámico --}}
                <span x-cloak x-show="discountType === '{{ \App\Enums\DiscountType::PERCENTAGE->value }}'" class="absolute right-4 text-gray-500 font-sans font-medium pointer-events-none">%</span>
            </div>
            @error('value') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
        </div>

        {{-- Fechas --}}
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Fecha de Inicio (Opcional)</label>
            <input type="datetime-local" name="starts_at" x-model="startsAt"
                   class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
            @error('starts_at') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
        </div>
        
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Fecha de Finalización (Opcional)</label>
            <input type="datetime-local" name="ends_at" x-model="endsAt" :min="startsAt"
                   class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
            @error('ends_at') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Estado Activo --}}
    <div class="mb-10 pt-6 border-t border-gray-100 font-sans">
        <label class="flex items-center cursor-pointer group w-max bg-gray-50/30 rounded-xl pr-4 border border-transparent hover:border-gray-100 transition-colors">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $discount->is_active ?? true) ? 'checked' : '' }} 
                   class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-white transition-colors cursor-pointer shadow-sm">
            <span class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">Descuento Activo en tienda</span>
        </label>
    </div>

    {{-- Lógica de Aplicación --}}
    <div class="mb-8 p-6 bg-gray-50/50 rounded-2xl border border-gray-100 font-sans">
        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">¿A qué aplica este descuento?</label>
        <select name="applies_to" x-model="appliesTo" class="block w-full rounded-xl border-gray-200 bg-white py-3.5 text-sm focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm cursor-pointer">
            @foreach($appliesOptions as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- Selector de Productos --}}
    <div x-show="appliesTo === 'products'" x-transition x-cloak class="mb-8 font-sans">
        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Selecciona los Productos</label>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-80 overflow-y-auto p-3 border border-gray-100 rounded-xl bg-gray-50/50">
            @foreach($products as $product)
                <label class="relative border p-4 rounded-xl cursor-pointer transition-all duration-300 flex flex-col h-full bg-white hover:-translate-y-0.5"
                    :class="selProducts.includes('{{ $product->id }}') ? 'border-amber-900 bg-amber-50/50 shadow-sm' : 'border-gray-200 hover:border-amber-900/30 hover:shadow-sm'">
                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="hidden" x-model="selProducts">
                    <div class="flex justify-between items-start mb-2 gap-2">
                        <span class="text-sm font-bold text-gray-900 leading-tight">{{ $product->name }}</span>
                        <div class="transform transition-transform duration-200" :class="selProducts.includes('{{ $product->id }}') ? 'scale-110' : 'scale-100'">
                            <svg x-cloak x-show="selProducts.includes('{{ $product->id }}')" class="w-5 h-5 text-amber-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </div>
                    </div>
                    <span class="text-[10px] uppercase tracking-widest text-gray-400 mt-auto font-mono font-medium">SKU: {{ $product->sku }}</span>
                </label>
            @endforeach
        </div>
        @error('product_ids') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
    </div>

    {{-- Selector de Categorías --}}
    <div x-show="appliesTo === 'categories'" x-transition x-cloak class="mb-8 font-sans">
        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Selecciona las Categorías</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-h-80 overflow-y-auto p-3 border border-gray-100 rounded-xl bg-gray-50/50">
            @foreach($categories as $cat)
                <label class="relative border p-4 rounded-xl cursor-pointer transition-all duration-300 flex items-center gap-3 bg-white hover:-translate-y-0.5"
                    :class="selCategories.includes('{{ $cat->id }}') ? 'border-amber-900 bg-amber-50/50 shadow-sm' : 'border-gray-200 hover:border-amber-900/30 hover:shadow-sm'">
                    <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}" class="hidden" x-model="selCategories">
                    <div class="h-5 w-5 border rounded flex items-center justify-center shrink-0 transition-colors" :class="selCategories.includes('{{ $cat->id }}') ? 'bg-amber-900 border-amber-900' : 'bg-white border-gray-300'">
                        <svg x-cloak x-show="selCategories.includes('{{ $cat->id }}')" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    <span class="text-sm font-bold text-gray-900 truncate">{{ $cat->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Selector de Clientes --}}
    <div x-show="appliesTo === 'customers'" x-transition x-cloak class="mb-8 font-sans">
        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4">Selecciona los Clientes</label>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-80 overflow-y-auto p-3 border border-gray-100 rounded-xl bg-gray-50/50">
            @foreach($customers as $cust)
                <label class="relative border p-4 rounded-xl cursor-pointer transition-all duration-300 flex items-center gap-3 bg-white hover:-translate-y-0.5"
                    :class="selCustomers.includes('{{ $cust->id }}') ? 'border-amber-900 bg-amber-50/50 shadow-sm' : 'border-gray-200 hover:border-amber-900/30 hover:shadow-sm'">
                    
                    <input type="checkbox" name="customer_ids[]" value="{{ $cust->id }}" class="hidden" x-model="selCustomers">
                    
                    <div class="h-5 w-5 border rounded flex items-center justify-center shrink-0 transition-colors" :class="selCustomers.includes('{{ $cust->id }}') ? 'bg-amber-900 border-amber-900' : 'bg-white border-gray-300'">
                        <svg x-cloak x-show="selCustomers.includes('{{ $cust->id }}')" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                    </div>
                    
                    <div class="flex flex-col truncate">
                        <span class="text-sm font-bold text-gray-900 truncate">
                            {{ $cust->user?->first_name ?? 'Usuario' }} {{ $cust->user?->last_name ?? 'Eliminado' }}
                            
                            @if($cust->user && $cust->user->deleted_at !== null)
                                <span class="ml-1 text-[9px] font-bold bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded uppercase tracking-widest border border-rose-200">Eliminado</span>
                            @endif
                        </span>
                        <span class="text-[11px] text-gray-500 font-medium truncate mt-0.5">{{ $cust->user?->email ?? 'Sin correo' }}</span>
                    </div>
                </label>
            @endforeach
        </div>
    </div>
</div>