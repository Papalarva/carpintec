{{--
    Vista: Tienda / E-commerce principal
    Ruta: resources/views/shop/index.blade.php
    Middleware: auth (requiere sesión activa)
--}}
@extends('layouts.carpintec')

@section('title', 'CARPINTEC - Tienda de Carpintería')

@section('content')
<div id="ecommerceApp" class="ecommerce-app">
    <div class="app">

        {{-- ===== Barra de navegación ===== --}}
        <nav class="tabbar" role="navigation" aria-label="Navegación principal">
            <div class="logo-area">
                <i class="fas fa-tree" aria-hidden="true"></i>
                <span>CARPINTEC</span>
            </div>
            <div class="nav-links" id="navLinks">
                <a data-cat="inicio" class="active" role="button" tabindex="0">Inicio</a>
                <a data-cat="sala" role="button" tabindex="0">Sala</a>
                <a data-cat="cocina" role="button" tabindex="0">Cocina</a>
                <a data-cat="comedor" role="button" tabindex="0">Comedor</a>
                <a data-cat="recamara" role="button" tabindex="0">Recámara</a>
                <a data-cat="baño" role="button" tabindex="0">Baño</a>
            </div>
            <div class="user-actions">
                <i class="fas fa-shopping-cart cart-icon" id="cartIcon" role="button" tabindex="0" aria-label="Abrir carrito">
                    <span class="cart-count" id="cartCount">0</span>
                </i>
                <i class="fas fa-bell" id="notifIcon" role="button" tabindex="0" aria-label="Notificaciones"></i>
                <i class="fas fa-user-circle" id="profileIcon" role="button" tabindex="0" aria-label="Mi perfil"></i>
                <i class="fas fa-cog" id="adminPanelBtn"
                   style="margin-left: 10px; cursor: pointer; display: none;"
                   title="Panel de administración"
                   role="button" tabindex="0" aria-label="Panel de administración"></i>
                <i class="fas fa-sign-out-alt" id="logoutBtn"
                   style="margin-left: 10px; cursor: pointer;"
                   title="Cerrar sesión"
                   role="button" tabindex="0" aria-label="Cerrar sesión"></i>
            </div>
        </nav>

        {{-- ===== Contenido principal ===== --}}
        <main id="mainContent">
            {{-- Carrusel de productos destacados --}}
            <section class="carousel-section" aria-label="Productos destacados">
                <div class="slider-container" id="sliderContainer">
                    <div class="slider-wrapper">
                        <div class="slider" id="slider">
                            {{-- Los slides se generan dinámicamente por JS --}}
                        </div>
                    </div>
                    <div class="dots" id="dotsContainer" role="tablist" aria-label="Slides del carrusel"></div>
                </div>
            </section>

            {{-- Grid de productos --}}
            <section class="suggestions">
                <div class="section-title">✨ Sugerencias para ti</div>
                <div class="products-grid" id="productsGrid" aria-label="Catálogo de productos">
                    {{--
                        Renderizado estático opcional con el componente:
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                        El JS también puede rellenar este grid dinámicamente.
                    --}}
                </div>
            </section>
        </main>

        {{-- Botón flotante: pedido a medida --}}
        <div id="fabCustom" class="fab-custom" role="button" tabindex="0" aria-label="Solicitar mueble a medida">
            <i class="fas fa-pencil-alt" aria-hidden="true"></i>
        </div>

        {{-- Botón flotante: ayuda --}}
        <button id="floatingHelpBtn" class="help-btn" aria-label="Ayuda">
            <i class="fas fa-question" aria-hidden="true"></i>
        </button>

        <footer>
            <p>🌲 Hecho con madera reciclada | Tu mueble rústico de confianza</p>
            <small>Ayuda: contacto@carpintec.com</small>
        </footer>
    </div>

    {{-- ===== Componente: Carrito lateral ===== --}}
    <x-cart-modal />

    {{-- ===== Modal: Perfil de usuario ===== --}}
    <div id="profileModal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Mi perfil">
        <div class="product-modal" style="max-width: 450px;">
            <button class="modal-close" id="closeProfileBtn" aria-label="Cerrar perfil">&times;</button>
            <h2><i class="fas fa-user-circle" aria-hidden="true"></i> Mi perfil</h2>
            <div class="profile-form">
                <div class="input-group">
                    <label for="profileEmail">Correo electrónico</label>
                    <input type="email" id="profileEmail" readonly disabled>
                </div>
                <div class="input-group">
                    <label for="profileUsername">Nombre de usuario</label>
                    <input type="text" id="profileUsername">
                </div>
                <div class="input-group">
                    <label for="profilePhone">Teléfono</label>
                    <input type="tel" id="profilePhone">
                </div>
                <button id="saveProfileBtn" class="btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>

    {{-- ===== Modal: Detalle de producto ===== --}}
    <div id="productModal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Detalle de producto">
        <div class="product-modal">
            <button class="modal-close" aria-label="Cerrar">&times;</button>
            <div id="modalContent">
                {{-- El JS inyecta el contenido del producto aquí --}}
            </div>
        </div>
    </div>

    {{-- ===== Modal: Pedido personalizado ===== --}}
    <div id="customOrderModal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Pedido a medida">
        <div class="product-modal custom-order-modal">
            <button class="modal-close" id="closeCustomModal" aria-label="Cerrar">&times;</button>
            <h2>📐 Solicitar mueble a medida</h2>
            <form id="customOrderForm" class="custom-order-form" novalidate>
                <textarea id="customDesc" placeholder="Descripción detallada (obligatorio)" rows="4" required></textarea>
                <input type="file" id="customImage" accept="image/*" aria-label="Imagen de referencia">
                <div id="imagePreview" class="image-preview"></div>
                <input type="number" id="customQty" placeholder="Cantidad" min="1" value="1" required>
                <input type="text" id="customMeasures" placeholder="Medidas (ej: 120x60x45 cm)">
                <input type="text" id="customMaterial" placeholder="Material preferido">
                <input type="number" id="customPrice" placeholder="Precio sugerido (opcional)" step="0.01">
                <button type="submit" class="btn-primary">Agregar al carrito</button>
            </form>
        </div>
    </div>

    {{-- ===== Panel de Administración (modal inline para usuario admin) ===== --}}
    <div id="adminPanel" class="admin-panel" role="dialog" aria-modal="true" aria-label="Panel de administración">
        <div class="admin-panel-content">
            <button id="closeAdminPanel" class="modal-close"
                    style="position: absolute; top: 16px; right: 20px;" aria-label="Cerrar">&times;</button>
            <h2>Panel de Administración</h2>
            <div class="admin-tab" role="tablist">
                <button id="tabProducts" class="active" role="tab" aria-selected="true">Productos</button>
                <button id="tabOrders" role="tab" aria-selected="false">Pedidos</button>
            </div>
            <div id="adminProductsSection">
                <div class="admin-product-list" id="adminProductList"></div>
                <div class="admin-form">
                    <h3>Agregar nuevo producto</h3>
                    <input type="text" id="newProductName" placeholder="Nombre">
                    <select id="newProductCategory">
                        <option value="sala">Sala</option>
                        <option value="cocina">Cocina</option>
                        <option value="comedor">Comedor</option>
                        <option value="recamara">Recámara</option>
                        <option value="baño">Baño</option>
                    </select>
                    <input type="number" id="newProductPrice" placeholder="Precio">
                    <input type="text" id="newProductImage" placeholder="URL de imagen">
                    <input type="number" id="newProductStock" placeholder="Stock (cantidad disponible)" value="10">
                    <textarea id="newProductDescription" placeholder="Descripción"></textarea>
                    <button id="addProductBtn">Agregar producto</button>
                </div>
            </div>
            <div id="adminOrdersSection" style="display: none;">
                <div id="ordersList" class="orders-list"></div>
            </div>
        </div>
    </div>

    {{-- ===== Modal: Checkout / Pago ===== --}}
    <div id="checkoutModal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Finalizar compra">
        <div class="product-modal checkout-modal">
            <button class="modal-close" id="closeCheckoutBtn" aria-label="Cerrar">&times;</button>
            <h2>Finalizar compra</h2>
            <div id="checkoutSummary" class="checkout-summary"></div>
            <h3>Método de pago</h3>
            <div class="payment-methods" role="group" aria-label="Métodos de pago">
                <div class="payment-method" data-method="card" role="radio" tabindex="0">💳 Tarjeta Debito/Credito</div>
                <div class="payment-method" data-method="paypal" role="radio" tabindex="0">📱 PayPal</div>
                <div class="payment-method" data-method="mercadopago" role="radio" tabindex="0">🟡 Otro</div>
            </div>
            <div id="paymentFormContainer" class="payment-form"></div>
            <div class="address-fields">
                <label for="deliveryAddress">Dirección de entrega:</label>
                <textarea id="deliveryAddress" rows="3"
                    placeholder="Calle, número, colonia, ciudad, código postal">Calle Roble #123, Colonia Madera, Ciudad de México, CP 12345</textarea>
            </div>
            <button id="confirmPayBtn" class="btn-pay">Confirmar pago</button>
        </div>
    </div>

    {{-- ===== Modal: Ayuda ===== --}}
    <div id="helpModal" class="modal-overlay" role="dialog" aria-modal="true" aria-label="Ayuda">
        <div class="product-modal help-modal">
            <button class="modal-close" id="closeHelpBtn" aria-label="Cerrar">&times;</button>
            <div class="help-content">
                <i class="fas fa-question-circle" aria-hidden="true"></i>
                <h3>¿Necesitas ayuda?</h3>
                <p>📞 Teléfono: +52 55 1234 5678</p>
                <p>✉️ Email: soporte@carpintec.com</p>
                <p>💬 Chat en línea: disponible de 9am a 6pm</p>
                <hr>
                <p><strong>Preguntas frecuentes:</strong><br>
                    - ¿Cómo personalizar un mueble? Usa el botón flotante "Pedido a medida".<br>
                    - ¿Tiempo de entrega? De 5 a 15 días hábiles.<br>
                    - ¿Garantía? 1 año en defectos de fabricación.</p>
            </div>
        </div>
    </div>

</div>{{-- /#ecommerceApp --}}

<x-toast />
@endsection

@push('scripts')
    <script src="{{ asset('js/carpintec.js') }}"></script>
@endpush
