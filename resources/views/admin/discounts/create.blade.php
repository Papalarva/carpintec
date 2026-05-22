@extends('layouts.admin')
@section('title', 'Nuevo Descuento')
@section('header', 'Crear Nuevo Descuento')

@section('content')
    <form action="{{ route('admin.discounts.store') }}" method="POST" class="max-w-5xl mx-auto">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
            <h3 class="text-xl font-serif font-bold text-gray-900 mb-8 border-b border-gray-100 pb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                Configuración del Descuento
            </h3>
            
            @include('admin.discounts.form', ['discount' => $discount])
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mb-8">
            <a href="{{ route('admin.discounts.index') }}" class="w-full sm:w-auto text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans focus:outline-none">
                Cancelar
            </a>
            <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans text-center focus:outline-none">
                Crear Descuento
            </button>
        </div>
    </form>
@endsection