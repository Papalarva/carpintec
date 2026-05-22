<?php

namespace App\Listeners;

use App\Models\Customer;
use Illuminate\Auth\Events\Registered;

class CreateCustomer
{
    public function handle(Registered $event): void
    {
        $user = $event->user;
        // Evita crear duplicado
        if (!$user->customer) {
            Customer::create([
                'user_id' => $user->id,
                'accepts_marketing' => false,
            ]);
        }
    }
}