{{--
    Componente Anónimo: Modal del Carrito (sidebar deslizante)
    Uso: <x-cart-modal />
    El JS lo controla con la clase .open en #cartModal
--}}

<!-- Modal del carrito -->
<div id="cartModal" class="cart-modal" role="dialog" aria-modal="true" aria-label="Mi carrito">
    <div class="cart-header">
        <h3>Mi carrito</h3>
        <button id="closeCartBtn" class="modal-close" style="position: static;" aria-label="Cerrar carrito">&times;</button>
    </div>
    <div id="cartItemsList" class="cart-items">
        <div class="empty-cart">Tu carrito está vacío</div>
    </div>
    <div class="cart-footer">
        <div class="cart-total" id="cartTotal">Total: $0</div>
        <button id="checkoutBtn" class="btn-checkout">Proceder al pago</button>
    </div>
</div>
