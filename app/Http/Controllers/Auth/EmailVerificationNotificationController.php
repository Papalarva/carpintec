<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        // Implementación de nuestro sistema de Toasts
        return back()->with('success', '¡Te hemos enviado un nuevo enlace de verificación! Por favor revisa tu bandeja de entrada o carpeta de spam.');
    }
}