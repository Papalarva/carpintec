<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('head')
    <meta name="robots" content="index, follow">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="pt-24">
            {{ $slot }}
        </main>

        <!-- Contenedor de toast (mensaje flotante) -->
        <div id="toast"
            class="fixed bottom-4 right-4 z-50 hidden bg-green-600 text-white px-6 py-3 rounded-md shadow-lg flex items-center space-x-2 transition-opacity duration-300"
            style="opacity:0;">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toast-message"></span>
        </div>
        @include('layouts.footer')
    </div>
    <script>
        // 1. Definición global de tu función
        window.showToast = function(mensaje, isError = false) {
            const toast = document.getElementById('toast');
            const msg = document.getElementById('toast-message');
            if (!toast || !msg) return;

            msg.textContent = mensaje;
            toast.classList.remove('hidden', 'bg-green-600', 'bg-red-600');
            
            if (isError) {
                toast.classList.add('bg-red-600');
            } else {
                toast.classList.add('bg-green-600');
            }
            
            toast.style.opacity = '1';
            const timeVisible = isError ? 4500 : 2500;

            setTimeout(() => { 
                toast.style.opacity = '0'; 
                setTimeout(() => { toast.classList.add('hidden'); }, 300); // Ocultar del DOM tras la transición
            }, timeVisible);
        }

        // 2. Interceptar mensajes Flash de Laravel y Errores de Validación
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                window.showToast("{{ session('success') }}", false);
            @endif

            @if(session('error'))
                window.showToast("{{ session('error') }}", true);
            @endif

            // NUEVO: Escuchar errores de validación de formularios
            @if($errors->any())
                window.showToast("Por favor, verifica los errores en el formulario.", true);
            @endif
        });
    </script>
    
</body>

</html>
