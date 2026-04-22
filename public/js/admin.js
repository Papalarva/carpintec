// ====================== VERIFICAR SESIÓN ======================
const currentUser = JSON.parse(localStorage.getItem('currentUser'));
if (!currentUser || currentUser.role !== 'admin') {
    window.location.href = 'login.html';
}

// ====================== FUNCIONES DE ALMACENAMIENTO ======================
function getProducts() {
    const products = localStorage.getItem('products');
    return products ? JSON.parse(products) : [];
}

function saveProducts(products) {
    localStorage.setItem('products', JSON.stringify(products));
}

function getOrders() {
    const orders = localStorage.getItem('orders');
    return orders ? JSON.parse(orders) : [];
}

function saveOrders(orders) {
    localStorage.setItem('orders', JSON.stringify(orders));
}

function showToastMessage(msg, isError = false) {
    let toast = document.querySelector('.toast-notification');
    if (!toast) {
        toast = document.createElement('div');
        toast.className = 'toast-notification';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.style.backgroundColor = isError ? '#c62828' : '#2e7d32';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// ====================== INVENTARIO ======================
function renderInventory() {
    const products = getProducts();
    const container = document.getElementById('inventoryGrid');
    if (!container) {
        console.error('No se encontró el contenedor inventoryGrid');
        return;
    }
    
    if (products.length === 0) {
        container.innerHTML = '<p>No hay productos. Agrega uno nuevo.</p>';
        return;
    }
    
    container.innerHTML = products.map(p => `
        <div class="inventory-card">
            <img src="${p.images[0]}" alt="${p.name}">
            <h4>${p.name}</h4>
            <p>$${p.price} | ${p.category}</p>
            <div class="stock-control">
                <button onclick="updateStock(${p.id}, -1)">-</button>
                <span>Stock: ${p.stock || 0}</span>
                <button onclick="updateStock(${p.id}, 1)">+</button>
            </div>
            <div class="admin-product-actions">
                <button onclick="editProduct(${p.id})">✏️ Editar</button>
                <button onclick="deleteProduct(${p.id})" style="background:#ffcccc;">🗑️ Eliminar</button>
            </div>
        </div>
    `).join('');
}

window.updateStock = function(productId, delta) {
    let products = getProducts();
    const prod = products.find(p => p.id === productId);
    if (prod) {
        let newStock = (prod.stock || 0) + delta;
        if (newStock < 0) newStock = 0;
        prod.stock = newStock;
        saveProducts(products);
        renderInventory();
        showToastMessage(`Stock de ${prod.name} actualizado a ${newStock}`);
    }
};

window.deleteProduct = function(id) {
    let products = getProducts();
    const newProducts = products.filter(p => p.id !== id);
    if (newProducts.length === products.length) {
        showToastMessage("Producto no encontrado", true);
        return;
    }
    saveProducts(newProducts);
    renderInventory();
    showToastMessage("Producto eliminado");
};

window.editProduct = function(id) {
    let products = getProducts();
    const prod = products.find(p => p.id === id);
    if (!prod) return;
    const newName = prompt("Nuevo nombre:", prod.name);
    if (newName) prod.name = newName;
    const newPrice = parseFloat(prompt("Nuevo precio:", prod.price));
    if (!isNaN(newPrice)) prod.price = newPrice;
    const newDesc = prompt("Nueva descripción:", prod.description);
    if (newDesc) prod.description = newDesc;
    saveProducts(products);
    renderInventory();
    showToastMessage("Producto actualizado");
};

function addProduct() {
    const name = document.getElementById('newProductName').value.trim();
    const category = document.getElementById('newProductCategory').value;
    const price = parseFloat(document.getElementById('newProductPrice').value);
    const image = document.getElementById('newProductImage').value.trim();
    const description = document.getElementById('newProductDescription').value.trim();
    const stock = parseInt(document.getElementById('newProductStock').value) || 0;
    if (!name || isNaN(price)) {
        showToastMessage("Nombre y precio son obligatorios", true);
        return;
    }
    const products = getProducts();
    const newId = Date.now();
    products.push({
        id: newId, name, category, price, brand: "Madera Viva", rating: 0,
        images: [image || "https://picsum.photos/id/158/400/300"],
        description: description || "Nuevo producto artesanal",
        stock: stock
    });
    saveProducts(products);
    renderInventory();
    showToastMessage("Producto agregado");
    // Limpiar formulario
    document.getElementById('newProductName').value = '';
    document.getElementById('newProductPrice').value = '';
    document.getElementById('newProductImage').value = '';
    document.getElementById('newProductDescription').value = '';
    document.getElementById('newProductStock').value = '10';
}

// ====================== SOLICITUDES (PEDIDOS) ======================
function renderRequests() {
    const orders = getOrders();
    const container = document.getElementById('requestsList');
    if (!container) return;
    
    if (orders.length === 0) {
        container.innerHTML = "<p>No hay solicitudes aún.</p>";
        return;
    }
    
    container.innerHTML = orders.map(order => {
        const hasCustom = order.items?.some(i => i.type === 'custom') || false;
        const cardClass = hasCustom ? 'request-card custom' : 'request-card';
        const userEmail = order.user || 'Usuario desconocido';
        const avatarInitial = userEmail.charAt(0).toUpperCase();
        const status = order.status || 'pendiente';
        const paymentMethod = order.paymentMethod || 'No especificado';
        const deliveryAddress = order.deliveryAddress || 'No especificada';
        
        return `
            <div class="${cardClass}">
                <div class="request-avatar">${avatarInitial}</div>
                <div class="request-details">
                    <strong>${userEmail}</strong><br>
                    <small>${order.date || ''}</small>
                    <div><strong>Total:</strong> $${(order.total || 0).toFixed(2)}</div>
                    <div><strong>Estado:</strong> <span style="color: ${status === 'pendiente' ? '#f39c12' : '#2ecc71'};">${status}</span></div>
                    <div><strong>Pago:</strong> ${paymentMethod}</div>
                    <div><strong>Dirección:</strong> ${deliveryAddress}</div>
                    <div><strong>Productos:</strong></div>
                    <ul>
                        ${order.items.map(item => `<li>${item.type === 'custom' ? '🎨 Personalizado' : '📦 Producto'} - ${item.name} (x${item.quantity})</li>`).join('')}
                    </ul>
                    <button class="btn-mark-sent" data-id="${order.id}" style="background: #2ecc71; color: white; border: none; padding: 6px 12px; border-radius: 20px; margin-top: 10px; cursor: pointer;">✅ Pedido enviado</button>
                </div>
            </div>
        `;
    }).join('');
}

function markOrderAsSent(orderId) {
    // Convertir a número (los IDs se guardan como número)
    const idToRemove = Number(orderId);
    let orders = getOrders();
    const originalLength = orders.length;
    // Filtrar usando comparación estricta con números
    const newOrders = orders.filter(order => Number(order.id) !== idToRemove);
    
    if (newOrders.length === originalLength) {
        showToastMessage("No se encontró el pedido para eliminar", true);
        return;
    }
    
    saveOrders(newOrders);
    renderRequests();
    showToastMessage("✅ Pedido marcado como enviado y eliminado del historial");
}

// ====================== ESTADÍSTICAS ======================
function renderStats() {
    const products = getProducts();
    const orders = getOrders();
    const totalProducts = products.length;
    const totalStock = products.reduce((sum, p) => sum + (p.stock || 0), 0);
    const totalOrders = orders.length;
    const totalRevenue = orders.reduce((sum, o) => sum + (o.total || 0), 0);
    const customOrders = orders.filter(o => o.items?.some(i => i.type === 'custom')).length;
    const container = document.getElementById('statsGrid');
    if (!container) return;
    container.innerHTML = `
        <div class="stat-card"><h3>📦 Productos</h3><p>${totalProducts}</p></div>
        <div class="stat-card"><h3>📊 Stock total</h3><p>${totalStock}</p></div>
        <div class="stat-card"><h3>📋 Pedidos</h3><p>${totalOrders}</p></div>
        <div class="stat-card"><h3>💰 Ingresos</h3><p>$${totalRevenue.toFixed(2)}</p></div>
        <div class="stat-card"><h3>🎨 Personalizados</h3><p>${customOrders}</p></div>
    `;
}

// ====================== NAVEGACIÓN ENTRE PESTAÑAS ======================
function switchTab(tabId) {
    document.querySelectorAll('.admin-section').forEach(section => section.classList.remove('active'));
    document.getElementById(`${tabId}Section`).classList.add('active');
    document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
    document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');
    
    if (tabId === 'inventory') renderInventory();
    else if (tabId === 'requests') renderRequests();
    else if (tabId === 'stats') renderStats();
}

// ====================== EVENTOS ======================
document.addEventListener('DOMContentLoaded', () => {
    // Pestañas
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => switchTab(tab.dataset.tab));
    });
    
    // Botón agregar producto
    document.getElementById('addProductBtn')?.addEventListener('click', addProduct);
    
    // Cerrar sesión
    document.getElementById('adminLogoutBtn')?.addEventListener('click', () => {
        localStorage.removeItem('currentUser');
        window.location.href = 'login.html';
    });
    
    // Delegación para eliminar pedidos (por si el DOM se actualiza dinámicamente)
    document.getElementById('requestsList')?.addEventListener('click', (e) => {
        const btn = e.target.closest('.btn-mark-sent');
        if (btn) {
            const orderId = btn.getAttribute('data-id');
            if (orderId) markOrderAsSent(orderId);
        }
    });
    
    // Inicializar vista de inventario (pors defecto)
    switchTab('inventory');
});