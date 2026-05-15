<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La autorización de propiedad la seguimos manejando en el controlador
        return true;
    }

    protected function prepareForValidation(): void
    {
        // 1. Sanitización de Datos (Previene XSS limpiando etiquetas HTML/Scripts)
        // 2. Fusión de campos: Si la API falló, tomamos la colonia del input manual
        $neighborhood = $this->neighborhood ?: $this->neighborhood_manual;

        $this->merge([
            'alias'           => strip_tags($this->alias),
            'street'          => strip_tags($this->street),
            'exterior_number' => strip_tags($this->exterior_number),
            'interior_number' => strip_tags($this->interior_number),
            'neighborhood'    => strip_tags($neighborhood),
            'city'            => strip_tags($this->city),
            'state'           => strip_tags($this->state),
        ]);
    }

    public function rules(): array
    {
        return [
            'alias'           => ['nullable', 'string', 'max:100'],
            'street'          => ['required', 'string', 'max:255'],
            'exterior_number' => ['required', 'string', 'max:20'],
            'interior_number' => ['nullable', 'string', 'max:20'],
            'neighborhood'    => ['required', 'string', 'max:255'],
            'city'            => ['required', 'string', 'max:255'],
            'state'           => ['required', 'string', 'max:255'],
            // Regex: Exactamente 5 dígitos numéricos
            'postal_code'     => ['required', 'string', 'regex:/^[0-9]{5}$/'],
            // Regex: Entre 10 y 15 dígitos numéricos
            'contact_phone'   => ['nullable', 'string', 'regex:/^[0-9]{10,15}$/'],
            'is_primary'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.regex'   => 'El código postal debe contener exactamente 5 dígitos.',
            'contact_phone.regex' => 'El teléfono de contacto debe contener entre 10 y 15 números válidos.',
        ];
    }
}