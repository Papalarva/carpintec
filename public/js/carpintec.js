// ====================== INICIALIZACIÓN DE DATOS ======================
const DEFAULT_PRODUCTS = [
    { id: 1, name: "Mesa de Roble", category: "comedor", price: 349.99, brand: "Artesano Maderero", rating: 4.8, images: ["https://cdn.shopify.com/s/files/1/0260/3532/2938/products/image_18082bc5-81ce-4d0e-b2be-09a6b5e387fc_2000x.jpg?v=1619173679"], description: "Mesa maciza de roble sólido.", stock: 5 },
    { id: 2, name: "Silla Nórdica", category: "sala", price: 89.99, brand: "NordWood", rating: 4.5, images: ["https://media.adeo.com/mkp/a6e7ad1be055224750fb36db076f0425/media.jpg"], description: "Silla de haya ergonómica.", stock: 12 },
    { id: 3, name: "Biblioteca Flotante", category: "sala", price: 199.99, brand: "Rústica Línea", rating: 4.7, images: ["https://th.bing.com/th/id/R.4f2c7dff3a9864fcf3d38bb8f1899092?rik=pKXxu4qYwTynzA&pid=ImgRaw&r=0"], description: "Estante de pino.", stock: 3 },
    { id: 4, name: "Aparador", category: "comedor", price: 279.00, brand: "Carpintería Alma", rating: 4.9, images: ["https://aureahogar.es/5716-thickbox_default/aparador-madera-4-puertas-3-cajones-172-cm-beirut.jpg"], stock: 2 },
    { id: 5, name: "Cama King Size", category: "recamara", price: 599.99, brand: "DreamWood", rating: 4.6, images: ["https://media.adeo.com/marketplace/MKP/85844928/adb046cc50f8bbdec3c335f9d6ba6afa.jpeg"], description: "Cabecera tapizada.", stock: 4 },
    { id: 6, name: "Banco Baño", category: "baño", price: 45.99, brand: "EcoBambú", rating: 4.3, images: ["https://i.pinimg.com/originals/d1/8d/3d/d18d3d01c5b650a2f6047494888c7828.jpg"], description: "Resistente a humedad.", stock: 8 },
    { id: 7, name: "Tabla Cocina", category: "cocina", price: 34.50, brand: "ChefWood", rating: 4.7, images: ["https://tse1.explicit.bing.net/th/id/OIP.fubgu2jyi1qAiVbUBZAuKQHaEl?rs=1&pid=ImgDetMain&o=7&rm=3"], description: "Bambú orgánico.", stock: 20 }
];

localStorage.setItem('products', JSON.stringify(DEFAULT_PRODUCTS));
if (!localStorage.getItem('registeredUsers')) localStorage.setItem('registeredUsers', JSON.stringify([]));
if (!localStorage.getItem('registeredAdmins')) localStorage.setItem('registeredAdmins', JSON.stringify([]));
if (!localStorage.getItem('cart')) localStorage.setItem('cart', JSON.stringify([]));
if (!localStorage.getItem('orders')) localStorage.setItem('orders', JSON.stringify([]));

const users = JSON.parse(localStorage.getItem('registeredUsers'));
if (users.length === 0) {
    users.push({ email: "cliente@madera.com", phone: "5512345678", username: "artesano123", password: "123456" });
    localStorage.setItem('registeredUsers', JSON.stringify(users));
}
const admins = JSON.parse(localStorage.getItem('registeredAdmins'));
if (admins.length === 0) {
    admins.push({ identification: "ADMIN123", email: "admin@madera.com", name: "Carlos", lastname: "López", curp: "LOPC800101HDFRRN09", ssn: "12345678901" });
    localStorage.setItem('registeredAdmins', JSON.stringify(admins));
}

// ====================== FUNCIONES COMUNES ======================
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

function getProducts() { return JSON.parse(localStorage.getItem('products')); }
function saveProducts(products) { localStorage.setItem('products', JSON.stringify(products)); }
function getOrders() { return JSON.parse(localStorage.getItem('orders')); }
function saveOrders(orders) { localStorage.setItem('orders', JSON.stringify(orders)); }

