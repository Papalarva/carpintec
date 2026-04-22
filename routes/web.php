<?php

use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Ruta de login personalizada (pantalla de selección de rol + auth)
Route::view('/acceso', 'auth.login')->name('carpintec.login');

// Tienda (requiere autenticación)
Route::middleware(['auth'])->group(function () {
    Route::view('/tienda', 'shop.index')->name('shop.index');
    Route::view('/admin', 'admin.index')->name('admin.index');

    // Pedidos
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
