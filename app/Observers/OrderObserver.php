<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\NewOrderAdminNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderObserver
{ 
    public $afterCommit = true; 

    public function created(Order $order)
    {
        try { 
            $order->load(['items.product', 'customer.user']);
 
            $adminEmail = config('mail.from.address', 'ventas@carpintec.com');
 
            Mail::to($adminEmail)->send(new NewOrderAdminNotification($order));
            
        } catch (\Exception $e) {
            Log::error('Error al encolar notificación de nuevo pedido: ' . $e->getMessage());
        }
    }
}