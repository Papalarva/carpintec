<?php

namespace App\Observers;

use App\Models\QuotationMessage;
use App\Models\User;
use App\Mail\NewMessageForCustomerMail;
use App\Mail\NewMessageForAdminMail;
use Illuminate\Support\Facades\Mail;

class QuotationMessageObserver
{
    /**
     * Handle the QuotationMessage "created" event.
     */
    public function created(QuotationMessage $quotationMessage): void
    {
        // Si el mensaje lo envió el administrador, notificamos al cliente
        if ($quotationMessage->sender_type === 'admin') {
            $customerEmail = $quotationMessage->quotation->customer->user->email;
            
            \Illuminate\Support\Facades\Mail::to($customerEmail)
                ->send(new \App\Mail\NewMessageForCustomerMail($quotationMessage));
        } 
        // Si el mensaje lo envió el cliente, notificamos a los administradores
        else {
            // SOLUCIÓN: Usamos Eloquent puro en lugar del scope 'role()'
            // Asumiendo que un usuario tiene muchos roles (relación 'roles')
            $staffUsers = \App\Models\User::whereHas('roles', function ($query) {
                $query->whereIn('name', ['admin', 'worker']);
            })->get();
            
            /* * NOTA DE ARQUITECTURA: 
             * Si en tu sistema un usuario solo tiene UN rol y tu relación 
             * en el modelo User se llama 'role' (en singular), cambia 
             * 'roles' por 'role' en la línea de arriba.
             */

            if ($staffUsers->isNotEmpty()) {
                \Illuminate\Support\Facades\Mail::to($staffUsers)
                    ->send(new \App\Mail\NewMessageForAdminMail($quotationMessage));
            }
        }
    }
}