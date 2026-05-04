<?php

namespace App\Models;
 
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable implements MustVerifyEmail
{
    // 👇 1. Quitamos HasApiTokens y agregamos HasUuids correctamente
    use HasFactory, Notifiable, HasRoles, HasUuids;

    // (Eliminamos $incrementing y $keyType porque el trait HasUuids ya lo hace por nosotros en automático)

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Accessor moderno para obtener el nombre completo.
     * Uso en Blade: {{ auth()->user()->full_name }}
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->first_name} {$this->last_name}",
        );
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    // Relación: Un usuario tiene muchos códigos (historial)
    public function twoFactorCodes()
    {
        return $this->hasMany(TwoFactorCode::class);
    }

    // El motor generador de códigos
    public function generateTwoFactorCode()
    {
        // 1. Borramos códigos anteriores para que no se acumule basura
        $this->twoFactorCodes()->delete();

        // 2. Generamos un código aleatorio de 6 dígitos
        $code = rand(100000, 999999);

        // 3. Lo guardamos en la base de datos con vigencia de 10 minutos
        $this->twoFactorCodes()->create([
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // 4. Enviamos el correo usando Mailtrap (Texto plano para rápido)
        \Illuminate\Support\Facades\Mail::raw(
            "Tu código de seguridad para entrar a Carpintec es: {$code}. Expira en 10 minutos.", 
            function ($message) {
                $message->to($this->email)
                        ->subject('Código de Seguridad 2FA - Carpintec');
            }
        );
    }

}