// ====================== CARRITO ======================
function getCart() { return JSON.parse(localStorage.getItem('cart')) || []; }
function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartUI();
}
function addToCart(item) {
    const cart = getCart();
    const existingIndex = cart.findIndex(i => i.id === item.id && i.type === (item.type || 'product'));
    if (existingIndex !== -1) cart[existingIndex].quantity += item.quantity;
    else cart.push(item);
    saveCart(cart);
    showToastMessage(`${item.name} agregado al carrito`);
}
function removeFromCart(index) {
    const cart = getCart();
    cart.splice(index, 1);
    saveCart(cart);
}
function updateQuantity(index, newQty) {
    if (newQty <= 0) removeFromCart(index);
    else {
        const cart = getCart();
        cart[index].quantity = newQty;
        saveCart(cart);
    }
}
function updateCartUI() {
    const cart = getCart();
    const countSpan = document.getElementById('cartCount');
    if (countSpan) countSpan.innerText = cart.reduce((sum, i) => sum + i.quantity, 0);
    const container = document.getElementById('cartItemsList');
    if (!container) return;
    if (cart.length === 0) {
        container.innerHTML = '<div class="empty-cart">Tu carrito está vacío</div>';
        document.getElementById('cartTotal').innerHTML = 'Total: $0';
        return;
    }
    let total = 0;
    container.innerHTML = cart.map((item, idx) => {
        const price = item.type === 'custom' ? (item.suggestedPrice || 0) : item.price;
        const subtotal = price * item.quantity;
        total += subtotal;
        const priceDisplay = (item.type === 'custom' && price === 0) ? 'Por cotizar' : `$${price.toFixed(2)}`;
        return `
            <div class="cart-item">
                ${item.image ? `<img class="cart-item-img" src="${item.image}">` : '<div class="cart-item-img" style="background:#eee;"></div>'}
                <div class="cart-item-details">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-price">${priceDisplay}</div>
                    <div class="cart-item-actions">
                        <button onclick="window.updateQuantity(${idx}, ${item.quantity - 1})">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="window.updateQuantity(${idx}, ${item.quantity + 1})">+</button>
                        <button onclick="window.removeFromCart(${idx})" style="background:#ffdddd;">🗑️</button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    const totalText = (cart.some(i => i.type === 'custom' && (i.suggestedPrice || 0) === 0))
        ? 'Total parcial (algunos ítems requieren cotización)'
        : `Total: $${total.toFixed(2)}`;
    document.getElementById('cartTotal').innerHTML = totalText;
}
window.updateQuantity = updateQuantity;
window.removeFromCart = removeFromCart;

// ====================== CHECKOUT ======================
function checkout() {
    const cart = getCart();
    if (cart.length === 0) { showToastMessage("Carrito vacío", true); return; }
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    const order = {
        id: Date.now(),
        user: currentUser.email,
        date: new Date().toLocaleString(),
        items: cart.map(item => ({ ...item })),
        total: cart.reduce((sum, i) => sum + ((i.type === 'custom' ? (i.suggestedPrice || 0) : i.price) * i.quantity), 0)
    };
    const orders = getOrders();
    orders.push(order);
    saveOrders(orders);
    localStorage.setItem('cart', JSON.stringify([]));
    updateCartUI();
    showToastMessage("✅ Pedido realizado con éxito.");
    document.getElementById('cartModal')?.classList.remove('open');
}

// ====================== PEDIDO PERSONALIZADO ======================
function showCustomOrderModal() { document.getElementById('customOrderModal')?.classList.add('active'); }
function addCustomOrder(event) {
    event.preventDefault();
    const desc = document.getElementById('customDesc').value.trim();
    if (!desc) { showToastMessage("Descripción obligatoria", true); return; }
    const qty = parseInt(document.getElementById('customQty').value);
    const measures = document.getElementById('customMeasures').value;
    const material = document.getElementById('customMaterial').value;
    let suggestedPrice = parseFloat(document.getElementById('customPrice').value);
    if (isNaN(suggestedPrice)) suggestedPrice = 0;
    const fileInput = document.getElementById('customImage');
    const addItem = (imageData) => {
        addToCart({
            id: Date.now(), type: 'custom',
            name: `Pedido: ${desc.substring(0, 40)}...`,
            description: desc, measures, material, suggestedPrice,
            quantity: qty, image: imageData || '', price: suggestedPrice
        });
        document.getElementById('customOrderModal').classList.remove('active');
        document.getElementById('customOrderForm').reset();
        document.getElementById('imagePreview').innerHTML = '';
    };
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = e => addItem(e.target.result);
        reader.readAsDataURL(fileInput.files[0]);
    } else addItem('');
}

// ====================== FUNCIONES DE INTERFAZ (E-COMMERCE) ======================
let slideInterval = null;
let currentSlide = 0;
let totalSlides = 0;

function renderCarousel() {
    const products = getProducts();
    const offers = products.slice(0, 5); // Tomamos hasta 5 productos destacados
    totalSlides = offers.length;
    const slider = document.getElementById('slider');
    const dotsContainer = document.getElementById('dotsContainer');
    if (!slider || !dotsContainer) return;

    // Limpiar contenedores
    slider.innerHTML = '';
    dotsContainer.innerHTML = '';

    if (totalSlides === 0) return;

    // Generar slides y puntos
    offers.forEach((p, idx) => {
        const slide = document.createElement('div');
        slide.className = 'slide';
        slide.dataset.id = p.id;
        slide.innerHTML = `
            <img src="${p.images[0]}" alt="${p.name}">
            <div class="slide-info">${p.name} - $${p.price}</div>
        `;
        slide.addEventListener('click', () => openProductModal(p.id));
        slider.appendChild(slide);

        const dot = document.createElement('span');
        dot.className = 'dot';
        if (idx === 0) dot.classList.add('active');
        dot.addEventListener('click', () => goToSlide(idx));
        dotsContainer.appendChild(dot);
    });

    // Iniciar slider automático
    if (slideInterval) clearInterval(slideInterval);
    startAutoSlide();

    // Ajustar posición inicial
    updateSliderPosition();
}

function updateSliderPosition() {
    const slider = document.getElementById('slider');
    if (!slider) return;
    const slideWidth = slider.clientWidth;
    slider.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Actualizar puntos activos
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, idx) => {
        if (idx === currentSlide) dot.classList.add('active');
        else dot.classList.remove('active');
    });
}

function goToSlide(index) {
    if (index < 0) index = totalSlides - 1;
    if (index >= totalSlides) index = 0;
    currentSlide = index;
    updateSliderPosition();
    resetAutoSlide();
}

function nextSlide() {
    goToSlide(currentSlide + 1);
}

function startAutoSlide() {
    slideInterval = setInterval(() => {
        nextSlide();
    }, 4000);
}

function resetAutoSlide() {
    if (slideInterval) {
        clearInterval(slideInterval);
        startAutoSlide();
    }
}

// Ajustar el slider cuando la ventana cambie de tamaño
window.addEventListener('resize', () => {
    updateSliderPosition();
});
function renderProductsGrid(category) {
    const products = getProducts();
    const filtered = category === 'inicio' ? products : products.filter(p => p.category === category);
    const grid = document.getElementById('productsGrid');
    if (!grid) return;
    grid.innerHTML = filtered.map(p => `
        <div class="product-card" data-id="${p.id}">
            <img class="product-img" src="${p.images[0]}" alt="${p.name}">
            <div class="product-info">
                <div class="product-title">${p.name}</div>
                <div class="product-price">$${p.price}</div>
                <div class="rating">${'★'.repeat(Math.floor(p.rating))} (${p.rating})</div>
                <small>${p.brand}</small>
            </div>
        </div>
    `).join('');
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', () => openProductModal(parseInt(card.dataset.id)));
    });
}

function getRelatedProducts(currentProductId, category, limit = 3) {
    const products = getProducts();
    return products.filter(p => p.category === category && p.id !== currentProductId).slice(0, limit);
}

function openProductModal(id) {
    const products = getProducts();
    const prod = products.find(p => p.id === id);
    if (!prod) return;
    const related = getRelatedProducts(id, prod.category, 3);
    const modal = document.getElementById('productModal');
    const modalContent = document.getElementById('modalContent');
    modalContent.innerHTML = `
        <div class="modal-grid">
            <div class="modal-gallery">
                <img class="modal-main-img" id="modalMainImg" src="${prod.images[0]}">
                <div class="thumbnails">
                    ${prod.images.map((img, idx) => `<img class="thumb ${idx === 0 ? 'active' : ''}" data-img="${img}" src="${img}">`).join('')}
                </div>
            </div>
            <div class="modal-details">
                <h2>${prod.name}</h2>
                <div class="product-brand">Marca: ${prod.brand}</div>
                <div class="rating">⭐ ${prod.rating} / 5</div>
                <div class="price-large">$${prod.price}</div>
                <p>${prod.description}</p>
                <div class="quantity-selector">
                    <button id="decQty">-</button>
                    <span id="qtyValue">1</span>
                    <button id="incQty">+</button>
                </div>
                <div>
                    <button class="btn-buy" id="buyNowBtn">Comprar ahora</button>
                    <button class="btn-cart" id="addToCartBtn">Agregar al carrito</button>
                </div>
                <div class="opinions"><h4>📝 Opiniones</h4><p>⭐⭐⭐⭐⭐ "Excelente calidad"</p></div>
                ${related.length ? `
                <div class="related">
                    <h4>🔗 Productos relacionados</h4>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 10px;">
                        ${related.map(r => `
                            <div style="cursor: pointer; border:1px solid #eee; border-radius:12px; padding:8px; width:100px; text-align:center;" data-relid="${r.id}">
                                <img src="${r.images[0]}" width="80" height="80" style="border-radius:8px; object-fit:cover;">
                                <div style="font-size:0.8rem;">${r.name}</div>
                                <div style="font-weight:bold;">$${r.price}</div>
                            </div>
                        `).join('')}
                    </div>
                </div>
                ` : ''}
            </div>
        </div>
    `;
    // Thumbnails
    document.querySelectorAll('.thumb').forEach(thumb => {
        thumb.addEventListener('click', () => {
            document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
            document.getElementById('modalMainImg').src = thumb.dataset.img;
        });
    });
    let qty = 1;
    const qtySpan = document.getElementById('qtyValue');
    document.getElementById('decQty')?.addEventListener('click', () => { if (qty > 1) qty--; qtySpan.innerText = qty; });
    document.getElementById('incQty')?.addEventListener('click', () => { qty++; qtySpan.innerText = qty; });
    document.getElementById('buyNowBtn')?.addEventListener('click', () => {
        openCheckoutModal([{
            id: prod.id,
            type: 'product',
            name: prod.name,
            price: prod.price,
            quantity: qty,
            image: prod.images[0],
            description: prod.description
        }]);
    });
    document.getElementById('addToCartBtn')?.addEventListener('click', () => addToCart({
        id: prod.id,
        type: 'product',
        name: prod.name,
        price: prod.price,
        quantity: qty,
        image: prod.images[0]
    }));
    modal.classList.add('active');
    const closeBtn = modal.querySelector('.modal-close');
    if (closeBtn) {
        const newCloseBtn = closeBtn.cloneNode(true);
        closeBtn.parentNode.replaceChild(newCloseBtn, closeBtn);
        newCloseBtn.addEventListener('click', () => modal.classList.remove('active'));
    }
    modal.addEventListener('click', (e) => { if (e.target === modal) modal.classList.remove('active'); });
}

// ====================== PANEL DE ADMINISTRACIÓN ======================
function renderAdminProducts() {
    const products = getProducts();
    const container = document.getElementById('adminProductList');
    if (!container) return;
    container.innerHTML = products.map(p => `
        <div class="admin-product-card">
            <img src="${p.images[0]}">
            <h4>${p.name}</h4>
            <p>$${p.price} | ${p.category}</p>
            <p>📦 Stock: ${p.stock || 0}</p>
            <div class="admin-product-actions">
                <button onclick="editProduct(${p.id})">✏️ Editar</button>
                <button onclick="deleteProduct(${p.id})" style="background:#ffcccc;">🗑️ Eliminar</button>
            </div>
        </div>
    `).join('');
}

function deleteProduct(id) {
    let products = getProducts();
    products = products.filter(p => p.id !== id);
    saveProducts(products);
    renderAdminProducts();
    renderProductsGrid('inicio');
    renderCarousel();
    showToastMessage("Producto eliminado");
}

function editProduct(id) {
    const products = getProducts();
    const prod = products.find(p => p.id === id);
    if (!prod) return;
    const newName = prompt("Nuevo nombre:", prod.name);
    if (newName) prod.name = newName;
    const newPrice = parseFloat(prompt("Nuevo precio:", prod.price));
    if (!isNaN(newPrice)) prod.price = newPrice;
    const newDesc = prompt("Nueva descripción:", prod.description);
    if (newDesc) prod.description = newDesc;
    let newStock = parseInt(prompt("Nuevo stock:", prod.stock || 0));
    if (!isNaN(newStock)) prod.stock = newStock;
    saveProducts(products);
    renderAdminProducts();
    renderProductsGrid('inicio');
    renderCarousel();
    showToastMessage("Producto actualizado");
}

function addProduct() {
    const name = document.getElementById('newProductName').value.trim();
    const category = document.getElementById('newProductCategory').value;
    const price = parseFloat(document.getElementById('newProductPrice').value);
    const image = document.getElementById('newProductImage').value.trim();
    const description = document.getElementById('newProductDescription').value.trim();
    const stock = parseInt(document.getElementById('newProductStock')?.value) || 10;
    if (!name || isNaN(price)) { showToastMessage("Nombre y precio obligatorios", true); return; }
    const products = getProducts();
    const newId = Date.now();
    products.push({
        id: newId, name, category, price, brand: "Madera Viva", rating: 0,
        images: [image || "https://picsum.photos/id/158/400/300"],
        description: description || "Nuevo producto artesanal", stock
    });
    saveProducts(products);
    renderAdminProducts();
    renderProductsGrid('inicio');
    renderCarousel();
    showToastMessage("Producto agregado");
    document.getElementById('newProductName').value = '';
    document.getElementById('newProductPrice').value = '';
    document.getElementById('newProductImage').value = '';
    document.getElementById('newProductDescription').value = '';
    if (document.getElementById('newProductStock')) document.getElementById('newProductStock').value = '';
}

function renderOrders() {
    const orders = getOrders();
    const container = document.getElementById('ordersList');
    if (!container) return;
    if (orders.length === 0) { container.innerHTML = "<p>No hay pedidos aún.</p>"; return; }
    container.innerHTML = orders.map(order => `
        <div class="order-item" style="margin-bottom:16px; border-left:4px solid ${order.items.some(i => i.type === 'custom') ? '#f39c12' : '#2ecc71'};">
            <strong>Pedido #${order.id}</strong><br>
            👤 Usuario: ${order.user}<br>
            📅 Fecha: ${order.date}<br>
            💰 Total: $${order.total.toFixed(2)}<br>
            <div style="margin-top:8px;"><strong>Productos:</strong>
                <ul style="margin-left:20px;">
                    ${order.items.map(item => `
                        <li>${item.type === 'custom' ? '🎨 <strong>Personalizado</strong>' : '📦 Producto'} - ${item.name} (x${item.quantity})
                        ${item.type === 'custom' && item.description ? `<br><small>Desc: ${item.description.substring(0, 80)}${item.description.length > 80 ? '…' : ''}</small>` : ''}
                        ${item.type === 'custom' && item.measures ? `<br><small>Medidas: ${item.measures}</small>` : ''}
                        </li>
                    `).join('')}
                </ul>
            </div>
        </div>
    `).join('');
}

function openAdminPanel() {
    renderAdminProducts();
    renderOrders();
    document.getElementById('adminPanel').classList.add('open');
    document.getElementById('adminProductsSection').style.display = 'block';
    document.getElementById('adminOrdersSection').style.display = 'none';
    document.getElementById('tabProducts').classList.add('active');
    document.getElementById('tabOrders').classList.remove('active');
}

function attachAdminEvents() {
    document.getElementById('adminPanelBtn')?.addEventListener('click', openAdminPanel);
    document.getElementById('closeAdminPanel')?.addEventListener('click', () => document.getElementById('adminPanel').classList.remove('open'));
    document.getElementById('addProductBtn')?.addEventListener('click', addProduct);
    document.getElementById('tabProducts')?.addEventListener('click', () => {
        document.getElementById('adminProductsSection').style.display = 'block';
        document.getElementById('adminOrdersSection').style.display = 'none';
        document.getElementById('tabProducts').classList.add('active');
        document.getElementById('tabOrders').classList.remove('active');
        renderAdminProducts();
    });
    document.getElementById('tabOrders')?.addEventListener('click', () => {
        document.getElementById('adminProductsSection').style.display = 'none';
        document.getElementById('adminOrdersSection').style.display = 'block';
        document.getElementById('tabOrders').classList.add('active');
        document.getElementById('tabProducts').classList.remove('active');
        renderOrders();
    });
}

// ====================== EVENTOS GENERALES ======================
function attachEcommerceEvents() {
    document.querySelectorAll('#navLinks a').forEach(link => {
        link.addEventListener('click', () => {
            const cat = link.dataset.cat;
            const carouselSection = document.querySelector('.carousel-section');
            if (cat === 'inicio') {
                if (carouselSection) carouselSection.style.display = 'block';
                renderCarousel();
                renderProductsGrid('inicio');
            } else {
                if (carouselSection) carouselSection.style.display = 'none';
                renderProductsGrid(cat);
            }
            document.querySelectorAll('#navLinks a').forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });
    document.getElementById('notifIcon')?.addEventListener('click', () => showToastMessage("🔔 Nuevas ofertas en muebles de pino"));
    document.getElementById('profileIcon')?.addEventListener('click', () => showToastMessage("Perfil - Próximamente"));
    document.getElementById('logoutBtn')?.addEventListener('click', () => { localStorage.removeItem('currentUser'); window.location.href = 'login.html'; });
    const cartIcon = document.getElementById('cartIcon');
    const cartModal = document.getElementById('cartModal');
    const closeCart = document.getElementById('closeCartBtn');
    cartIcon?.addEventListener('click', () => cartModal.classList.add('open'));
    closeCart?.addEventListener('click', () => cartModal.classList.remove('open'));
    document.getElementById('checkoutBtn')?.addEventListener('click', checkout);
    document.getElementById('fabCustom')?.addEventListener('click', showCustomOrderModal);
    document.getElementById('closeCustomModal')?.addEventListener('click', () => document.getElementById('customOrderModal').classList.remove('active'));
    document.getElementById('customOrderForm')?.addEventListener('submit', addCustomOrder);
    document.getElementById('customImage')?.addEventListener('change', function (e) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = ev => {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.maxWidth = '100px';
                img.style.borderRadius = '8px';
                preview.appendChild(img);
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    attachAdminEvents();
}

function initEcommerce() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) { window.location.href = 'login.html'; return; }
    document.getElementById('ecommerceApp').classList.add('active');
    const root = document.documentElement;
    if (currentUser.role === 'user') {
        root.style.setProperty('--primary', '#b45f2b');
        root.style.setProperty('--primary-dark', '#8b3e1c');
        document.getElementById('adminPanelBtn').style.display = 'none';
    } else {
        root.style.setProperty('--primary', '#1e3a5f');
        root.style.setProperty('--primary-dark', '#0f2c47');
        document.getElementById('adminPanelBtn').style.display = 'inline-block';
    }
    renderCarousel();
    renderProductsGrid('inicio');
    attachEcommerceEvents();
    updateCartUI();
}

// ====================== PERFIL DE USUARIO ======================
function openProfileModal() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) return;
    const users = JSON.parse(localStorage.getItem('registeredUsers'));
    const userData = users.find(u => u.email === currentUser.email);
    if (userData) {
        document.getElementById('profileEmail').value = userData.email;
        document.getElementById('profileUsername').value = userData.username || '';
        document.getElementById('profilePhone').value = userData.phone || '';
    }
    document.getElementById('profileModal').classList.add('active');
}

function saveProfile() {
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser) return;
    const newUsername = document.getElementById('profileUsername').value.trim();
    const newPhone = document.getElementById('profilePhone').value.trim();
    let users = JSON.parse(localStorage.getItem('registeredUsers'));
    const userIndex = users.findIndex(u => u.email === currentUser.email);
    if (userIndex !== -1) {
        users[userIndex].username = newUsername;
        users[userIndex].phone = newPhone;
        localStorage.setItem('registeredUsers', JSON.stringify(users));
        showToastMessage("Perfil actualizado correctamente");
        document.getElementById('profileModal').classList.remove('active');
    } else {
        showToastMessage("Error al actualizar", true);
    }
}

// Asociar eventos
document.getElementById('profileIcon')?.addEventListener('click', openProfileModal);
document.getElementById('closeProfileBtn')?.addEventListener('click', () => document.getElementById('profileModal').classList.remove('active'));
document.getElementById('saveProfileBtn')?.addEventListener('click', saveProfile);

// ====================== CHECKOUT (PROCESO DE PAGO) ======================
let currentCheckoutCart = []; // Guardar los items a pagar

function openCheckoutModal(items) {
    currentCheckoutCart = items;
    const total = items.reduce((sum, i) => sum + ((i.type === 'custom' ? (i.suggestedPrice || 0) : i.price) * i.quantity), 0);
    const summaryHtml = `
        <h3>Resumen de compra</h3>
        ${items.map(item => `
            <div class="checkout-item">
                <div>
                    <strong>${item.name}</strong> (x${item.quantity})
                    ${item.description ? `<br><small>${item.description.substring(0, 80)}</small>` : ''}
                </div>
                <div>$${((item.type === 'custom' ? (item.suggestedPrice || 0) : item.price) * item.quantity).toFixed(2)}</div>
            </div>
        `).join('')}
        <div class="checkout-item" style="font-weight: bold; border-top: 1px solid #ddd; margin-top: 8px; padding-top: 8px;">
            <span>Total a pagar</span>
            <span>$${total.toFixed(2)}</span>
        </div>
    `;
    document.getElementById('checkoutSummary').innerHTML = summaryHtml;
    selectPaymentMethod('card');
    document.getElementById('checkoutModal').classList.add('active');
}

function selectPaymentMethod(method) {
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('active'));
    document.querySelector(`.payment-method[data-method="${method}"]`).classList.add('active');
    const container = document.getElementById('paymentFormContainer');
    if (method === 'card') {
        container.innerHTML = `
            <div class="card-fields">
                <input type="text" id="cardNumber" placeholder="Número de tarjeta" value="4111 1111 1111 1111">
                <div style="display: flex; gap: 12px;">
                    <input type="text" id="cardExpiry" placeholder="MM/AA" value="12/28">
                    <input type="text" id="cardCvv" placeholder="CVV" value="123">
                </div>
                <input type="text" id="cardName" placeholder="Nombre del titular" value="Cliente Madera Viva">
            </div>
        `;
    } else if (method === 'paypal') {
        container.innerHTML = `<p> Correo: <strong>cliente@maderaviva.com</strong></p><p>Contraseña: ********</p>`;
    } else if (method === 'mercadopago') {
        container.innerHTML = `<p> Usuario: <strong>cliente_maderaviva</strong></p><p>Contraseña: ********</p>`;
    }
}

function confirmPayment() {
    if (currentCheckoutCart.length === 0) return;
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    const selectedMethod = document.querySelector('.payment-method.active').dataset.method;
    const address = document.getElementById('deliveryAddress').value;

    // Simular proceso de pago
    showToastMessage("🔄 Procesando pago...");
    setTimeout(() => {
        // Crear pedido
        const order = {
            id: Date.now(),
            user: currentUser.email,
            date: new Date().toLocaleString(),
            items: currentCheckoutCart.map(item => ({ ...item })),
            total: currentCheckoutCart.reduce((sum, i) => sum + ((i.type === 'custom' ? (i.suggestedPrice || 0) : i.price) * i.quantity), 0),
            status: 'pendiente',
            paymentMethod: selectedMethod,
            deliveryAddress: address
        };
        const orders = getOrders();
        orders.push(order);
        saveOrders(orders);

        // Vaciar carrito solo si se pagó todo el carrito (o si es compra directa)
        const cart = getCart();
        // Si los items a pagar son todos los del carrito, vaciamos; si no (por ejemplo compra directa de un producto), también.
        // En nuestro caso, siempre será el carrito completo o un solo producto.
        const remainingCart = cart.filter(cartItem =>
            !currentCheckoutCart.some(checkoutItem => checkoutItem.id === cartItem.id && checkoutItem.type === cartItem.type)
        );
        saveCart(remainingCart);

        showToastMessage("✅ Pago exitoso. Su pedido ha sido registrado.");
        document.getElementById('checkoutModal').classList.remove('active');
        updateCartUI();
    }, 1500);
}

// Modificar el evento de checkoutBtn (desde carrito)
// Reemplazar el evento existente:
const originalCheckout = document.getElementById('checkoutBtn');
if (originalCheckout) {
    originalCheckout.removeEventListener('click', checkout);
    originalCheckout.addEventListener('click', () => {
        const cart = getCart();
        if (cart.length === 0) showToastMessage("Carrito vacío", true);
        else openCheckoutModal(cart);
    });
}

// En lugar de addToCart, que abra checkout con ese producto
document.getElementById('buyNowBtn')?.addEventListener('click', () => {
    openCheckoutModal([{ id: prod.id, type: 'product', name: prod.name, price: prod.price, quantity: qty, image: prod.images[0] }]);
});

// Eventos para checkout modal
document.getElementById('closeCheckoutBtn')?.addEventListener('click', () => document.getElementById('checkoutModal').classList.remove('active'));
document.getElementById('confirmPayBtn')?.addEventListener('click', confirmPayment);
document.querySelectorAll('.payment-method').forEach(btn => {
    btn.addEventListener('click', () => selectPaymentMethod(btn.dataset.method));
});

// ====================== AYUDA ======================
document.getElementById('floatingHelpBtn')?.addEventListener('click', () => {
    document.getElementById('helpModal').classList.add('active');
});
document.getElementById('closeHelpBtn')?.addEventListener('click', () => {
    document.getElementById('helpModal').classList.remove('active');
});

// ====================== AUTENTICACIÓN (login.html) ======================
let currentRole = null;
function setRoleTheme(role) {
    const overlay = document.getElementById('authOverlay');
    const card = document.getElementById('authCard');
    if (!overlay || !card) return;
    if (role === 'user') {
        overlay.style.backgroundColor = 'rgba(180, 95, 43, 0.65)';
        card.classList.remove('role-admin');
        card.classList.add('role-user');
    } else if (role === 'admin') {
        overlay.style.backgroundColor = 'rgba(30, 58, 95, 0.7)';
        card.classList.remove('role-user');
        card.classList.add('role-admin');
    }
}
function renderLoginForm() {
    const container = document.getElementById('loginFormContainer');
    if (currentRole === 'user') {
        container.innerHTML = `<div class="input-group"><label>Correo</label><input type="email" id="loginEmail" value="cliente@madera.com"></div>
            <div class="input-group"><label>Contraseña</label><input type="password" id="loginPassword" value="123456"></div>
            <button id="submitLoginBtn" class="btn-primary">Iniciar sesión</button>`;
    } else {
        container.innerHTML = `<div class="input-group"><label>Correo</label><input type="email" id="loginEmail" value="admin@madera.com"></div>
            <div class="input-group"><label>ID</label><input type="text" id="loginIdentification" value="ADMIN123"></div>
            <button id="submitLoginBtn" class="btn-primary">Iniciar sesión</button>`;
    }
    document.getElementById('submitLoginBtn')?.addEventListener('click', handleLogin);
}
function handleLogin() {
    if (currentRole === 'user') {
        const email = document.getElementById('loginEmail').value.trim();
        const pwd = document.getElementById('loginPassword').value.trim();
        const users = JSON.parse(localStorage.getItem('registeredUsers'));
        if (users.some(u => u.email === email && u.password === pwd)) {
            localStorage.setItem('currentUser', JSON.stringify({ role: 'user', email }));
            showToastMessage("✅ Inicio exitoso");
            window.location.href = 'ecommerce.html';
        } else showToastMessage("❌ Credenciales incorrectas", true);
    } else {
        const email = document.getElementById('loginEmail').value.trim();
        const id = document.getElementById('loginIdentification').value.trim();
        const admins = JSON.parse(localStorage.getItem('registeredAdmins'));
        if (admins.some(a => a.email === email && a.identification === id)) {
            localStorage.setItem('currentUser', JSON.stringify({ role: 'admin', email }));
            showToastMessage("🔧 Inicio admin exitoso");
            window.location.href = 'admin.html';
        } else showToastMessage("❌ ID o correo incorrectos", true);
    }
}
function renderRegisterForm() {
    const container = document.getElementById('registerFormContainer');
    if (currentRole === 'user') {
        container.innerHTML = `<div class="input-group"><label>Correo</label><input type="email" id="regEmail" value="nuevo@madera.com"></div>
            <div class="input-group"><label>Celular</label><input type="tel" id="regPhone" value="5512345678"></div>
            <div class="input-group"><label>Usuario</label><input type="text" id="regUsername" value="artesano123"></div>
            <div class="input-group"><label>Contraseña</label><input type="password" id="regPassword" value="maderita"></div>
            <div class="input-group"><label>Confirmar</label><input type="password" id="regConfirmPassword" value="maderita"></div>
            <button id="submitRegisterBtn" class="btn-primary">Registrarse</button>`;
    } else {
        container.innerHTML = `<div class="input-group"><label>ID</label><input type="text" id="regId" value="ADMIN999"></div>
            <div class="input-group"><label>Correo</label><input type="email" id="regEmail" value="jefe@madera.com"></div>
            <div class="input-group"><label>Nombre</label><input type="text" id="regName" value="Carlos"></div>
            <div class="input-group"><label>Apellido</label><input type="text" id="regLastname" value="López"></div>
            <div class="input-group"><label>CURP</label><input type="text" id="regCurp" value="LOPC800101HDFRRN09"></div>
            <div class="input-group"><label>Seguro social</label><input type="text" id="regSsn" value="12345678901"></div>
            <button id="submitRegisterBtn" class="btn-primary">Registrar admin</button>`;
    }
    document.getElementById('submitRegisterBtn')?.addEventListener('click', handleRegister);
}
function handleRegister() {
    if (currentRole === 'user') {
        const email = document.getElementById('regEmail').value.trim();
        const phone = document.getElementById('regPhone').value.trim();
        const username = document.getElementById('regUsername').value.trim();
        const pwd = document.getElementById('regPassword').value;
        const confirm = document.getElementById('regConfirmPassword').value;
        if (!email || !phone || !username || !pwd) return showToastMessage("Complete campos", true);
        if (pwd !== confirm) return showToastMessage("Contraseñas no coinciden", true);
        const users = JSON.parse(localStorage.getItem('registeredUsers'));
        users.push({ email, phone, username, password: pwd });
        localStorage.setItem('registeredUsers', JSON.stringify(users));
        showToastMessage("✅ Registro exitoso. Inicia sesión.");
        showScreen('login');
    } else {
        const id = document.getElementById('regId').value.trim();
        const email = document.getElementById('regEmail').value.trim();
        const name = document.getElementById('regName').value.trim();
        const lastname = document.getElementById('regLastname').value.trim();
        const curp = document.getElementById('regCurp').value.trim();
        const ssn = document.getElementById('regSsn').value.trim();
        if (!id || !email || !name || !lastname || !curp || !ssn) return showToastMessage("Complete todos los campos", true);
        const admins = JSON.parse(localStorage.getItem('registeredAdmins'));
        admins.push({ identification: id, email, name, lastname, curp, ssn });
        localStorage.setItem('registeredAdmins', JSON.stringify(admins));
        showToastMessage("✅ Administrador registrado.");
        showScreen('login');
    }
}
function renderRecoverForm() {
    const container = document.getElementById('recoverFormContainer');
    if (currentRole === 'user') {
        container.innerHTML = `<div class="input-group"><label>Correo</label><input type="email" id="recoverEmail" value="cliente@madera.com"></div>
            <button id="submitRecoverBtn" class="btn-primary">Enviar contraseña</button>`;
    } else {
        container.innerHTML = `<div class="input-group"><label>Correo</label><input type="email" id="recoverEmail" value="admin@madera.com"></div>
            <div class="input-group"><label>ID admin principal</label><input type="text" id="recoverAdminId" value="ADMIN123"></div>
            <button id="submitRecoverBtn" class="btn-primary">Verificar</button>`;
    }
    document.getElementById('submitRecoverBtn')?.addEventListener('click', handleRecover);
}
function handleRecover() {
    if (currentRole === 'user') {
        const email = document.getElementById('recoverEmail').value.trim();
        const users = JSON.parse(localStorage.getItem('registeredUsers'));
        if (users.some(u => u.email === email)) showToastMessage(`📧 Enlace enviado a ${email}`);
        else showToastMessage("Correo no registrado", true);
    } else {
        const email = document.getElementById('recoverEmail').value.trim();
        const id = document.getElementById('recoverAdminId').value.trim();
        const admins = JSON.parse(localStorage.getItem('registeredAdmins'));
        const mainAdmin = admins.find(a => a.email === 'admin@madera.com' && a.identification === 'ADMIN123');
        if (email === mainAdmin?.email && id === mainAdmin?.identification) showToastMessage("🔐 Instrucciones enviadas");
        else showToastMessage("Credenciales inválidas", true);
    }
}
function showScreen(screenName) {
    const roleScreen = document.getElementById('roleScreen');
    const loginScreen = document.getElementById('loginScreen');
    const registerScreen = document.getElementById('registerScreen');
    const recoverScreen = document.getElementById('recoverScreen');
    roleScreen.classList.remove('active');
    loginScreen.classList.remove('active');
    registerScreen.classList.remove('active');
    recoverScreen.classList.remove('active');
    if (screenName === 'role') { roleScreen.classList.add('active'); currentRole = null; }
    else if (screenName === 'login') { loginScreen.classList.add('active'); renderLoginForm(); document.getElementById('loginTitle').innerText = currentRole === 'user' ? 'Inicio Usuario' : 'Acceso Admin'; }
    else if (screenName === 'register') { registerScreen.classList.add('active'); document.getElementById('registerTitle').innerText = currentRole === 'user' ? 'Registro Usuario' : 'Registro Admin'; renderRegisterForm(); }
    else if (screenName === 'recover') { recoverScreen.classList.add('active'); renderRecoverForm(); }
}
function bindAuthEvents() {
    document.getElementById('selectUserBtn')?.addEventListener('click', () => { currentRole = 'user'; setRoleTheme('user'); showScreen('login'); });
    document.getElementById('selectAdminBtn')?.addEventListener('click', () => { currentRole = 'admin'; setRoleTheme('admin'); showScreen('login'); });
    document.getElementById('backToRoleFromLogin')?.addEventListener('click', () => showScreen('role'));
    document.getElementById('goToRegister')?.addEventListener('click', () => showScreen('register'));
    document.getElementById('goToRecover')?.addEventListener('click', () => showScreen('recover'));
    document.getElementById('backToLoginFromRegister')?.addEventListener('click', () => showScreen('login'));
    document.getElementById('backToRoleFromRegister')?.addEventListener('click', () => showScreen('role'));
    document.getElementById('backToLoginFromRecover')?.addEventListener('click', () => showScreen('login'));
    document.getElementById('backToRoleFromRecover')?.addEventListener('click', () => showScreen('role'));
}
if (document.getElementById('authPage')) {
    bindAuthEvents();
    setRoleTheme(null);
    showScreen('role');
} else if (document.getElementById('ecommerceApp')) {
    initEcommerce();
}