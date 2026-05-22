<x-app-layout>
    <x-slot:title>Mis Direcciones | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-10 font-sans">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{}">

            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div>
                    <h2 class="font-serif text-3xl text-gray-900 leading-tight">Mis Direcciones</h2>
                    <p class="text-gray-500 mt-2 text-sm">Gestiona tus lugares de entrega para un checkout más rápido.</p>
                </div>
                <a href="{{ route('addresses.create') }}"
                    class="inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-6 py-3.5 transition-colors shadow-sm w-full sm:w-auto">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nueva Dirección
                </a>
            </div>

            @if ($addresses->isEmpty())
                <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-serif text-gray-900 mb-1">Aún no hay direcciones</h3>
                    <p class="text-sm text-gray-500">Agrega tu casa o taller para facilitar tus futuras cotizaciones.</p>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach ($addresses as $address)
                        <div class="bg-white rounded-2xl border {{ $address->is_primary ? 'border-amber-500 shadow-md relative overflow-hidden' : 'border-gray-200 shadow-sm' }} p-6 transition-all hover:shadow-md flex flex-col h-full">

                            @if ($address->is_primary)
                                <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden pointer-events-none">
                                    <div class="absolute transform rotate-45 bg-amber-500 text-white text-[10px] font-bold tracking-wider uppercase py-1 right-[-35px] top-[15px] w-[120px] text-center shadow-sm">
                                        Principal
                                    </div>
                                </div>
                            @endif

                            <div class="flex-grow">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 {{ $address->is_primary ? 'text-amber-600' : 'text-gray-400' }} mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                    </svg>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $address->alias ?: 'Dirección de entrega' }}</h3>
                                </div>

                                <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                    {{ $address->street }} {{ $address->exterior_number }}
                                    {{ $address->interior_number ? 'Int. ' . $address->interior_number : '' }}<br>
                                    Col. {{ $address->neighborhood }}<br>
                                    {{ $address->city }}, {{ $address->state }}, C.P. {{ $address->postal_code }}
                                </p>

                                @if ($address->contact_phone)
                                    <div class="flex items-center text-sm text-gray-500 mb-2">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                        </svg>
                                        {{ $address->contact_phone }}
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex space-x-4">
                                    <a href="{{ route('addresses.edit', $address) }}" class="text-sm font-medium text-gray-600 hover:text-amber-800 transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                        Editar
                                    </a>
                                    
                                    <button
                                        @click.prevent="$dispatch('open-delete-modal', { url: '{{ route('addresses.destroy', $address) }}' })"
                                        type="button"
                                        class="text-sm font-medium text-red-600 hover:text-red-800 transition-colors flex items-center focus:outline-none">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </div>

                                @if (!$address->is_primary)
                                    <form action="{{ route('addresses.set-primary', $address) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-xs font-bold text-amber-700 hover:text-amber-900 uppercase tracking-wider transition-colors focus:outline-none">
                                            Fijar principal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div x-data="{ 
            show: false, 
            deleteUrl: '',
            openModal(url) {
                this.deleteUrl = url;
                this.show = true;
                document.body.style.overflow = 'hidden';
            },
            closeModal() {
                this.show = false;
                document.body.style.overflow = '';
            }
         }" 
         @open-delete-modal.window="openModal($event.detail.url)"
         @keydown.escape.window="closeModal()"
         x-show="show" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true" 
         x-cloak>
        
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"
                 class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" 
                 aria-hidden="true"></div>

            <div x-show="show" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4 sm:translate-y-0"
                 class="relative inline-block w-full max-w-md overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl p-6 sm:p-8 z-10">

                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto sm:mx-0 sm:h-12 sm:w-12 bg-red-50 rounded-full border border-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-serif text-gray-900" id="modal-title">¿Eliminar dirección?</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 leading-relaxed">
                                Esta dirección será removida de tu libreta, pero se conservará intacta en el historial de tus pedidos o cotizaciones previas.
                            </p>
                        </div>
                    </div>
                </div>

                <form :action="deleteUrl" method="POST" class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
                    @csrf
                    @method('DELETE')
                    <button type="button" @click="closeModal()"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-sm font-bold text-gray-600 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-xl transition-colors duration-200 focus:outline-none uppercase tracking-widest">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-colors duration-200 focus:outline-none uppercase tracking-widest">
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>