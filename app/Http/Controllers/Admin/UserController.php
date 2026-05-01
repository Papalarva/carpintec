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

        $users = User::query()
            ->when($search, fn ($q) =>
                $q->where('first_name', 'ilike', "%{$search}%")
                  ->orWhere('last_name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
            )
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends(['search' => $search]);

        return view('admin.users.index', compact('users', 'search'));
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
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone'      => 'nullable|string',
            'roles'      => 'array',
            'roles.*'    => 'exists:roles,id',
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
        ]);

        // Sincronizar roles
        $user->roles()->sync($validated['roles'] ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado.');
    }
}