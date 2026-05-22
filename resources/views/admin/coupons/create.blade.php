@extends('layouts.admin')

@section('title', 'Nuevo Cupón')
@section('header', 'Crear Código Promocional')

@section('content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8" x-data="{ 
            code: '{{ old('code') }}',
            generateCode() {
                const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Sin O, 0, 1, I
                let result = '';
                for (let i = 0; i < 8; i++) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                this.code = result;
            }
        }">
            <h3 class="text-xl font-serif font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">
                Configuración del Cupón
            </h3>
            
            <div class="grid grid-cols-1 gap-8 font-sans">
                
                {{-- Descuento Asociado --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Campaña / Descuento Asociado <span class="text-rose-500">*</span></label>
                    <select name="discount_id" required class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm cursor-pointer">
                        <option value="">Selecciona la regla de descuento a aplicar</option>
                        @foreach($discounts as $discount)
                            @php
                                $valText = $discount->type === \App\Enums\DiscountType::PERCENTAGE ? rtrim(rtrim($discount->value, '0'), '.') . '%' : '$' . number_format($discount->value, 2);
                            @endphp
                            <option value="{{ $discount->id }}" {{ old('discount_id') == $discount->id ? 'selected' : '' }}>
                                {{ $discount->name }} ({{ $valText }})
                            </option>
                        @endforeach
                    </select>
                    @error('discount_id') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Código Promocional con Generador --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Código Promocional</label>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"></path></svg>
                            </div>
                            <input type="text" name="code" x-model="code"
                                   placeholder="Ej. BUENFIN2026"
                                   class="block w-full pl-12 rounded-xl border-dashed border-2 border-amber-200 bg-amber-50/50 py-4 text-lg font-bold font-mono uppercase text-amber-900 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors placeholder-amber-900/30">
                        </div>
                        <button type="button" @click="generateCode()" class="px-6 py-4 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-[10px] font-bold rounded-xl shadow-sm transition-colors whitespace-nowrap focus:outline-none">
                            Aleatorio
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-3 font-sans font-medium">Si lo dejas vacío, el sistema generará uno automáticamente.</p>
                    @error('code') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8 mt-2">
                    {{-- Usos Máximos --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Límite de Usos Totales</label>
                        <input type="number" name="max_uses" min="1" value="{{ old('max_uses') }}"
                               placeholder="Ej. 100"
                               class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                        <p class="text-xs text-gray-500 mt-2 font-sans font-medium">Dejar vacío para usos ilimitados.</p>
                        @error('max_uses') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Expiración --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Expiración Exacta</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}"
                               class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                        <p class="text-xs text-gray-500 mt-2 font-sans font-medium">Fecha y hora en que dejará de ser válido.</p>
                        @error('expires_at') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mb-8">
            <a href="{{ route('admin.coupons.index') }}" class="w-full sm:w-auto text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans focus:outline-none">
                Cancelar
            </a>
            <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans text-center focus:outline-none">
                Crear Cupón
            </button>
        </div>
    </form>
</div>
@endsection