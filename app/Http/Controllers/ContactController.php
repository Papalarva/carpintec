<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Muestra la vista del formulario de contacto.
     */
    public function index()
    {
        // Asegúrate de que el nombre coincida con tu archivo blade (ej. contact.blade.php o contacto.blade.php)
        return view('pages.contact'); 
    }

    /**
     * Procesa y envía el correo de contacto.
     */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            // Regla estricta para teléfonos:
            'phone'   => 'nullable|string|regex:/^[\d\s\-\+\(\)]+$/|min:10|max:10',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required'    => 'Por favor, ingresa tu nombre completo.', 
            'phone.regex'      => 'El formato del teléfono no es válido. Solo se permiten números y los símbolos + - ( ).',
            'phone.min'        => 'El teléfono debe tener al menos 10 caracteres.',
            'phone.max'        => 'El teléfono no puede exceder los 20 caracteres.',
            
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'Ingresa un correo electrónico válido.',
            'subject.required' => 'Selecciona un motivo para tu mensaje.',
            'message.required' => 'No olvides escribir tu mensaje.',
        ]);

        try {
            // Utilizamos Mail::raw basándonos en tu ruta de prueba en web.php
            $body = "Nombre: {$validated['name']}\n"
                  . "Teléfono: {$validated['phone']}\n"
                  . "Correo: {$validated['email']}\n"
                  . "Asunto: {$validated['subject']}\n\n"
                  . "Mensaje:\n{$validated['message']}";

            Mail::raw($body, function ($message) use ($validated) {
                $message->to('contacto@carpintec.local')
                        ->subject('Nuevo mensaje de contacto: ' . $validated['subject'])
                        ->replyTo($validated['email']);
            });

            return back()->with('success', '¡Gracias por escribirnos! Hemos recibido tu mensaje y nos pondremos en contacto pronto.');

        } catch (\Exception $e) {
            // Registramos el error internamente para los desarrolladores
            Log::error('Error al enviar correo de contacto: ' . $e->getMessage());

            // Devolvemos un toast amigable al usuario manteniendo sus datos (withInput)
            return back()
                ->withInput()
                ->with('error', 'Ocurrió un problema de conexión al enviar tu mensaje. Por favor, intenta de nuevo más tarde.');
        }
    }
}