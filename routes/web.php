<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\CouponController;


// ─── Controladores Públicos ─────────────────────────────
use App\Http\Controllers\QuotationController;

// ─── Controladores de Administración ────────────────────
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuotationController as AdminQuotationController; // EL ALIAS CLAVE

// ─── Páginas estáticas ──────────────────────────────────
Route::get('/', function () {
    return view('welcome');
});

// ─── Dashboard Cliente (Breeze) ─────────────────────────
Route::get('/dashboard', function () {
    return view('dashboard');
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

// ─── RUTAS DE ADMINISTRACIÓN ────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,worker'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Categorías y productos (trabajadores y admin)
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    // Productos - rutas adicionales (soft deletes)
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');

    // Rutas exclusivas para admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update']);
        Route::resource('roles', RoleController::class)->except(['show']);

        // 🚀 AHORA SÍ USAMOS EL ALIAS DEL ADMIN AQUÍ ABAJO
        Route::resource('quotations', AdminQuotationController::class)->only(['index', 'show']);
        Route::put('quotations/{quotation}/update-status', [AdminQuotationController::class, 'updateStatus'])->name('quotations.update-status');
        Route::post('quotations/{quotation}/convert-to-order', [AdminQuotationController::class, 'convertToOrder'])->name('quotations.convert-to-order');
        Route::get('quotations/{quotation}/file/{media}', [AdminQuotationController::class, 'downloadFile'])->name('quotations.download-file');

        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::put('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::put('orders/{order}/update-shipment', [OrderController::class, 'updateShipment'])->name('orders.update-shipment');
        Route::put('orders/{order}/payments/{payment}/approve', [OrderController::class, 'approvePayment'])->name('orders.approve-payment');

        // Inventario
        Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/{product}/movements', [InventoryController::class, 'showMovements'])->name('inventory.movements');
        Route::get('inventory/{product}/adjust', [InventoryController::class, 'createAdjustment'])->name('inventory.adjust');
        Route::post('inventory/{product}/adjust', [InventoryController::class, 'storeAdjustment'])->name('inventory.store-adjustment');

        // Descuentos
        Route::resource('discounts', DiscountController::class);

        // Cupones
        Route::resource('coupons', CouponController::class);

        // Reportes
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    });
});

// ─── Rutas de autenticación (Breeze) ────────────────────
require __DIR__ . '/auth.php';
