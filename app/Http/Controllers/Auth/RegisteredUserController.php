<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\Customer;
use App\Models\Subscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Models\Role;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        try {
            $user = DB::transaction(function () use ($request) {
                
                $newUser = User::create([
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'email'      => $request->email,
                    'password'   => Hash::make($request->password),
                ]);

                // CORRECCIÓN: Volvemos a tu lógica original con Eloquent 
                // ya que tu tabla roles no usa el estándar de Spatie (sin guard_name)
                $role = Role::where('name', 'customer')->first();
                if ($role) {
                    $newUser->roles()->attach($role->id);
                } else {
                    Log::warning('Carpintec Auth - El rol "customer" no existe. El usuario ID: ' . $newUser->id . ' se creó sin rol.');
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
                    Mail::send('emails.subscriber_welcome', ['user' => $user], function ($message) use ($user) {
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