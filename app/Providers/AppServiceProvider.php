<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Relation; 
use App\Models\QuotationMessage;
use App\Observers\QuotationMessageObserver;
// ¡Nuevas importaciones necesarias!
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{ 
    public function register(): void
    {
        $this->app->singleton(CartManager::class, function () {
            return new CartManager();
        });
    } 

    public function boot(): void
    {
        Relation::enforceMorphMap([
            'User' => \App\Models\User::class,
            'Product' => \App\Models\Product::class,
            'Quotation' => \App\Models\Quotation::class, 
            'quotation_message' => \App\Models\QuotationMessage::class,
        ]);

        Blade::if('role', function (string|array $roles) {
            $user = Auth::user();
            return $user !== null && method_exists($user, 'hasRole') && call_user_func([$user, 'hasRole'], $roles);
        }); 

        // Registro de Observers
        QuotationMessage::observe(QuotationMessageObserver::class);
        Order::observe(OrderObserver::class); 
    }

    public function hasRole($roles): bool
    {
        $user = Auth::user();

        return $user !== null && method_exists($user, 'hasRole') && call_user_func([$user, 'hasRole'], $roles);
    }
}