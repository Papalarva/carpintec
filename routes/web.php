<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use App\Models\User;  
use Illuminate\Http\Request;  

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/carrito/agregar/{product}', function () {
    return redirect()->back()->with('info', 'El carrito estará disponible próximamente.');
})->name('cart.add');

Route::get('/cotizar/{product}', function () {
    return redirect()->back()->with('info', 'Las cotizaciones estarán disponibles próximamente.');
})->name('quotation.request');

// Carrito
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');

Route::middleware(['auth'])->group(function () {
    Route::resource('addresses', AddressController::class)->except(['show']);
    Route::post('addresses/{address}/set-primary', [AddressController::class, 'setPrimary'])
        ->name('addresses.set-primary');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::delete('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');

    Route::get('/pedido/{order}/confirmacion', function (Request $request, Order $order) {
        
        /** @var User $user */
        $user = $request->user();

        // Ahora el editor sabe perfectamente quién es $user y qué es ->customer
        if ($order->customer_id !== $user->customer->id) {
            abort(403);
        }
        
        return view('orders.confirmation', compact('order'));
        
    })->name('orders.confirmation');
});

require __DIR__.'/auth.php';
