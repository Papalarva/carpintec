{{--
    Vista: Panel de Administración (página completa)
    Ruta: resources/views/admin/index.blade.php
    Middleware: auth + role:admin
--}}
@extends('layouts.carpintec')

@section('title', 'Panel Admin - CARPINTEC')

@push('styles')
<style>
    /* Estilos específicos del admin (complementan carpintec.css) */
    .admin-nav {
        background: #1e3a5f;
        color: white;
        padding: 0 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 70px;
    }
    .admin-nav .logo { font-size: 1.5rem; font-weight: bold; }
    .admin-nav .tabs { display: flex; gap: 24px; }
    .admin-nav .tab {
        background: none;
        border: none;
        color: white;
        font-size: 1rem;
        padding: 8px 0;
        cursor: pointer;
        border-bottom: 2px solid transparent;
    }
    .admin-nav .tab.active { border-bottom-color: #f39c12; }
    .admin-content { padding: 30px; max-width: 1400px; margin: 0 auto; }
    .admin-section { display: none; }
    .admin-section.active { display: block; }

    /* Inventario */
    .inventory-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    .inventory-card {
        background: white;
        border-radius: 20px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .inventory-card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 12px;
    }
    .stock-control { display: flex; align-items: center; gap: 8px; margin: 10px 0; }
    .stock-control button {
        background: #e2e8f0;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 30px;
        cursor: pointer;
    }

    /* Solicitudes */
    .requests-list { display: flex; flex-direction: column; gap: 20px; }
    .request-card {
        background: white;
        border-radius: 20px;
        padding: 16px;
        display: flex;
        gap: 16px;
        border-left: 6px solid;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .request-card.custom { border-left-color: #f39c12; }
    .request-card.express { border-left-color: #e74c3c; }
    .request-avatar {
        width: 60px;
        height: 60px;
        background: #ddd;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #666;
    }
    .request-details { flex: 1; }

    /* Estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 24px;
        text-align: center;
    }
</style>
@endpush

@section('content')

{{-- ===== Navbar Admin ===== --}}
<nav class="admin-nav" role="navigation" aria-label="Panel de administración">
    <div class="logo">
        <i class="fas fa-tree" aria-hidden="true"></i> CARPINTEC
    </div>
    <div class="tabs" role="tablist">
        <button class="tab active" data-tab="inventory" role="tab" aria-selected="true">Inventario</button>
        <button class="tab" data-tab="requests" role="tab" aria-selected="false">Solicitudes</button>
        <button class="tab" data-tab="stats" role="tab" aria-selected="false">Estadísticas</button>
    </div>
    <div>
        <i class="fas fa-sign-out-alt" id="adminLogoutBtn"
           style="cursor: pointer; font-size: 1.2rem;"
           role="button" tabindex="0" aria-label="Cerrar sesión"></i>
    </div>
</nav>

{{-- ===== Contenido Admin ===== --}}
<div class="admin-content">

    {{-- Sección: Inventario --}}
    <section id="inventorySection" class="admin-section active" role="tabpanel" aria-label="Inventario">
        <h2>Gestión de Inventario</h2>
        <div class="inventory-grid" id="inventoryGrid">
            {{-- El JS renderiza las tarjetas de inventario aquí --}}
        </div>
        <div class="admin-form" style="background:#f1f5f9; margin-top:30px;">
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
            <textarea id="newProductDescription" placeholder="Descripción"></textarea>
            <input type="number" id="newProductStock" placeholder="Stock" value="10">
            <button id="addProductBtn" class="btn-primary">➕ Agregar producto</button>
        </div>
    </section>

    {{-- Sección: Solicitudes --}}
    <section id="requestsSection" class="admin-section" role="tabpanel" aria-label="Solicitudes de clientes">
        <h2>Solicitudes de clientes</h2>
        <div class="requests-list" id="requestsList">
            {{-- El JS renderiza los pedidos aquí --}}
        </div>
    </section>

    {{-- Sección: Estadísticas --}}
    <section id="statsSection" class="admin-section" role="tabpanel" aria-label="Estadísticas">
        <h2>Estadísticas rápidas</h2>
        <div class="stats-grid" id="statsGrid">
            {{-- El JS renderiza las tarjetas de stats aquí --}}
        </div>
    </section>

</div>{{-- /.admin-content --}}

<x-toast />
@endsection

@push('scripts')
    <script src="{{ asset('js/admin.js') }}"></script>
@endpush
