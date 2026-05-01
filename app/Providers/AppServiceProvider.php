<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartManager;
use Illuminate\Support\Facades\Blade; // <-- Agrega esta importación
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartManager::class, function () {
            return new CartManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enseñamos a Blade a entender @role y @endrole
        Blade::if('role', function (string|array $roles) {
            /** @var mixed $user */
            $user = Auth::user();

            return $user !== null && method_exists($user, 'hasRole') && $user->hasRole($roles);
        });
    }
}
