<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    } 

    public function rules(): array
    {
        return [
            'product_id'    => ['nullable', 'exists:products,id'],
            'subject'       => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string', 'max:5000'],
            'attachments'   => ['nullable', 'array', 'max:5'], // Máximo 5 archivos por solicitud
            'attachments.*' => [
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120', // 5MB en kilobytes
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.*.mimes' => 'Solo se permiten imágenes (JPG, PNG) o documentos PDF.',
            'attachments.*.max'   => 'Cada archivo no debe superar los 5MB.',
            'attachments.max'     => 'No puedes subir más de 5 archivos a la vez.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'subject'     => strip_tags($this->input('subject', '')),
            'description' => strip_tags($this->input('description', '')),
        ]);
    }
}