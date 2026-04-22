{{--
    Vista: Login / Registro / Recuperación de contraseña
    Ruta: resources/views/auth/login.blade.php
    Controlador: el JS maneja la navegación entre pantallas internamente.
    Para integrar con Fortify, conectar handleLogin() al endpoint POST /login.
--}}
@extends('layouts.carpintec')

@section('title', 'CARPINTEC - Acceso')

@section('content')
<div id="authPage" class="auth-page">
    <div id="authOverlay" class="auth-overlay"></div>

    <div class="auth-card" id="authCard">

        {{-- ===== Pantalla: Selección de rol ===== --}}
        <div id="roleScreen" class="screen active">
            <h2>🌲 CARPINTEC</h2>
            <p class="sub">Artesanía en madera para tu hogar</p>
            <button id="selectUserBtn" class="btn-primary" style="background:#b45f2b;">
                Ingresar como Usuario
            </button>
            <button id="selectAdminBtn" class="btn-primary" style="background:#1e3a5f; margin-top:12px;">
                Ingresar como Administrador
            </button>
        </div>

        {{-- ===== Pantalla: Inicio de sesión ===== --}}
        <div id="loginScreen" class="screen">
            <h2 id="loginTitle">Iniciar sesión</h2>
            <p class="sub" id="loginSub">Accede a tu cuenta</p>
            {{-- El JS inyecta los campos según el rol seleccionado --}}
            <div id="loginFormContainer"></div>
            <div class="flex-between">
                <button id="backToRoleFromLogin" class="back-link">← Volver</button>
                <div>
                    <button id="goToRegister" class="back-link">¿No tienes cuenta? Regístrate</button>
                    <button id="goToRecover" class="back-link" style="margin-left:12px;">
                        ¿Olvidaste tu contraseña?
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== Pantalla: Registro ===== --}}
        <div id="registerScreen" class="screen">
            <h2 id="registerTitle">Registro</h2>
            <p class="sub" id="registerSub">Crea una nueva cuenta</p>
            <div id="registerFormContainer"></div>
            <div class="flex-between">
                <button id="backToLoginFromRegister" class="back-link">← Volver al inicio de sesión</button>
                <button id="backToRoleFromRegister" class="back-link">Cambiar rol</button>
            </div>
        </div>

        {{-- ===== Pantalla: Recuperar contraseña ===== --}}
        <div id="recoverScreen" class="screen">
            <h2 id="recoverTitle">Recuperar contraseña</h2>
            <p class="sub" id="recoverSub">Te ayudaremos a recuperar el acceso</p>
            <div id="recoverFormContainer"></div>
            <div class="flex-between">
                <button id="backToLoginFromRecover" class="back-link">← Volver al inicio</button>
                <button id="backToRoleFromRecover" class="back-link">Cambiar rol</button>
            </div>
        </div>

    </div>{{-- /.auth-card --}}
</div>{{-- /#authPage --}}

<x-toast />
@endsection

@push('scripts')
    <script src="{{ asset('js/carpintec.js') }}"></script>
@endpush
