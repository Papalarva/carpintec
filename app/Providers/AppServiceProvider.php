<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Relation; // <-- 1. Importamos Relation

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
        // 2. Le enseñamos a Eloquent a mapear el texto 'User' de la BD a tu modelo real
        Relation::enforceMorphMap([
            'User' => \App\Models\User::class,
            'Product' => \App\Models\Product::class,
        ]);

        // Enseñamos a Blade a entender @role y @endrole
        Blade::if('role', function (string|array $roles) {
            /** @var mixed $user */
            $user = Auth::user();

            return $user !== null && method_exists($user, 'hasRole') && $user->hasRole($roles);
        });
    }
}