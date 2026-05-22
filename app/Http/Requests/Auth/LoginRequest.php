<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Buscamos al usuario INCLUYENDO los deshabilitados (Soft Deletes)
        $user = \App\Models\User::withTrashed()->where('email', $this->input('email'))->first();

        // 🛡️ FASE DE SEGURIDAD: Detectar cuenta deshabilitada/eliminada
        if ($user && $user->trashed()) {
            \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());

            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'El acceso a esta cuenta ha sido restringido o deshabilitado por un administrador.',
            ]);
        }

        // 2. Intentar el inicio de sesión nativo con las credenciales
        if (! \Illuminate\Support\Facades\Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey());

            throw \Illuminate\Validation\ValidationException::withMessages([
                // 📝 Forzamos el mensaje explícito en español aquí, anulando el inglés por defecto
                'email' => 'Estas credenciales no coinciden con nuestros registros.',
            ]);
        }

        \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! \Illuminate\Support\Facades\RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new \Illuminate\Auth\Events\Lockout($this));

        $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($this->throttleKey());
        $minutes = ceil($seconds / 60);

        // 🛡️ FASE DE SEGURIDAD: Personalizamos el mensaje de bloqueo temporal
        $mensaje = $seconds < 60 
            ? "Demasiados intentos de acceso. Por favor, inténtalo de nuevo en {$seconds} segundos."
            : "Demasiados intentos de acceso. Por favor, inténtalo de nuevo en {$minutes} minuto(s).";

        throw \Illuminate\Validation\ValidationException::withMessages([
            'email' => $mensaje,
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
