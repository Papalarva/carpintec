<?php
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use Illuminate\Http\Request;

// ─── Páginas estáticas ──────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Perfil (Breeze) ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Catálogo público ───────────────────────────────────
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// ─── Carrito de compras (público, invitado y cliente) ──
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{product:slug}', [CartController::class, 'update'])->name('cart.update');      // binding por slug
Route::delete('/carrito/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');    // binding por slug
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');

// ─── Cotizaciones (placeholder, se implementará después) ─
Route::get('/cotizar/{product}', function () {
    return redirect()->back()->with('info', 'Las cotizaciones estarán disponibles próximamente.');
})->name('quotation.request');

// ─── Direcciones (clientes autenticados) ────────────────
Route::middleware(['auth'])->group(function () {
    Route::resource('addresses', AddressController::class)->except(['show']);
    Route::post('addresses/{address}/set-primary', [AddressController::class, 'setPrimary'])
        ->name('addresses.set-primary');
});

// ─── Checkout (clientes autenticados) ───────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::delete('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');

    Route::get('/pedido/{order}/confirmacion', function (Order $order) {
        if ($order->customer_id !== request()->user()->customer->id) {
            abort(403);
        }
        return view('orders.confirmation', compact('order'));
    })->name('orders.confirmation');
});

// ─── Rutas de autenticación (Breeze) ────────────────────
require __DIR__.'/auth.php';