<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Subscriber;
use App\Models\Role;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'        => ['required', 'string', 'max:255'],
            'last_name'         => ['required', 'string', 'max:255'],
            'email'             => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'accepts_marketing' => ['nullable', 'boolean'],
        ]);

        try {
            $user = DB::transaction(function () use ($request) {
                
                $newUser = User::create([
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'password'   => Hash::make($request->password),
                ]);

                // CORRECCIÓN: Volvemos a tu método original de Eloquent
                // para evitar el requerimiento de 'guard_name' de Spatie
                $role = Role::where('name', 'customer')->first();
                if ($role) {
                    $newUser->roles()->attach($role->id);
                } else {
                    Log::warning('Carpintec Auth - El rol "customer" no existe en la BD. El usuario ID: ' . $newUser->id . ' se creó sin rol.');
                }

                $acceptsMarketing = $request->boolean('accepts_marketing');
                
                $customer = Customer::create([
                    'user_id'           => $newUser->id,
                    'accepts_marketing' => $acceptsMarketing,
                ]);

                if ($acceptsMarketing) {
                    Subscriber::create([
                        'email'       => $newUser->email,
                        'customer_id' => $customer->id,
                        'is_active'   => true,
                    ]);
                }

                return $newUser;
            });

            if ($request->boolean('accepts_marketing')) {
                try {
                    $body = "¡Hola {$user->first_name}!\n\n"
                          . "Gracias por suscribirte al newsletter oficial de Carpintec.\n"
                          . "A partir de ahora, serás de los primeros en conocer nuestras nuevas colecciones, promociones exclusivas y el trabajo artesanal de nuestro taller.\n\n"
                          . "Saludos,\nEl equipo de Carpintec.";

                    Mail::raw($body, function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('¡Bienvenido al Newsletter de Carpintec!');
                    });
                } catch (\Exception $mailException) {
                    Log::error('Carpintec Correo Error - No se pudo enviar el correo a ' . $user->email . '. Detalles: ' . $mailException->getMessage());
                }
            }

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('home', absolute: false))
                ->with('success', '¡Bienvenido a Carpintec! Tu cuenta ha sido creada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Carpintec Auth Error - Fallo en la creación de cuenta: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un problema al crear tu cuenta. Por favor, intenta nuevamente.');
        }
    }
}