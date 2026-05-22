<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartManager;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\MigrateSessionCart::class,
        ],
        \Illuminate\Auth\Events\Registered::class => [
            \App\Listeners\CreateCustomer::class,
        ],
    ];
}
