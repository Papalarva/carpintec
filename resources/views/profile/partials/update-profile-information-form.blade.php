<section>
    <header class="mb-8">
        <h3 class="font-serif text-2xl text-gray-900">
            Información Personal
        </h3>
        <p class="font-sans mt-2 text-sm text-gray-600">
            Actualiza tu información de contacto y nombre para tu cuenta.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="font-sans space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                <input id="first_name" name="first_name" type="text" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('first_name', $user->first_name) }}" required autofocus autocomplete="given-name" />
                @error('first_name')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Apellidos</label>
                <input id="last_name" name="last_name" type="text" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('last_name', $user->last_name) }}" required autocomplete="family-name" />
                @error('last_name')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <input id="email" name="email" type="email" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                @error('email')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                <input id="phone" name="phone" type="tel" class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors" value="{{ old('phone', $user->phone) }}" autocomplete="tel" />
                @error('phone')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center pt-4">
            <button type="submit" class="bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm">
                Guardar Cambios
            </button>
        </div>
    </form>
</section>