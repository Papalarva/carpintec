<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = $request->user();

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

        return redirect()->intended(route('home'))
            ->with('success', '¡Hola, ' . $user->first_name . '! Que gusto tenerte de vuelta.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Has cerrado sesión exitosamente. ¡Vuelve pronto!');
    }
}
