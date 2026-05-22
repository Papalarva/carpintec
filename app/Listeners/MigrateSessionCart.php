<?php

namespace App\Listeners;

use App\Services\CartManager;
use Illuminate\Auth\Events\Login;

class MigrateSessionCart
{
    public function __construct(protected CartManager $cartManager) {}

    public function handle(Login $event): void
    {
        $user = $event->user;
        // Solo para clientes que tienen registro en customers
        if ($user->customer) {
            $this->cartManager->migrateSessionToDatabase($user->customer);
        }
    }
}