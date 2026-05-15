<x-app-layout>
    <x-slot:title>Editar Dirección | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-10 font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <a href="{{ session('url.intended.address', route('addresses.index')) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-amber-800 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Volver
                </a>
            </div>

            <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-gray-100">
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h1 class="text-2xl font-serif text-gray-900">Editar Dirección</h1>
                    <p class="text-sm text-gray-500 mt-1">Ingresa tu código postal para autocompletar tu ubicación.</p>
                </div>
                
                <form action="{{ route('addresses.update', $address) }}" method="POST">
                    @method('PUT')
                    @include('addresses.form')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>