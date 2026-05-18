@extends('layouts.admin')

@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

@section('content')
<div class="mb-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
    <form method="GET" class="w-full flex flex-col md:flex-row gap-4">
        
        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                </svg>
            </div>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o correo..."
                   class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans">
        </div>
        
        <div class="w-full md:w-64">
            <select name="role" onchange="this.form.submit()" class="block w-full rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans bg-gray-50/50 cursor-pointer">
                <option value="">Todos los roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ (isset($roleId) && $roleId == $role->id) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<x-admin.table :headers="[
    'Nombre' => 'first_name', 
    'Email' => 'email', 
    'Teléfono' => 'phone', 
    'Roles' => null, 
    'Acciones' => null
]">
    @forelse($users as $user)
        <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 font-sans">
                <div class="flex items-center gap-3">
                    <div class="h-9 w-9 rounded-full bg-amber-50 flex items-center justify-center text-amber-900 font-bold border border-amber-100/70 shadow-sm text-xs tracking-wider">
                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                    </div>
                    <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium font-sans">
                {{ $user->email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium font-sans">
                {{ $user->phone ?? '—' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-wrap gap-1.5">
                    @forelse($user->roles as $role)
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200 font-sans tracking-wide">
                            {{ $role->name }}
                        </span>
                    @empty
                        <span class="text-gray-400 text-xs italic font-sans">Sin rol asignado</span>
                    @endforelse
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center font-sans">
                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-xl transition-colors" title="Editar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z"></path>
                    </svg>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-16 text-center bg-white">
                <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                </svg>
                <span class="text-base font-medium text-gray-400 font-sans">No se encontraron usuarios registrados.</span>
            </td>
        </tr>
    @endforelse
</x-admin.table>

<div class="mt-6 font-sans">
    {{ $users->links() }}
</div>
@endsection