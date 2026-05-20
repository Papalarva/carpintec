<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    protected function prepareForValidation()
    {
        // Sanitización extrema: quitamos HTML, espacios extra y capitalizamos nombres
        $this->merge([
            'first_name' => ucwords(strtolower(strip_tags(trim($this->first_name)))),
            'last_name'  => ucwords(strtolower(strip_tags(trim($this->last_name)))),
            'email'      => strtolower(strip_tags(trim($this->email))),
        ]);
    }

    public function rules(): array
    {
        // Regex: Letras (mayúsculas/minúsculas), acentos, ñ, espacios, guiones y apóstrofes. 
        // El modificador 'u' al final es vital para procesar caracteres Unicode (acentos) correctamente en PHP.
        $nameRegex = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-\']+$/u';

        return [
            'first_name'        => ['required', 'string', 'max:255', "regex:{$nameRegex}"],
            'last_name'         => ['required', 'string', 'max:255', "regex:{$nameRegex}"],
            'email'             => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:'.User::class],
            'password'          => ['required', 'confirmed', Rules\Password::defaults()],
            'accepts_marketing' => ['nullable', 'boolean'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'first_name.regex'   => 'El nombre solo puede contener letras, espacios, apóstrofes o guiones.',
            'last_name.regex'    => 'Los apellidos solo pueden contener letras, espacios, apóstrofes o guiones.',
            'email.email'        => 'Debes proporcionar una dirección de correo electrónico válida y real.',
            'email.unique'       => 'Este correo electrónico ya está registrado en Carpintec.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}