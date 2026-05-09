<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Exception;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();
            $user->fill($request->validated());

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('success', 'Tu perfil ha sido actualizado correctamente.');
        } catch (Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Ocurrió un error al actualizar tu perfil. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Valida que la contraseña sea correcta
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            // 2. Realizamos el Borrado Lógico (Soft Delete)
            // Usamos withoutEvents para que el paquete de roles (Spatie)
            // no intente buscar la tabla de permisos que no existe en tu BD.
            \App\Models\User::withoutEvents(function () use ($user) {
                $user->delete(); 
            });

            // 3. Destruimos la sesión local
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('success', 'Tu cuenta ha sido eliminada permanentemente.');
            
        } catch (\Exception $e) {
            return Redirect::route('profile.edit')->with('error', 'Error interno: ' . $e->getMessage());
        }
    }
}