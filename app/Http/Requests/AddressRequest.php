<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    protected function prepareForValidation()
    {
        // Sanitización extrema: quitamos espacios extra y bloqueamos inyección de scripts HTML
        $this->merge([
            'postal_code'     => preg_replace('/[^0-9]/', '', $this->postal_code),
            'contact_phone'   => preg_replace('/[^0-9]/', '', $this->contact_phone),
            'state'           => strip_tags(trim($this->state)),
            'city'            => strip_tags(trim($this->city)),
            'neighborhood'    => strip_tags(trim($this->neighborhood)),
            'street'          => strip_tags(trim($this->street)),
            'exterior_number' => strip_tags(trim($this->exterior_number)),
            'interior_number' => strip_tags(trim($this->interior_number)),
            'alias'           => strip_tags(trim($this->alias)),
        ]);
    }

    public function rules(): array
    {
        return [
            // Usamos 'digits' que obliga a que sean numéricos y exactamente de esa longitud
            'postal_code'     => ['required', 'digits:5'],
            'state'           => ['required', 'string', 'max:255'],
            'city'            => ['required', 'string', 'max:255'],
            'neighborhood'    => ['required', 'string', 'max:255'],
            'street'          => ['required', 'string', 'max:255'],
            // Ampliamos a 20 para permitir formatos como "Mza 12 Lote 34" o "Km 11.5"
            'exterior_number' => ['required', 'string', 'max:20'], 
            'interior_number' => ['nullable', 'string', 'max:20'],
            'alias'           => ['nullable', 'string', 'max:100'],
            'contact_phone'   => ['nullable', 'digits:10'],
            'is_primary'      => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            // Mapeamos directamente el error de la regla 'digits'
            'postal_code.digits'    => 'El código postal debe tener exactamente 5 números.',
            'contact_phone.digits'  => 'El teléfono debe tener exactamente 10 números.',
            'neighborhood.required' => 'Es necesario indicar la colonia o asentamiento.',
            'exterior_number.max'   => 'El número exterior ingresado es demasiado largo.',
            'interior_number.max'   => 'El número interior ingresado es demasiado largo.',
        ];
    }
}