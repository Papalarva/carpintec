@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('header', 'Editar Usuario')

@section('content')
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Nombre</label>
            <input name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Apellido</label>
            <input name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Teléfono</label>
            <input name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded border-gray-300">
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-medium">Roles</h3>
        <div class="grid grid-cols-2 gap-2 mt-2">
            @foreach($roles as $role)
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                           @if(in_array($role->id, old('roles', $userRoles))) checked @endif
                           class="rounded border-gray-300 text-indigo-600">
                    <span>{{ $role->name }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Guardar cambios</button>
        <a href="{{ route('admin.users.index') }}" class="ml-2 text-gray-600 hover:underline">Cancelar</a>
    </div>
</form>
@endsection