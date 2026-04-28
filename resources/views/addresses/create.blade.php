<x-app-layout>
    <x-slot:title>Nueva dirección</x-slot:title>

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Nueva dirección</h1>
        <form action="{{ route('addresses.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @include('addresses._form')
        </form>
    </div>
</x-app-layout>