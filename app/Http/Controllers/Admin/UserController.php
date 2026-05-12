<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $roleId = $request->query('role');
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'desc');

        $query = User::query();

        // 1. Filtro de Búsqueda
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        // 2. Filtro por Rol
        if ($roleId) {
            $query->whereHas('roles', fn ($q) => $q->where('roles.id', $roleId));
        }

        // 3. Ordenamiento Dinámico Segurizado
        $allowedSorts = ['first_name', 'email', 'phone', 'created_at'];
        
        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // 4. Paginación preservando todos los parámetros de la URL
        $users = $query->with('roles')->paginate(15)->withQueryString();

        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'search', 'roles', 'roleId'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => ['required', 'email', \Illuminate\Validation\Rule::unique('users')->ignore($user->id)],
            'phone'      => 'nullable|string',
            'roles'      => 'array',
            'roles.*'    => 'exists:roles,id',
        ]);

        try {
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone'],
            ]);

            // SOLUCIÓN: Usar el método nativo de Eloquent sync() adaptado a tu tabla polimórfica
            // Si la vista no envía ningún rol (array vacío), se quitarán todos los roles del usuario.
            $user->roles()->sync($validated['roles'] ?? []);

            return redirect()->route('admin.users.index')
                             ->with('success', 'Usuario actualizado correctamente.');
                             
        } catch (\Exception $e) {
            // Regla de Oro: Manejo seguro y silencioso de errores
            // (Si necesitas depurar en el futuro, puedes usar Log::error($e->getMessage()) aquí)
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ocurrió un error al actualizar el usuario. Intenta de nuevo.');
        }
    }
}