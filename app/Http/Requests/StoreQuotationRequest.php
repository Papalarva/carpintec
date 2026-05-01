<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuotationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // La ruta ya usa middleware 'auth'
    }

    public function rules()
    {
        return [
            'product_id' => 'nullable|exists:products,id', // Validamos que el producto exista
            'subject' => 'required|string|max:150',
            'description' => 'required|string|max:2000',
            // Blindaje estricto de archivos: solo imágenes y PDFs, max 5MB
            'attachments' => 'nullable|array|max:5', // Máximo 5 archivos
            'attachments.*' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:5120', 
        ];
    }
    
    public function messages(): array
    {
        return [
            'subject.required' => 'El asunto es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'attachments.*.max' => 'Cada archivo no debe superar los 5 MB.',
        ];
    }
}