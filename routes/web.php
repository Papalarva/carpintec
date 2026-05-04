<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use Illuminate\Http\Request;

// ─── Controladores Públicos ─────────────────────────────
use App\Http\Controllers\QuotationController;

// ─── Controladores de Administración ────────────────────
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Auth\TwoFactorController;
 
// ─── Página principal (Tienda / Catálogo) ───────────────
Route::get('/', [CatalogController::class, 'index'])->name('home');

// ─── TÚNEL DE REDIRECCIÓN INTELIGENTE (Soluciona el error de Breeze) ───
Route::get('/dashboard', function () {
    // Si es un empleado, lo mandamos a su panel de control
    if (auth()->check() && auth()->user()->hasAnyRole(['admin', 'worker'])) {
        return redirect()->route('admin.dashboard');
    }
    
    // Si es un cliente normal, lo mandamos a la tienda
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// ─── Perfil (Breeze) ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Catálogo público ───────────────────────────────────
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

// ─── Carrito de compras (público, invitado y cliente) ───
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');

// ─── Checkout (clientes autenticados) ───────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::delete('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
    Route::get('/pedido/{order}/confirmacion', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');
});

// ─── Cotizaciones personalizadas (Público) ──────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/cotizar/{product?}', [QuotationController::class, 'create'])->name('quotation.request');
    Route::post('/cotizaciones', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/cotizaciones', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/cotizaciones/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/cotizaciones/{quotation}/adjunto/{filename}', [QuotationController::class, 'downloadAttachment'])->name('quotations.download');
});

// ==========================================
// 🔓 ZONA COMPARTIDA: ADMINS Y WORKERS
// ==========================================
// Cambia el pipe (|) por una coma (,)
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin,worker']], function () {    
    // El dashboard de admin debe estar aquí para que el worker tenga a dónde entrar
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('quotations', AdminQuotationController::class); // ¡Corregido!
    Route::resource('orders', OrderController::class);
});

// ==========================================
// 🔒 ZONA EXCLUSIVA: SOLO ADMINS
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin']], function () {
    
    // Rutas de administración que faltaban
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/{product}/movements', [InventoryController::class, 'showMovements'])->name('inventory.movements');
    Route::get('inventory/{product}/adjust', [InventoryController::class, 'createAdjustment'])->name('inventory.adjust');
    Route::post('inventory/{product}/adjust', [InventoryController::class, 'storeAdjustment'])->name('inventory.store-adjustment');

    Route::resource('discounts', DiscountController::class);
    Route::resource('coupons', CouponController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    
});

// ─── Verificación 2FA (Requiere estar logueado pero no verificado aún) ───
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
});

// ─── Pruebas ────────────────────────────────────────────
Route::get('/test-mail', function () {
    \Illuminate\Support\Facades\Mail::raw(
        '¡Hola! Conexión Mailtrap funciona.', 
        function ($message) {
            $message->to('prueba@carpintec.local')->subject('Prueba de Conexión');
        }
    );
    return 'Revisa tu bandeja.';
});

// ─── Rutas de autenticación (Breeze) ────────────────────
require __DIR__ . '/auth.php';