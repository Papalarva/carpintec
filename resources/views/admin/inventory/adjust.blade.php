@extends('layouts.admin')

@section('title', 'Gestionar Ficha - ' . $product->name)

@section('header')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-serif font-bold text-gray-900">Ajuste de Inventario</h1>
            <p class="text-sm text-gray-500 font-sans mt-1">
                {{ $product->name }} <span class="mx-2">|</span> SKU: {{ $product->sku }}
            </p>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest font-sans mb-0.5">Stock Actual</p>
                <p class="text-xl font-sans font-bold text-gray-900">{{ $product->inventory?->quantity ?? 0 }}</p>
            </div>
            <a href="{{ route('admin.inventory.movements', $product) }}" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-4 py-2.5 transition-colors shadow-sm font-sans">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Ver Kardex
            </a>
        </div>
    </div>
@endsection

@section('content')
<div x-data="{ 
        mode: 'in', 
        inputAmount: '', 
        currentStock: {{ $product->inventory?->quantity ?? 0 }},
        get finalStock() {
            let amt = parseInt(this.inputAmount) || 0;
            if (this.mode === 'in') return this.currentStock + amt;
            if (this.mode === 'out') return this.currentStock - amt;
            return this.currentStock;
        },
        get payloadQuantity() {
            let amt = parseInt(this.inputAmount) || 0;
            return this.mode === 'out' ? -Math.abs(amt) : (this.mode === 'in' ? Math.abs(amt) : 0);
        }
     }">
     
    <form method="POST" action="{{ route('admin.inventory.store-adjustment', $product) }}" class="space-y-8">
        @csrf
        <input type="hidden" name="quantity" :value="payloadQuantity">

        {{-- Tarjeta Principal: Selector de Acción y Ajuste --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-8 py-5 bg-gray-50/50">
                <h2 class="text-lg font-medium text-gray-900 font-serif flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"></path></svg>
                    ¿Qué acción deseas realizar?
                </h2>
            </div>
            
            <div class="p-8">
                {{-- Botones Segmentados Interactivos --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-10">
                    <button type="button" @click="mode = 'in'; inputAmount = ''" 
                            :class="mode === 'in' ? 'bg-[#fefaf5] border-amber-900 ring-1 ring-amber-900' : 'bg-white border-gray-200 hover:border-amber-700 hover:shadow-sm'" 
                            class="p-5 rounded-2xl border text-left transition-all duration-200 group">
                        <span class="block text-sm font-bold uppercase tracking-widest font-sans mb-1" 
                              :class="mode === 'in' ? 'text-amber-900' : 'text-gray-900 group-hover:text-amber-800'">Ingresar Stock</span>
                        <span class="text-xs text-gray-500 font-sans">Añadir piezas al inventario</span>
                    </button>
                    
                    <button type="button" @click="mode = 'out'; inputAmount = ''" 
                            :class="mode === 'out' ? 'bg-[#fefaf5] border-amber-900 ring-1 ring-amber-900' : 'bg-white border-gray-200 hover:border-amber-700 hover:shadow-sm'" 
                            class="p-5 rounded-2xl border text-left transition-all duration-200 group">
                        <span class="block text-sm font-bold uppercase tracking-widest font-sans mb-1"
                              :class="mode === 'out' ? 'text-amber-900' : 'text-gray-900 group-hover:text-amber-800'">Salida / Merma</span>
                        <span class="text-xs text-gray-500 font-sans">Restar piezas por uso o daño</span>
                    </button>
                    
                    <button type="button" @click="mode = 'logistics'; inputAmount = ''" 
                            :class="mode === 'logistics' ? 'bg-[#fefaf5] border-amber-900 ring-1 ring-amber-900' : 'bg-white border-gray-200 hover:border-amber-700 hover:shadow-sm'" 
                            class="p-5 rounded-2xl border text-left transition-all duration-200 group">
                        <span class="block text-sm font-bold uppercase tracking-widest font-sans mb-1"
                              :class="mode === 'logistics' ? 'text-amber-900' : 'text-gray-900 group-hover:text-amber-800'">Solo Logística</span>
                        <span class="text-xs text-gray-500 font-sans">Actualizar ubicación o mínimos</span>
                    </button>
                </div>

                {{-- Diseño Asimétrico: Inputs (Izq) vs Letrero Gigante (Der) --}}
                <div x-show="mode !== 'logistics'" x-collapse>
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">
                        
                        {{-- Columna Izquierda: Formularios (Ocupa 3 de 5) --}}
                        <div class="lg:col-span-3 space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-2" x-text="mode === 'in' ? 'Cantidad a Sumar' : 'Cantidad a Restar'"></label>
                                <input type="number" x-model.number="inputAmount" min="0" placeholder="Ej: 5"
                                    class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50 font-sans transition-colors">
                                @error('quantity') <p class="text-red-700 text-xs mt-2 font-sans font-medium">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-2">Motivo de la Operación</label>
                                <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Ej: Producción completada, Pieza rota..."
                                    class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50 font-sans transition-colors">
                            </div>
                        </div>

                        {{-- Columna Derecha: El Letrero Masivo (Ocupa 2 de 5) --}}
                        <div class="lg:col-span-2">
                            <div class="h-full rounded-2xl border flex flex-col justify-center items-center text-center p-8 transition-colors duration-300"
                                 :class="finalStock < 0 ? 'bg-red-50 border-red-200' : 'bg-[#fcfaf8] border-gray-200'">
                                <span class="text-xs font-bold uppercase tracking-widest font-sans" :class="finalStock < 0 ? 'text-red-800' : 'text-gray-500'">Stock Resultante:</span>
                                <span class="text-7xl font-sans font-bold mt-4" :class="finalStock < 0 ? 'text-red-800' : 'text-gray-900'" x-text="finalStock"></span>
                                
                                <p x-show="finalStock < 0" x-cloak class="text-xs text-red-700 mt-4 font-bold font-sans tracking-wide">
                                    ⚠️ El stock no puede ser negativo.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta Secundaria: Logística --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100 px-8 py-5 bg-gray-50/50">
                <h2 class="text-lg font-medium text-gray-900 font-serif flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"></path></svg>
                    Logística y Ubicación
                </h2>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-2">Stock Mínimo (Alerta)</label>
                        <input type="number" name="min_quantity" value="{{ old('min_quantity', $product->inventory?->min_quantity ?? 0) }}" required min="0"
                            class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50 font-sans transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest font-sans mb-2">Ubicación en Taller/Tienda</label>
                        <input type="text" name="location" value="{{ old('location', $product->inventory?->location ?? '') }}" placeholder="Ej: Pasillo 3, Anaquel B"
                            class="block w-full rounded-xl border-gray-200 py-3 text-sm focus:ring-amber-800 focus:border-amber-800 bg-gray-50 font-sans transition-colors">
                    </div>
                </div>
            </div>
        </div>

        {{-- Botón de Guardado Global --}}
        <div class="flex justify-end pt-2">
            <button type="submit" 
                    :disabled="finalStock < 0"
                    :class="finalStock < 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-amber-800'"
                    class="inline-flex items-center justify-center bg-amber-900 text-white uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans">
                Guardar Ficha de Inventario
            </button>
        </div>
    </form>
</div>
@endsection