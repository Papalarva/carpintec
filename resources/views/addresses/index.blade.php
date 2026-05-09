<x-app-layout>
    <x-slot:title>Mis direcciones</x-slot:title>

    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Mis direcciones</h1>
            <a href="{{ route('addresses.create') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva dirección
            </a>
        </div>

        @if ($addresses->isEmpty())
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">No tienes direcciones registradas.</p>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($addresses as $address)
                    <div class="bg-white rounded-lg shadow p-6 relative">
                        @if ($address->is_primary)
                            <span
                                class="absolute top-2 right-2 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                Principal
                            </span>
                        @endif

                        <h3 class="text-lg font-semibold text-gray-900">{{ $address->alias ?? $address->street }}
                            {{ $address->exterior_number }}</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            {{ $address->street }} {{ $address->exterior_number }}
                            {{ $address->interior_number ? 'Int. ' . $address->interior_number : '' }}<br>
                            Col. {{ $address->neighborhood }}<br>
                            {{ $address->city }}, {{ $address->state }}, C.P. {{ $address->postal_code }}<br>
                            {{ $address->country ?? 'México' }}
                        </p>
                        @if ($address->contact_phone)
                            <p class="mt-2 text-sm text-gray-500">Tel: {{ $address->contact_phone }}</p>
                        @endif

                        <div class="mt-4 flex space-x-3">
                            <a href="{{ route('addresses.edit', $address) }}"
                                class="text-indigo-600 hover:text-indigo-900 text-sm">Editar</a>
                            @if (!$address->is_primary)
                                <form action="{{ route('addresses.set-primary', $address) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-900 text-sm">Hacer
                                        principal</button>
                                </form>
                            @endif
                            <form action="{{ route('addresses.destroy', $address) }}" method="POST"
                                class="inline ml-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm"
                                    onclick="return confirm('¿Eliminar esta dirección?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
