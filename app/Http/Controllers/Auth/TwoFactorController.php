<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric|digits:6',
            'remember_device' => 'nullable', // Validamos que exista el campo
        ]);

        $twoFactor = auth()->user()->twoFactorCodes()
            ->where('code', $request->code)
            ->first();

        if (!$twoFactor) {
            return back()->withErrors(['code' => 'El código ingresado es incorrecto.']);
        }

        if ($twoFactor->isExpired()) {
            $twoFactor->delete();
            return back()->withErrors(['code' => 'El código ha expirado. Por favor, vuelve a iniciar sesión.']);
        }

        // ¡ÉXITO! Marcamos la sesión y limpiamos la BD
        session(['2fa_verified' => true]);
        $twoFactor->delete();

        // Preparamos la respuesta de redirección
        $response = redirect()->route('admin.dashboard');

        // 👇 LA MAGIA: Si marcó la casilla, guardamos la cookie
        if ($request->filled('remember_device')) {
            // Nombre único por usuario (ej. trusted_device_59b6f7fc...)
            $cookieName = 'trusted_device_' . auth()->id();
            
            // Creamos la cookie por 43200 minutos (30 días exactos)
            $response->cookie($cookieName, true, 43200);
        }

        return $response;
    }
}