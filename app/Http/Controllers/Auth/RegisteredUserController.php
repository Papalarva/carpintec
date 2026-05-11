<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password'   => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required'  => 'Los apellidos son obligatorios.',
            'email.required'      => 'El correo electrónico es obligatorio.',
            'email.unique'        => 'Este correo ya está registrado. Por favor, inicia sesión.',
            'password.required'   => 'La contraseña es obligatoria.',
            'password.confirmed'  => 'Las contraseñas no coinciden.',
            'password.min'        => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                
                $newUser = User::create([
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'password'   => Hash::make($request->password), 
                ]);
                $newUser->customer()->create([
                    'accepts_marketing' => false, 
                ]);

                return $newUser;
            });

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('home')
                ->with('success', '¡Bienvenido a Carpintec! Tu cuenta ha sido creada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error en registro de usuario: ' . $e->getMessage());
            
            return back()->withInput()->with('error', 'Ocurrió un error al crear tu cuenta. Por favor, intenta de nuevo.');
        }
    }
}