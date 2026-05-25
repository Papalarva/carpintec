<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $roleId = $request->query('role');
        $accountStatus = $request->query('account_status', 'active'); // Nuevo filtro
        $sort = $request->query('sort');
        $direction = $request->query('direction', 'desc');

        $query = User::query();

        if ($accountStatus === 'disabled') {
            $query->onlyTrashed();
        } elseif ($accountStatus === 'all') {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'ilike', "%{$search}%")
                    ->orWhere('last_name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($roleId) {
            $query->whereHas('roles', fn($q) => $q->where('roles.id', $roleId));
        }

        $allowedSorts = ['first_name', 'email', 'phone', 'created_at'];

        if ($sort && in_array($sort, $allowedSorts)) {
            $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $users = $query->with('roles')->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'search', 'roles', 'roleId', 'accountStatus'));
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

            $user->roles()->sync($validated['roles'] ?? []);

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el usuario. Intenta de nuevo.');
        }
    }

    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Por seguridad, no puedes deshabilitar tu propia cuenta activa.');
        }

        try {
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario deshabilitado correctamente del sistema.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al intentar deshabilitar al usuario. Verifica las dependencias.');
        }
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->back()->with('success', 'Usuario restaurado y habilitado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocurrió un error al intentar restaurar al usuario.');
        }
    }
}
