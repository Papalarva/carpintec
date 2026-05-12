<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // <-- No olvides importar el modelo Role
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // <-- Para registrar errores silenciosamente
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'   => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        // SOLUCIÓN: Asignación nativa de Eloquent con manejo de errores silencioso
        try {
            $role = Role::where('name', 'customer')->first();
            
            if ($role) {
                // Usamos attach() porque es una inserción limpia para un usuario nuevo
                $user->roles()->attach($role->id);
            }
        } catch (\Exception $e) {
            // Regla de Oro: Manejo seguro y silencioso de errores. 
            // Registramos el error internamente pero no rompemos el registro del cliente.
            Log::error('Carpintec Auth Error - No se pudo asignar el rol inicial al usuario: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        // Opcional: Podríamos agregar nuestro sistema de Toasts aquí: ->with('success', '¡Bienvenido a Carpintec!')
        return redirect(route('home', absolute: false));
    }
}