<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Subscriber;
use Illuminate\View\View;
use Exception;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $newlySubscribed = false;
            $user = $request->user();

            DB::transaction(function () use ($request, $user, &$newlySubscribed) {
                $oldEmail = $user->email;

                $user->fill($request->validated());

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                    Subscriber::where('email', $oldEmail)->update(['email' => $user->email]);
                }

                $user->save();

                $acceptsMarketing = $request->boolean('accepts_marketing');

                if ($user->customer) {
                    $user->customer->update(['accepts_marketing' => $acceptsMarketing]);
                }

                if ($acceptsMarketing) {
                    $subscriber = Subscriber::updateOrCreate(
                        ['email' => $user->email],
                        ['customer_id' => $user->customer?->id, 'is_active' => true]
                    );

                    if ($subscriber->wasRecentlyCreated) {
                        $newlySubscribed = true;
                    }
                } else {
                    Subscriber::where('email', $user->email)->delete();
                }
            });

            if ($newlySubscribed) {
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
                    Log::error('Carpintec Correo Error (Perfil) - No se pudo enviar bienvenida a ' . $user->email . '. Detalles: ' . $mailException->getMessage());
                }
            }

            return Redirect::route('profile.edit')->with('success', 'Tu perfil ha sido actualizado correctamente.');
        } catch (Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Ocurrió un error al actualizar tu perfil. Por favor, inténtalo de nuevo.');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            \App\Models\User::withoutEvents(function () use ($user) {
                $user->delete();
            });

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('info', 'Tu cuenta ha sido eliminada permanentemente. Esperamos verte de nuevo.');
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Error interno al intentar eliminar la cuenta.');
        }
    }
}
