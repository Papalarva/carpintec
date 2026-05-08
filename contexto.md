## Contexto del Proyecto de Carpintec

### Rutas definidas en web.php
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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// ─── Catálogo y Carrito ─────────────────────────────────
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/agregar/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/carrito/{product:slug}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrito/{product:slug}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/carrito/count', [CartController::class, 'count'])->name('cart.count');

// ─── Checkout y Cotizaciones (Público autenticado) ──────
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.apply-coupon');
    Route::delete('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.remove-coupon');
    Route::get('/pedido/{order}/confirmacion', [CheckoutController::class, 'confirmation'])->name('orders.confirmation');

    Route::get('/cotizar/{product?}', [QuotationController::class, 'create'])->name('quotation.request');
    Route::post('/cotizaciones', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('/cotizaciones', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('/cotizaciones/{quotation}', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('/cotizaciones/{quotation}/adjunto/{filename}', [QuotationController::class, 'downloadAttachment'])->name('quotations.download');
});

// ==========================================
// 🔓 ZONA COMPARTIDA: ADMINS Y WORKERS
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin,worker']], function () {    
    // Aquí vive tu panel inteligente. Su URL es /admin y su nombre es admin.dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('quotations', AdminQuotationController::class);
    Route::resource('orders', OrderController::class);
});

// ==========================================
// 🔒 ZONA EXCLUSIVA: SOLO ADMINS
// ==========================================
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:admin']], function () {
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

// ─── Verificación 2FA ───────────────────────────────────
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

require __DIR__ . '/auth.php';

### Estándares de Codificación 

Estándares de Backend (PHP / Laravel)
Para asegurar que todos los miembros del equipo programáramos de forma ordenada y que nuestro proyecto ("Carpintec") no se convirtiera en un código difícil de entender o mantener en el futuro, tomamos la decisión de establecer estándares de codificación estrictos.
Nuestra principal motivación fue aplicar la regla DRY (Don't Repeat Yourself) y mantener la seguridad del sistema en todo momento. A continuación, explicamos las decisiones más importantes que tomamos, junto con ejemplos reales de nuestro código:
Controladores "Delgados" y Validación Estricta
Decidimos que el Controlador nunca debe confiar en la información que envía el usuario. Por ello, establecimos como regla que todo formulario debe ser validado en las primeras líneas de código. Si la información es incorrecta o está duplicada, el sistema detiene el proceso inmediatamente y devuelve un mensaje amigable en español, evitando que la base de datos falle.

 
Código 1 
Ejemplo en CategoryController.php:
public function store(Request $request)
{
    // Regla de equipo: Siempre validar antes de guardar
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name',
    ], [
        // Mensaje personalizado en español para el usuario
        'name.unique' => 'Ya existe una categoría registrada con este nombre. Por favor, elige otro.',
        'name.required' => 'El nombre de la categoría es obligatorio.',
    ]);

    Category::create($validated);
    
    return redirect()->route('admin.categories.index')
                     ->with('success', 'Categoría creada exitosamente.');
}

Manejo Silencioso y Seguro de Errores
Para mantener una experiencia de usuario profesional (estilo SaaS) y proteger la estructura de la base de datos ante posibles ataques, decidimos prohibir que se muestren pantallas de error rojas con código SQL. Establecimos el estándar de usar bloques try/catch para atrapar los errores críticos (como intentar borrar una categoría que aún tiene productos) y transformarlos en notificaciones flotantes (Toasts).
 
Código 2
Ejemplo de manejo de la regla de Llave Foránea en CategoryController.php:
public function destroy(Category $category)
{
    try {
        $category->delete();
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría eliminada correctamente.');

    } catch (\Illuminate\Database\QueryException $e) {
        // Atrapamos el error de integridad referencial para evitar que el sistema colapse
        if ($e->getCode() == '23503') {
            return redirect()->route('admin.categories.index')
                             ->with('error', 'No se puede eliminar esta categoría porque aún tiene productos asociados.');
        }
    }
}
Vistas Basadas en Componentes  
En lugar de copiar y pegar el mismo código HTML de tablas o ventanas modales en cada una de nuestras pantallas, decidimos utilizar los Componentes de Blade. Esto significa que programamos el diseño de una tabla o un botón una sola vez y lo mandamos a llamar en donde lo necesitemos. Si algún día decidimos cambiar el color de las tablas de toda la plataforma, solo editamos un archivo y el cambio se refleja en todo el proyecto.
Código 3
Ejemplo del uso de componentes en index.blade.php
<x-admin.table :headers="['Código', 'Descuento', 'Usos', 'Expiración', 'Acciones']">
    @foreach($coupons as $coupon)
        <tr>
            <td class="px-6 py-4 font-mono">{{ $coupon->code }}</td>
            <td class="px-6 py-4">
                <x-admin.badge color="terracota" :label="$coupon->is_active ? 'Activo' : 'Inactivo'" />
            </td>
        </tr>
    @endforeach
</x-admin.table>

Idioma y Nomenclatura Estandarizada
Decidimos utilizar inglés para el código interno y la base de datos, y español únicamente para las vistas (lo que ve el usuario).
•	Las variables y métodos los escribimos en camelCase (ej. $totalRevenue, $lowStockProducts).
•	Los modelos y controladores los escribimos en PascalCase y en singular (ej. CategoryController en lugar de CategoriesController).
Esta decisión nos permite mantener el código limpio y compatible con los estándares globales de desarrollo de la comunidad de Laravel.
