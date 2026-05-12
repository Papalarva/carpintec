@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header', 'Perfil de ' . $user->first_name)

@section('content')

@if($errors->any())
    <div class="mb-8 bg-red-50 border-l-4 border-red-700 p-4 rounded-r-xl shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800 font-serif">Por favor, revisa los siguientes errores:</h3>
                <div class="mt-2 text-sm text-red-700 font-sans">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
        <h3 class="text-xl font-serif font-semibold text-gray-900 mb-6 border-b border-gray-100 pb-4">
            Información de Contacto
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 font-sans">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                <input name="first_name" value="{{ old('first_name', $user->first_name) }}" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Apellido</label>
                <input name="last_name" value="{{ old('last_name', $user->last_name) }}" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono Directo</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3.5 text-sm focus:ring-amber-800 focus:border-amber-800 transition-colors shadow-sm" placeholder="Opcional">
            </div>
        </div>
    </div>

    @php
        // Convertimos a string para compatibilidad con Alpine.js
        $selectedIds = collect(old('roles', $userRoles))->map(fn($id) => (string) $id)->toArray();
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8" x-data="{ selectedRoles: @js($selectedIds) }">
        <h3 class="text-xl font-serif font-semibold text-gray-900 mb-6 border-b border-gray-100 pb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
            Permisos y Roles
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 font-sans">
            @foreach($roles as $role)
                <label class="relative border p-4 rounded-xl cursor-pointer transition-all duration-200 flex flex-col items-center text-center h-full"
                    :class="selectedRoles.includes('{{ $role->id }}') ? 'border-amber-900 bg-amber-50 shadow-sm' : 'border-gray-200 hover:border-amber-900/30'">
                    
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="hidden" x-model="selectedRoles">

                    <div class="mb-3">
                        <svg x-cloak x-show="selectedRoles.includes('{{ $role->id }}')" class="w-6 h-6 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-cloak x-show="!selectedRoles.includes('{{ $role->id }}')" class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>

                    <span class="text-sm font-medium text-gray-900 uppercase tracking-wide">{{ $role->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans text-center">
            Cancelar
        </a>
        <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm font-sans text-center">
            Guardar Cambios
        </button>
    </div>
</form>
@endsection