<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwoFactorController extends Controller
{
    public function index()
    {
        return view('auth.2fa');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code'            => 'required|numeric|digits:6',
            'remember_device' => 'nullable',
        ]);

        $user = auth()->user();
        $twoFactor = $user->twoFactorCodes()
            ->where('code', $request->code)
            ->first();

        if (!$twoFactor) {
            return back()->withErrors(['code' => 'El código de seguridad ingresado es incorrecto.']);
        }

        if ($twoFactor->isExpired()) {
            $twoFactor->delete();
            return back()->withErrors(['code' => 'El código ha expirado. Por favor, solicita uno nuevo.']);
        }

        // Verificación exitosa
        session(['2fa_verified' => true]);
        $twoFactor->delete();
        
        $response = redirect()->route('admin.dashboard')
            ->with('success', 'Verificación exitosa. Bienvenido de nuevo, ' . $user->first_name . '.');
            
        if ($request->filled('remember_device')) {
            $cookieName = 'trusted_device_' . $user->id;
            // Guardar cookie por 30 días (43200 minutos)
            $response->cookie($cookieName, true, 43200);
        }

        return $response;
    }

    public function resend(Request $request)
    {
        $user = auth()->user();
        
        try {
            // Eliminar códigos anteriores para evitar confusiones
            $user->twoFactorCodes()->delete();
            
            // Generar y enviar nuevo código (asume que tu método ya maneja el Mail)
            $user->generateTwoFactorCode();
            
            return back()->with('info', 'Hemos enviado un nuevo código de seguridad a tu correo.');
        } catch (\Exception $e) {
            Log::error('Carpintec 2FA Error - No se pudo reenviar el código: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al intentar reenviar el código.');
        }
    }
}