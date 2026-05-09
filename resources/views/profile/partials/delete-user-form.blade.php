<section class="font-sans" x-data="{ confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">    <header class="mb-8">
        <h3 class="font-serif text-2xl text-gray-900">
            Eliminar Cuenta
        </h3>
        <p class="mt-2 text-sm text-gray-600">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier información que desees conservar.
        </p>
    </header>

    <button
        @click="confirmingUserDeletion = true"
        class="inline-flex items-center justify-center bg-white border border-red-200 text-red-700 hover:bg-red-50 hover:border-red-300 uppercase tracking-widest text-sm font-bold rounded-xl px-8 py-4 transition-colors duration-200 shadow-sm"
    >
        Eliminar Cuenta
    </button>

    <div
        x-show="confirmingUserDeletion"
        style="display: none;"
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div
                x-show="confirmingUserDeletion"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm"
                aria-hidden="true"
            ></div>

            <div
                x-show="confirmingUserDeletion"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                @click.away="confirmingUserDeletion = false"
                @keydown.escape.window="confirmingUserDeletion = false"
                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-8"
            >
                <div class="sm:flex sm:items-start">
                    <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-50 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-xl font-serif text-gray-900" id="modal-title">
                            ¿Estás seguro que deseas eliminar tu cuenta?
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán permanentemente. Por favor, ingresa tu contraseña para confirmar.
                            </p>
                        </div>
                    </div>
                </div>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                    @csrf
                    @method('delete')

                    <div class="mt-4">
                        <label for="password" class="sr-only">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full rounded-xl border-gray-200 py-3.5 focus:border-red-800 focus:ring-red-800 shadow-sm transition-colors"
                            placeholder="Tu contraseña"
                            x-ref="password"
                            @keyup.enter="$el.closest('form').submit()"
                        />
                        @error('password', 'userDeletion')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-8 sm:flex sm:flex-row-reverse">
                        <button
                            type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent bg-red-800 px-6 py-3 text-base font-bold text-white shadow-sm hover:bg-red-900 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm uppercase tracking-widest transition-colors duration-200"
                        >
                            Eliminar Cuenta
                        </button>
                        <button
                            type="button"
                            @click="confirmingUserDeletion = false"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 bg-white px-6 py-3 text-base font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm uppercase tracking-widest transition-colors duration-200"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>