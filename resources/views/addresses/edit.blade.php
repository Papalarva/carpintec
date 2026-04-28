<x-app-layout>
    <x-slot:title>Editar dirección</x-slot:title>

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Editar dirección</h1>
        <form action="{{ route('addresses.update', $address) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @method('PUT')
            @include('addresses._form', ['address' => $address])
        </form>
    </div>
</x-app-layout>