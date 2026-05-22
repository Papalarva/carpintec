@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header', 'Perfil de ' . $user->first_name)

@section('content')

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="max-w-5xl mx-auto">
    @csrf @method('PUT')

    {{-- Tarjeta: Información de Contacto --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
        <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">
            Información de Contacto
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-7 font-sans">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nombre</label>
                <input name="first_name" value="{{ old('first_name', $user->first_name) }}" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                @error('first_name') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Apellido</label>
                <input name="last_name" value="{{ old('last_name', $user->last_name) }}" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                @error('last_name') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Correo Electrónico</label>
                <input name="email" type="email" value="{{ old('email', $user->email) }}" required 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm">
                @error('email') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Teléfono Directo</label>
                <input name="phone" value="{{ old('phone', $user->phone) }}" 
                       class="block w-full rounded-xl border-gray-200 bg-gray-50/50 py-3.5 text-sm focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm" placeholder="Opcional">
                @error('phone') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    @php
        // Convertimos a string para compatibilidad con Alpine.js
        $selectedIds = collect(old('roles', $userRoles))->map(fn($id) => (string) $id)->toArray();
    @endphp

    {{-- Tarjeta: Permisos y Roles --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8" x-data="{ selectedRoles: @js($selectedIds) }">
        <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"></path></svg>
            Permisos y Roles
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 font-sans">
            @foreach($roles as $role)
                <label class="relative border p-5 rounded-2xl cursor-pointer transition-all duration-300 flex flex-col items-center text-center h-full hover:-translate-y-0.5"
                    :class="selectedRoles.includes('{{ $role->id }}') ? 'border-amber-900 bg-amber-50/50 shadow-md' : 'border-gray-200 hover:border-amber-900/30 bg-white hover:shadow-sm'">
                    
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="hidden" x-model="selectedRoles">

                    <div class="mb-3 transform transition-transform duration-200" :class="selectedRoles.includes('{{ $role->id }}') ? 'scale-110' : 'scale-100'">
                        <svg x-cloak x-show="selectedRoles.includes('{{ $role->id }}')" class="w-7 h-7 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-cloak x-show="!selectedRoles.includes('{{ $role->id }}')" class="w-7 h-7 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>

                    <span class="text-sm font-bold text-gray-900 uppercase tracking-wide">{{ $role->name }}</span>
                </label>
            @endforeach
        </div>
        @error('roles') <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-4 font-sans">{{ $message }}</p> @enderror
    </div>

    {{-- Botones de Acción --}}
    <div class="flex flex-col sm:flex-row items-center justify-end gap-4 mb-8 pt-4">
        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto text-center bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm font-sans focus:outline-none">
            Cancelar
        </a>
        <button type="submit" class="w-full sm:w-auto bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-xs font-bold rounded-xl px-10 py-4 transition-all duration-200 shadow-sm hover:shadow-md font-sans text-center focus:outline-none">
            Guardar Cambios
        </button>
    </div>
</form>
@endsection