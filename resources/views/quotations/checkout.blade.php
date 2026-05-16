<x-app-layout>
    <x-slot:title>Finalizar Proyecto | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-10 font-sans">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 border-b border-gray-200 pb-6 mt-4">
                <span class="text-amber-800 font-bold tracking-[0.2em] uppercase text-[10px] mb-2 block">Paso Final</span>
                <h1 class="font-serif text-3xl sm:text-4xl text-gray-900 tracking-tight">Confirmar Proyecto a Medida</h1>
            </div>

            <form action="{{ route('quotations.process-checkout', $quotation) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                    
                    <div class="lg:col-span-7 xl:col-span-8 space-y-8">
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8" x-data="{ selectedAddress: '{{ old('address_id', $addresses->where('is_primary', true)->first()->id ?? $addresses->first()->id) }}' }">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-lg font-bold text-gray-900 uppercase tracking-widest font-sans">1. Dirección de Entrega</h2>
                                <a href="{{ route('addresses.create') }}" class="text-xs font-bold text-amber-800 hover:text-amber-900 uppercase tracking-widest flex items-center transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"></path></svg>
                                    Agregar Nueva
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($addresses as $address)
                                    <label class="relative flex cursor-pointer rounded-xl border bg-white p-5 shadow-sm focus:outline-none transition-all duration-200 group"
                                           :class="selectedAddress == '{{ $address->id }}' ? 'border-amber-600 ring-1 ring-amber-600 bg-amber-50/10' : 'border-gray-200 hover:border-amber-300'">
                                        
                                        <input type="radio" name="address_id" value="{{ $address->id }}" x-model="selectedAddress" class="sr-only">
                                        
                                        <span class="flex flex-1">
                                            <span class="flex flex-col">
                                                <span class="block text-sm font-bold text-gray-900 mb-1 flex items-center gap-2">
                                                    {{ $address->alias ?? 'Mi Dirección' }}
                                                    @if($address->is_primary)
                                                        <span class="bg-green-100 text-green-800 text-[9px] px-2 py-0.5 rounded-full uppercase tracking-wider">Principal</span>
                                                    @endif
                                                </span>
                                                <span class="mt-1 flex items-center text-sm text-gray-600 leading-relaxed">
                                                    {{ $address->street }} {{ $address->exterior_number }} {{ $address->interior_number ? 'Int. ' . $address->interior_number : '' }}<br>
                                                    Col. {{ $address->neighborhood }}<br>
                                                    {{ $address->city }}, {{ $address->state }}, C.P. {{ $address->postal_code }}
                                                </span>
                                            </span>
                                        </span>
                                        
                                        <svg class="h-5 w-5 text-amber-600 absolute top-5 right-5 transition-opacity duration-200" 
                                             :class="selectedAddress == '{{ $address->id }}' ? 'opacity-100' : 'opacity-0'" 
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    </label>
                                @endforeach
                            </div>
                            @error('address_id') <p class="mt-3 text-sm text-red-600 font-sans">{{ $message }}</p> @enderror
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <h2 class="text-lg font-bold text-gray-900 uppercase tracking-widest font-sans mb-4">2. Instrucciones Finales (Opcional)</h2>
                            <textarea name="notes" rows="3" maxlength="1000"
                                      class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors resize-none text-sm font-sans" 
                                      placeholder="Ej. Entregar por la puerta trasera, cuidado con la pintura de la escalera, llamar al llegar...">{{ old('notes') }}</textarea>
                            @error('notes') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="lg:col-span-5 xl:col-span-4">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sticky top-8 border-t-4 border-t-amber-900">
                            <h2 class="text-lg font-bold text-gray-900 uppercase tracking-widest font-sans mb-6 border-b border-gray-100 pb-4">Resumen de Proyecto</h2>

                            <div class="mb-6">
                                <span class="text-[10px] font-bold text-amber-800 uppercase tracking-widest">Fabricación a Medida</span>
                                <h3 class="font-serif text-xl text-gray-900 mt-1 leading-snug">{{ $quotation->subject }}</h3>
                            </div>

                            <div class="border-t border-gray-100 pt-6 space-y-4 text-sm font-sans">
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Presupuesto Base</span>
                                    <span class="font-medium text-gray-900">${{ number_format($quotation->estimated_price, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-gray-600">
                                    <span>Envío Especializado</span>
                                    <span class="font-medium text-gray-900 text-xs uppercase tracking-widest">Por Confirmar</span>
                                </div>
                                
                                <div class="flex justify-between items-end pt-6 border-t border-gray-100">
                                    <span class="text-gray-900 font-bold uppercase tracking-widest text-sm">Total Estimado</span>
                                    <div class="text-right">
                                        <span class="font-serif text-3xl text-gray-900 block leading-none">${{ number_format($quotation->estimated_price, 2) }}</span>
                                        <span class="text-[10px] text-gray-500 uppercase tracking-widest">MXN (IVA Incluido)</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="mt-8 w-full bg-amber-900 text-white py-4 rounded-xl hover:bg-amber-800 transition-colors uppercase tracking-widest text-sm font-bold shadow-md hover:shadow-lg focus:outline-none">
                                Confirmar Fabricación
                            </button>

                            <div class="mt-6 flex items-start gap-3 text-xs text-gray-500 bg-gray-50 p-4 rounded-xl">
                                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="leading-relaxed">
                                    Al confirmar, tu proyecto pasará a estado de producción. Te enviaremos un enlace seguro para realizar el anticipo correspondiente.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>