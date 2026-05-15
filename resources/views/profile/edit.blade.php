<x-app-layout>
    <x-slot:title>
        Mi Perfil | Carpintec
    </x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="mb-6 px-4 sm:px-0">
                <h2 class="font-serif text-3xl text-gray-900 leading-tight">
                    Mi Perfil
                </h2>
                <p class="font-sans text-gray-500 mt-2 text-sm">
                    Gestiona tu información personal y la seguridad de tu cuenta.
                </p>
            </div>

            <div class="p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-8 bg-white shadow-sm rounded-2xl border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-8 bg-white shadow-sm rounded-2xl border border-red-50">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>