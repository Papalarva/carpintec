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
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // 🚦 EL AGENTE DE TRÁNSITO
        if ($request->user()->hasAnyRole(['admin', 'worker'])) {
            
            // 👇 REVISAMOS SI TIENE LA COOKIE VIP
            $cookieName = 'trusted_device_' . $request->user()->id;
            
            if ($request->hasCookie($cookieName)) {
                // Le damos el pase directo marcando la sesión
                session(['2fa_verified' => true]);
                
                // CORRECCIÓN: Quitamos el 'intended' para forzar la entrada al panel admin.
                return redirect()->route('admin.dashboard');
            }
            
            // Si no tiene la cookie, lo mandamos al flujo normal de seguridad
            $request->user()->generateTwoFactorCode();
            return redirect()->route('2fa.index');
        }

        // CORRECCIÓN: Usamos 'intended' para los clientes, así si venían del carrito, regresan al carrito.
        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
