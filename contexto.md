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

### Contexto de Componentes Blade y Vistas

La capa de vistas del proyecto está construida principalmente con **Blade components**. La idea general es dividir la interfaz en piezas pequeñas y reutilizables para evitar repetir HTML, clases Tailwind y lógica visual en cada pantalla.

#### 1. Tipos de componentes que existen

- **Componentes de layout con clase PHP**: `app/View/Components/AppLayout.php` y `app/View/Components/GuestLayout.php` no contienen HTML propio; solo redirigen a las vistas `layouts.app` y `layouts.guest`. Sirven como envoltorios principales de las páginas públicas y de autenticación.
- **Componentes anónimos Blade**: viven en `resources/views/components/*.blade.php` y se usan con la sintaxis `<x-nombre-componente>`. La mayoría de la interfaz del proyecto está aquí.
- **Componentes con namespace**: los que están dentro de `resources/views/components/admin/` se invocan como `<x-admin.algo>`, por ejemplo `<x-admin.table>` o `<x-admin.badge>`.

#### 2. Cómo funcionan en la práctica

- Los componentes reciben datos mediante **props** declaradas con `@props([...])`.
- El contenido interior se inserta con `{{ $slot }}`.
- Cuando un componente necesita más de una zona de contenido, usa **slots nombrados** como `<x-slot name="trigger">` o `<x-slot name="footer">`.
- Algunos componentes heredan atributos HTML del llamador con `{{ $attributes->merge([...]) }}`, así pueden recibir clases extra, href, id, etc.

#### 3. Componentes base reutilizados en formularios y navegación

- `<x-input-label>`: imprime etiquetas de formulario.
- `<x-text-input>`: estiliza inputs y permite deshabilitarse con `@disabled`.
- `<x-input-error>`: lista mensajes de validación.
- `<x-auth-session-status>`: muestra estados de sesión como mensajes exitosos.
- `<x-primary-button>`, `<x-secondary-button>` y `<x-danger-button>`: unifican la apariencia de botones por intención visual.
- `<x-nav-link>` y `<x-responsive-nav-link>`: manejan enlaces activos/inactivos en navegación normal y móvil.
- `<x-dropdown>` y `<x-dropdown-link>`: construyen menús desplegables con Alpine.js.
- `<x-application-logo>`: renderiza el logo SVG reutilizable.

#### 4. Separación entre frontend público y panel admin

El proyecto está dividido en dos capas visuales distintas:

- **Frontend público**: usa `resources/views/layouts/app.blade.php` y `resources/views/layouts/guest.blade.php` como marcos principales. Estas vistas se consumen normalmente con `<x-app-layout>` y `<x-guest-layout>`.
- **Panel administrativo**: usa `resources/views/layouts/admin.blade.php` como layout propio, con navegación, cabecera y contenedor de contenido pensados para gestión interna.

Esta separación es importante porque evita mezclar estilos, navegación y comportamiento entre la tienda pública y el backoffice.

#### 5. Componentes del frontend público

- `resources/views/layouts/navigation.blade.php` combina enlaces públicos, menú de usuario y carrito. Usa `x-dropdown`, `x-dropdown-link` y `x-responsive-nav-link`.
- `resources/views/layouts/guest.blade.php` envuelve auth screens y muestra el logo con `<x-application-logo>`.
- `resources/views/components/modal.blade.php` es el modal genérico del frontend. Usa Alpine.js para apertura/cierre, manejo de foco y bloqueo del scroll del body.

#### 6. Componentes y vistas del panel admin

Las vistas del admin viven sobre todo en `resources/views/admin/` y se apoyan en componentes con namespace `x-admin.*`. Ese grupo concentra la UI repetida del panel.

- `<x-admin.sidebar-link>`: resalta el módulo activo en la barra superior del admin.
- `<x-admin.badge>`: muestra estados, tipos o etiquetas de color controlado por prop.
- `<x-admin.table>`: renderiza tablas con encabezados dinámicos y filas recibidas por slot.
- `<x-admin.modal>`: modal simple del admin con título, cuerpo y footer opcional.
- `<x-admin.stat-card>`: tarjeta de métrica con título, valor, tendencia e ícono.
- `<x-admin.stat-card-analytic>`: variante más compacta de tarjeta analítica.
- `<x-admin.chart>`: monta un canvas y registra un gráfico de Chart.js usando `@push('scripts')`.

Además, el layout `resources/views/layouts/admin.blade.php` es el punto de entrada visual del panel y es el que organiza esas piezas en pantalla.

#### 7. Flujo de uso más común

- Las vistas públicas y de autenticación normalmente empiezan con `<x-app-layout>` o `<x-guest-layout>`.
- Las vistas del admin usan el layout administrativo y dentro llaman componentes específicos como `<x-admin.table>` o `<x-admin.badge>`.
- Los componentes visuales centralizan estilos Tailwind, así que cualquier cambio de apariencia se hace en un solo archivo y se refleja en todas las pantallas que lo consumen.

#### 8. Puntos importantes de comportamiento

- `x-dropdown` y `x-admin.modal` dependen de Alpine.js para abrir/cerrar elementos sin JavaScript manual complejo.
- `x-admin.chart` depende de Chart.js disponible en el bundle o en el entorno global y genera un ID único para no chocar si hay más de un gráfico en la misma vista.
- Los componentes de navegación usan `request()->routeIs(...)` o props `:active` para decidir qué enlace se pinta como activo.
- Los mensajes flash de éxito/error se muestran desde los layouts, no desde cada vista individual.

#### 9. Lectura rápida del patrón general

Si una vista repite HTML de formularios, botones, enlaces, tablas o badges, probablemente ya existe un componente para eso. La lógica visual vive en los componentes, mientras que las vistas solo pasan datos y slot content.
