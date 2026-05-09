<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    /**
     * Guarda un nuevo correo en el newsletter, sincroniza y envía bienvenida.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Por favor, ingresa tu correo electrónico.',
            'email.email'    => 'Ingresa un formato de correo válido.',
        ]);

        try {
            // Guardamos el resultado de la transacción en una variable
            $subscriber = DB::transaction(function () use ($validated) {
                $email = strtolower($validated['email']);
                $customerId = null;

                if (Auth::check() && Auth::user()->customer) {
                    $customer = Auth::user()->customer;
                    $customer->update(['accepts_marketing' => true]);
                    $customerId = $customer->id;
                    $email = Auth::user()->email; 
                } else {
                    $user = User::where('email', $email)->first();
                    if ($user && $user->customer) {
                        $user->customer->update(['accepts_marketing' => true]);
                        $customerId = $user->customer->id;
                    }
                }

                // updateOrCreate devuelve el modelo actualizado o recién creado
                return Subscriber::updateOrCreate(
                    ['email' => $email],
                    [
                        'customer_id' => $customerId,
                        'is_active'   => true
                    ]
                );
            });

            // LA MAGIA DEL CORREO: Solo lo enviamos si es un suscriptor completamente nuevo
            if ($subscriber->wasRecentlyCreated) {
                $body = "¡Hola!\n\n"
                      . "Gracias por suscribirte al newsletter oficial de Carpintec.\n"
                      . "A partir de ahora, serás de los primeros en conocer nuestras nuevas colecciones, promociones exclusivas y el trabajo artesanal de nuestro taller.\n\n"
                      . "Saludos,\nEl equipo de Carpintec.";

                Mail::raw($body, function ($message) use ($subscriber) {
                    $message->to($subscriber->email)
                            ->subject('¡Bienvenido al Newsletter de Carpintec!');
                });
            }

            return back()->with('success', '¡Gracias por suscribirte! Revisa tu bandeja de entrada.');

        } catch (\Exception $e) {
            Log::error('Error al registrar suscriptor o enviar correo: ' . $e->getMessage());
            
            return back()->with('error', 'Ocurrió un error al procesar tu suscripción. Por favor, intenta de nuevo.');
        }
    }
}