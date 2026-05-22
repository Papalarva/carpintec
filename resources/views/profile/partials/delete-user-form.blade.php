<section class="font-sans" x-data="{
    confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }},
    init() {
        this.$watch('confirmingUserDeletion', value => {
            if (value) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        });
    }
}">    
    <header class="mb-6">
        <h3 class="font-serif text-2xl text-gray-900">
            Eliminar Cuenta
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            Una vez que tu cuenta sea eliminada, todos sus recursos, cotizaciones y datos se borrarán permanentemente.
        </p>
    </header>

    <button
        @click="confirmingUserDeletion = true"
        class="inline-flex items-center justify-center bg-white border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 uppercase tracking-widest text-sm font-bold rounded-xl px-6 py-3.5 transition-colors duration-200 shadow-sm"
    >
        Eliminar Cuenta
    </button>

    <div
        x-show="confirmingUserDeletion"
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        
        <div
            x-show="confirmingUserDeletion"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm"
            aria-hidden="true"
        ></div>

        <div
            x-show="confirmingUserDeletion"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.away="confirmingUserDeletion = false"
            @keydown.escape.window="confirmingUserDeletion = false"
            class="relative inline-block w-full max-w-lg overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl p-6 sm:p-8"
        >
            <div class="sm:flex sm:items-start">
                <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto sm:mx-0 sm:h-12 sm:w-12 bg-red-50 rounded-full border border-red-100">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="mt-4 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-xl font-serif text-gray-900" id="modal-title">
                        ¿Eliminar cuenta permanentemente?
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 leading-relaxed">
                            Esta acción es irreversible. Todos tus datos serán borrados. Por favor, ingresa tu contraseña para confirmar tu identidad.
                        </p>
                    </div>
                </div>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                @csrf
                @method('delete')

                <div class="mt-4" x-data="{ show: false }">
                    <label for="password" class="sr-only">Contraseña</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-red-800 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        
                        <input
                            :type="show ? 'text' : 'password'"
                            name="password"
                            id="password"
                            class="w-full pl-11 pr-12 rounded-xl border-gray-200 py-3 focus:border-red-600 focus:ring-red-600 shadow-sm transition-colors"
                            placeholder="Tu contraseña actual"
                            x-ref="password"
                            @keyup.enter="$el.closest('form').submit()"
                        />
                        
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-red-800 transition-colors focus:outline-none">
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <svg x-show="show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" x-cloak><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('password', 'userDeletion')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
                    <button
                        type="button"
                        @click="confirmingUserDeletion = false"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-sm font-bold text-gray-600 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-xl transition-colors duration-200 focus:outline-none uppercase tracking-widest"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-sm transition-colors duration-200 focus:outline-none uppercase tracking-widest"
                    >
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>