@extends('layouts.admin')

@section('title', 'Usuarios')
@section('header', 'Gestión de Usuarios')

@section('content')
<div x-data="{ actionUrl: '', restoreUrl: '' }">

    <div class="mb-8 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col lg:flex-row gap-4 justify-between items-center">
        <form method="GET" class="w-full flex flex-col md:flex-row gap-4">
            
            <div class="relative w-full lg:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por nombre o correo..."
                       class="block w-full pl-10 rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans bg-gray-50/50">
            </div>
            
            <div class="w-full md:w-48">
                <select name="role" onchange="this.form.submit()" class="block w-full rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans bg-gray-50/50 cursor-pointer">
                    <option value="">Todos los roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (isset($roleId) && $roleId == $role->id) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-48">
                <select name="account_status" onchange="this.form.submit()" class="block w-full rounded-xl border-gray-200 py-3.5 text-sm focus:border-amber-900 focus:ring-amber-900 shadow-sm transition-colors font-sans bg-gray-50/50 cursor-pointer">
                    <option value="active" {{ ($accountStatus ?? 'active') == 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="disabled" {{ ($accountStatus ?? '') == 'disabled' ? 'selected' : '' }}>Deshabilitados</option>
                    <option value="all" {{ ($accountStatus ?? '') == 'all' ? 'selected' : '' }}>Todos</option>
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
            <tr class="hover:bg-gray-50/50 transition-colors {{ $user->trashed() ? 'opacity-75 bg-gray-50/30' : '' }}">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 font-sans">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full {{ $user->trashed() ? 'bg-gray-200 text-gray-500' : 'bg-amber-50 text-amber-900' }} flex items-center justify-center font-bold border {{ $user->trashed() ? 'border-gray-300' : 'border-amber-100/70' }} shadow-sm text-xs tracking-wider">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                        </div>
                        <div class="flex flex-col">
                            <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                            @if($user->trashed())
                                <span class="text-[10px] font-bold uppercase tracking-widest text-rose-600 mt-0.5">Deshabilitado</span>
                            @endif
                        </div>
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
                <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center gap-1 font-sans">
                    @if($user->trashed())
                        <button @click="restoreUrl = '{{ route('admin.users.restore', $user->id) }}'; $dispatch('open-modal', 'restore-modal')" class="p-2 text-gray-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors" title="Restaurar Usuario">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                            </svg>
                        </button>
                    @else
                        <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-amber-900 hover:bg-amber-50 rounded-xl transition-colors" title="Editar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.89 1.113l-3.53 1.08 1.08-3.53a4.5 4.5 0 011.113-1.89l3.4-1.341z"></path>
                            </svg>
                        </a>
                        <button @click="actionUrl = '{{ route('admin.users.destroy', $user) }}'; $dispatch('open-modal', 'delete-modal')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Deshabilitar Usuario">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                            </svg>
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center bg-white">
                    <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"></path>
                    </svg>
                    <span class="text-base font-medium text-gray-400 font-sans">No se encontraron usuarios con esos criterios.</span>
                </td>
            </tr>
        @endforelse
    </x-admin.table>

    <div class="mt-6 font-sans">
        {{ $users->links() }}
    </div>

    <x-admin.modal id="delete-modal" title="Deshabilitar Usuario">
        <p class="text-sm text-gray-600 leading-relaxed font-sans">
            ¿Estás seguro de que deseas deshabilitar a este usuario? Perderá el acceso al sistema inmediatamente, pero sus registros históricos se mantendrán intactos.
        </p>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3 w-full font-sans">
                <button @click="$dispatch('close-modal')" type="button" class="min-w-[140px] px-6 py-3.5 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-xs uppercase tracking-widest transition-colors shadow-sm focus:outline-none">
                    Cancelar
                </button>
                <form :action="actionUrl" method="POST" class="m-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="min-w-[140px] px-6 py-3.5 bg-rose-700 text-white hover:bg-rose-800 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-200 shadow-sm focus:outline-none">
                        Deshabilitar
                    </button>
                </form>
            </div>
        </x-slot>
    </x-admin.modal>

    <x-admin.modal id="restore-modal" title="Restaurar Usuario">
        <p class="text-sm text-gray-600 leading-relaxed font-sans">
            ¿Deseas volver a habilitar a este usuario? Recuperará el acceso al panel y podrá interactuar con el sistema con los permisos que tenía asignados.
        </p>
        <x-slot name="footer">
            <div class="flex items-center justify-end gap-3 w-full font-sans">
                <button @click="$dispatch('close-modal')" type="button" class="min-w-[140px] px-6 py-3.5 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 rounded-xl font-bold text-xs uppercase tracking-widest transition-colors shadow-sm focus:outline-none">
                    Cancelar
                </button>
                <form :action="restoreUrl" method="POST" class="m-0">
                    @csrf @method('PATCH')
                    <button type="submit" class="min-w-[140px] px-6 py-3.5 bg-emerald-700 text-white hover:bg-emerald-800 rounded-xl font-bold text-xs uppercase tracking-widest transition-all duration-200 shadow-sm focus:outline-none">
                        Habilitar
                    </button>
                </form>
            </div>
        </x-slot>
    </x-admin.modal>

</div>
@endsection