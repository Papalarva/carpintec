<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Por tu seguridad, por favor ingresa el código de 6 dígitos que acabamos de enviar a tu correo electrónico.') }}
    </div>

    <!-- Mostrar errores si el código está mal -->
    @if ($errors->any())
        <div class="mb-4">
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <!-- Input del Código -->
        <div>
            <x-input-label for="code" value="{{ __('Código de Verificación') }}" />
            
            <x-text-input id="code" class="block mt-1 w-full tracking-widest text-center text-2xl" 
                          type="text" 
                          name="code" 
                          required 
                          autofocus 
                          maxlength="6"
                          placeholder="123456" />
        </div>

        <!-- 👇 LA NUEVA CASILLA DE CONFIANZA 👇 -->
        <div class="block mt-4">
            <label for="remember_device" class="inline-flex items-center">
                <input id="remember_device" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember_device">
                <span class="ms-2 text-sm text-gray-600">{{ __('No volver a pedir en este equipo por 30 días') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verificar y Entrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>