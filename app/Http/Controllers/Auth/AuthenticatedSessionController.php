<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Validar credenciales base (Correo y Contraseña)
        $request->authenticate();

        $user = $request->user();

        // 🛡️ BLINDAJE: Detección de cuenta deshabilitada
        // Asume que en tu tabla 'users' tienes un campo 'is_active' (booleano).
        // Si usas otro nombre como 'status', solo cámbialo aquí.
        if (isset($user->is_active) && $user->is_active == false) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'El acceso a esta cuenta ha sido restringido o deshabilitado por un administrador.',
            ]);
        }

        // 2. Regenerar sesión para prevenir fijación de sesión
        $request->session()->regenerate();

        // 3. Lógica de 2FA para roles privilegiados
        if ($user->hasAnyRole(['admin', 'worker'])) {
            $cookieName = 'trusted_device_' . $user->id;

            if ($request->hasCookie($cookieName)) {
                session(['2fa_verified' => true]);
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Bienvenido de nuevo, ' . $user->first_name);
            }

            $user->generateTwoFactorCode();
            return redirect()->route('2fa.index')
                ->with('info', 'Por favor, verifica el código enviado a tu correo.');
        }

        // 4. Redirección para clientes normales
        return redirect()->intended(route('home'))
            ->with('success', '¡Hola, ' . $user->first_name . '! Qué gusto tenerte de vuelta.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Has cerrado sesión exitosamente. ¡Vuelve pronto!');
    }
}