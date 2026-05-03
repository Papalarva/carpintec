@extends('layouts.admin')

@section('title', 'Usuarios')
@section('header', 'Usuarios')

@section('content')
<div class="mb-4 flex justify-between">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar..." class="rounded border-gray-300">
        
        <!-- Nuevo selector de roles -->
        <select name="role" class="rounded border-gray-300">
            <option value="">Todos los roles</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ (isset($roleId) && $roleId == $role->id) ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
            @endforeach
        </select>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded">Buscar</button>
    </form>
</div>

<x-admin.table :headers="['Nombre', 'Email', 'Teléfono', 'Roles', 'Acciones']" :rows="$users">
    @foreach($users as $user)
        <tr>
            <td class="px-6 py-4">{{ $user->first_name }} {{ $user->last_name }}</td>
            <td class="px-6 py-4">{{ $user->email }}</td>
            <td class="px-6 py-4">{{ $user->phone ?? '-' }}</td>
            <td class="px-6 py-4">
                @foreach($user->roles as $role)
                    <x-admin.badge :label="$role->name" color="blue" />
                @endforeach
            </td>
            <td class="px-6 py-4">
                <a href="{{ route('admin.users.edit', $user) }}" class="text-indigo-600 hover:underline">Editar</a>
            </td>
        </tr>
    @endforeach
</x-admin.table>

<!-- Aquí está la solución al misterio: Los controles de paginación -->
<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection