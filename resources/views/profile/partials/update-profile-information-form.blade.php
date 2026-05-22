<section>
    <header class="mb-6">
        <h3 class="font-serif text-2xl text-gray-900">
            Información Personal
        </h3>
        <p class="font-sans mt-2 text-sm text-gray-600">
            Actualiza tu información de contacto y preferencias de cuenta.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="font-sans space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre(s)</label>
                <input id="first_name" name="first_name" type="text" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="given-name" />
                @error('first_name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Apellidos</label>
                <input id="last_name" name="last_name" type="text" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name" />
                @error('last_name') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <input id="email" name="email" type="email" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-4 bg-amber-50 rounded-xl border border-amber-100 flex flex-col sm:flex-row items-start sm:items-center">
                        <svg class="h-5 w-5 text-amber-800 mr-2 flex-shrink-0 mb-2 sm:mb-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-amber-800 leading-snug">
                            Tu dirección de correo no está verificada. 
                            <button form="send-verification" class="font-bold underline underline-offset-2 hover:text-amber-900 focus:outline-none transition-colors">
                                Clic aquí para reenviar enlace.
                            </button>
                        </p>
                    </div>
                @endif
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                <input id="phone" name="phone" type="tel" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('phone', $user->phone) }}" autocomplete="tel" />
                @error('phone') <p class="text-sm text-red-600 mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 mt-6">
            <div class="flex items-start mt-2">
                <div class="flex items-center h-5">
                    <input id="accepts_marketing" name="accepts_marketing" type="checkbox" value="1" class="w-4 h-4 rounded border-gray-300 text-amber-900 focus:ring-amber-800 transition-colors cursor-pointer" {{ (old('accepts_marketing') || $user->customer?->accepts_marketing) ? 'checked' : '' }}>
                </div>
                <div class="ml-3 text-sm">
                    <label for="accepts_marketing" class="font-sans font-medium text-gray-900 cursor-pointer">Suscripción al Newsletter</label>
                    <p class="text-gray-500 font-sans text-xs mt-0.5">Deseo recibir correos con promociones exclusivas, nuevas colecciones e inspiración de Carpintec.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center pt-2">
            <button type="submit" class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-3.5 transition-colors duration-200 shadow-sm">
                Guardar Cambios
            </button>
        </div>
    </form>
</section>