<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriberController;

// ─── Controladores de Administración ────────────────────
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController; // Importado correctamente
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
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\OrderController as CustomerOrderController; // Para evitar conflicto de nombres
use Illuminate\Support\Facades\Mail;
use App\Mail\TestQuoteMail;

// ─── Página principal (Tienda / Catálogo) ───────────────
Route::get('/', [CatalogController::class, 'index'])->name('home');

// ─── TÚNEL DE REDIRECCIÓN INTELIGENTE ───────────────────
// Esta ruta MANTIENE el nombre 'dashboard' para que Breeze no falle
Route::get('/dashboard', function () {
    if (auth()->check() && auth()->user()->hasAnyRole(['admin', 'worker'])) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Perfil (Breeze) ────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Candado en la eliminación de la cuenta
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy')
        ->middleware('password.confirm');
});

// ─── Catálogo y Carrito ─────────────────────────────────
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');

// ─── Colecciones  ───────────────────────────────
Route::get('/colecciones', [App\Http\Controllers\CollectionController::class, 'index'])->name('collections.index');
Route::get('/colecciones/{collection:slug}', [App\Http\Controllers\CollectionController::class, 'show'])->name('collections.show');
Route::get('/novedades', [App\Http\Controllers\CollectionController::class, 'newest'])->name('collections.newest');

Route::view('/sobre-nosotros', 'pages.about')->name('about');
Route::view('/preguntas-frecuentes', 'pages.faq')->name('faq');
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [ContactController::class, 'send'])->name('contact.send');
Route::view('/garantia', 'pages.warranty')->name('warranty');
Route::view('/envios-y-entregas', 'pages.shipping')->name('shipping');
Route::view('/terminos', 'pages.terms')->name('terms');
Route::view('/privacidad', 'pages.privacy')->name('privacy');
Route::post('/newsletter/subscribe', [SubscriberController::class, 'store'])->name('newsletter.subscribe');
Route::get('/mis-compras', [CustomerOrderController::class, 'index'])->name('orders.index');
Route::get('/mis-compras/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');

// ─── Checkout y Cotizaciones (Público autenticado) ──────
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::delete('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
    Route::get('/pedido/{order}/confirmacion', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');

    Route::get('/cotizar/{product?}', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('/cotizaciones', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/cotizaciones', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/cotizaciones/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/cotizaciones/{quotation}/adjunto/{mediaId}', [QuotationController::class, 'downloadAttachment'])->name('quotations.download');

    Route::post('/cotizaciones/{quotation}/mensaje', [QuotationController::class, 'sendMessage'])->name('quotations.message');
    Route::get('/cotizaciones/{quotation}/checkout', [QuotationController::class, 'checkout'])->name('quotations.checkout');
    Route::post('/cotizaciones/{quotation}/checkout', [QuotationController::class, 'processCheckout'])->name('quotations.process-checkout');
    Route::post('/cotizaciones/{quotation}/convertir-a-pedido', [QuotationController::class, 'convertToOrder'])->name('quotations.convert-to-order');
    Route::patch('/direcciones/{address}/principal', [AddressController::class, 'setPrimary'])
        ->name('addresses.set-primary')
        ->middleware('password.confirm');

    // Separamos el método destroy del resource para protegerlo
    Route::resource('addresses', AddressController::class)->except(['destroy']);

    // Candado en la eliminación de direcciones
    Route::delete('addresses/{address}', [AddressController::class, 'destroy'])
        ->name('addresses.destroy')
        ->middleware('password.confirm');
});

// ==========================================
// 🔓 ZONA COMPARTIDA: ADMINS Y WORKERS
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin|worker']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de Papelera para Categorías
    Route::post('categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    Route::resource('categories', CategoryController::class);

    // Rutas de Papelera para Productos
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.force-delete');
    Route::resource('products', ProductController::class);

        // Inventario
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/{product}/movements', [InventoryController::class, 'showMovements'])->name('inventory.movements');
    Route::get('inventory/{product}/adjust', [InventoryController::class, 'createAdjustment'])->name('inventory.adjust');
    Route::post('inventory/{product}/adjust', [InventoryController::class, 'storeAdjustment'])->name('inventory.store-adjustment');


    Route::resource('quotations', AdminQuotationController::class)->only(['index', 'show', 'edit', 'update']);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);

    Route::resource('collections', CollectionController::class);
});

// ==========================================
// 🔒 ZONA EXCLUSIVA: SOLO ADMINS
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin']], function () {
    Route::patch('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    // --- RUTAS FALTANTES RECUPERADAS: COTIZACIONES ADMIN ---
    Route::put('quotations/{quotation}/update-status', [AdminQuotationController::class, 'updateStatus'])->name('quotations.update-status');
    Route::post('quotations/{quotation}/convert-to-order', [AdminQuotationController::class, 'convertToOrder'])->name('quotations.convert-to-order');
    Route::get('quotations/{quotation}/file/{media}', [AdminQuotationController::class, 'downloadFile'])->name('quotations.download-file');
    Route::post('quotations/{quotation}/message', [AdminQuotationController::class, 'sendMessage'])->name('quotations.message');
    
    // --- RUTAS FALTANTES RECUPERADAS: ÓRDENES ADMIN ---
    Route::put('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::put('orders/{order}/update-shipment', [OrderController::class, 'updateShipment'])->name('orders.update-shipment');
    Route::put('orders/{order}/payments/{payment}/approve', [OrderController::class, 'approvePayment'])->name('orders.approve-payment');

    // Descuentos, Cupones y Reportes
    Route::resource('discounts', DiscountController::class);
    Route::resource('coupons', CouponController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
});

// ─── Verificación 2FA ───────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa', [TwoFactorController::class, 'index'])->name('2fa.index');
    Route::post('/2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');
    Route::post('/2fa/resend', [TwoFactorController::class, 'resend'])->name('2fa.resend');
});


require __DIR__ . '/auth.php';
