<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Carpintec') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-100">
        
        <main>
            {{ $slot }}
        </main>

        <div id="toast"
            class="fixed bottom-4 right-4 z-50 hidden bg-green-600 text-white px-6 py-3 rounded-xl shadow-premium flex items-center space-x-2 transition-opacity duration-300"
            style="opacity:0;">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span id="toast-message" class="font-sans text-sm font-medium"></span>
        </div>

        <script>
            window.showToast = function(mensaje, isError = false) {
                const toast = document.getElementById('toast');
                const msg = document.getElementById('toast-message');
                if (!toast || !msg) return;

                msg.textContent = mensaje;
                toast.classList.remove('hidden', 'bg-green-600', 'bg-red-600');
                toast.classList.add(isError ? 'bg-red-600' : 'bg-green-600');
                
                toast.style.opacity = '1';
                const timeVisible = isError ? 4500 : 2500;

                setTimeout(() => { 
                    toast.style.opacity = '0'; 
                    setTimeout(() => { toast.classList.add('hidden'); }, 300);
                }, timeVisible);
            }

            document.addEventListener('DOMContentLoaded', () => {
                @if(session('success')) window.showToast("{{ session('success') }}", false); @endif
                @if(session('error')) window.showToast("{{ session('error') }}", true); @endif
                @if(session('info')) window.showToast("{{ session('info') }}", false); @endif
                @if($errors->any()) window.showToast("Verifica los errores en el formulario.", true); @endif
            });
        </script>
    </body>
</html>