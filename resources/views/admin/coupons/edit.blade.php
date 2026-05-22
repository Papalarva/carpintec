@extends('layouts.admin')

@section('title', 'Editar Cupón')
@section('header', 'Configuración de Cupón')

@section('content')
<div class="max-w-3xl mx-auto">
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-gray-100 pb-6 mb-6 gap-4">
                <h3 class="text-xl font-serif font-bold text-gray-900">Editar Código Promocional</h3>
                {{-- Ticket visual para el código --}}
                <span class="inline-flex items-center px-4 py-2.5 rounded-lg text-sm font-bold font-mono tracking-widest text-amber-900 bg-amber-50/80 border border-dashed border-amber-300 shadow-sm">
                    {{ $coupon->code }}
                </span>
            </div>
            
            <div class="grid grid-cols-1 gap-8 font-sans">
                
                {{-- Descuento Asociado --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Campaña / Descuento Asociado <span class="text-rose-500">*</span></label>
                    <select name="discount_id" required class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm cursor-pointer">
                        @foreach($discounts as $discount)
                            @php
                                $valText = $discount->type === \App\Enums\DiscountType::PERCENTAGE ? rtrim(rtrim($discount->value, '0'), '.') . '%' : '$' . number_format($discount->value, 2);
                            @endphp
                            <option value="{{ $discount->id }}" {{ old('discount_id', $coupon->discount_id) == $discount->id ? 'selected' : '' }}>
                                {{ $discount->name }} ({{ $valText }})
                            </option>
                        @endforeach
                    </select>
                    @error('discount_id') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                </div>

                {{-- Código Promocional Modificable --}}
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Código Promocional</label>
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z"></path></svg>
                        </div>
                        <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                               class="block w-full pl-12 rounded-xl border-dashed border-2 border-amber-200 bg-amber-50/50 py-4 text-lg font-bold font-mono uppercase text-amber-900 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                    </div>
                    @error('code') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8 mt-2">
                    {{-- Usos Máximos --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Límite de Usos Totales</label>
                        <input type="number" name="max_uses" min="1" value="{{ old('max_uses', $coupon->max_uses) }}"
                               placeholder="Ej. 100"
                               class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                        <p class="text-xs text-gray-500 mt-2 font-sans font-medium">
                            Se ha usado <strong class="text-gray-900">{{ $coupon->used_count }}</strong> veces.
                        </p>
                        @error('max_uses') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
                    </div>

                    {{-- Expiración --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Expiración Exacta</label>
                        <input type="datetime-local" name="expires_at" 
                               value="{{ old('expires_at', optional($coupon->expires_at)->format('Y-m-d\TH:i')) }}"
                               class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
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
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection