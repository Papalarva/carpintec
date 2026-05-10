<x-app-layout>
    <x-slot:title>{{ $quotation->subject }}</x-slot:title>

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $quotation->subject }}</h1>

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <span class="text-sm text-gray-500">Estado:</span>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
        @switch($quotation->status->value)
            @case('pending') bg-yellow-100 text-yellow-800 @break
            @case('reviewing') bg-blue-100 text-blue-800 @break
            @case('quoted') bg-indigo-100 text-indigo-800 @break
            @case('accepted') bg-green-100 text-green-800 @break
            @case('rejected') bg-red-100 text-red-800 @break
            @default bg-gray-100 text-gray-800
        @endswitch">
                    {{ ucfirst($quotation->status->value) }}
                </span>
            </div>

            <div>
                <h3 class="text-sm font-medium text-gray-700">Descripción</h3>
                <p class="mt-1 text-gray-600">{{ $quotation->description }}</p>
            </div>

            @if ($quotation->estimated_price)
                <div>
                    <h3 class="text-sm font-medium text-gray-700">Precio estimado</h3>
                    <p class="mt-1 text-gray-600">${{ number_format($quotation->estimated_price, 2) }}</p>
                </div>
            @endif

            @if ($quotation->attachments && count($quotation->attachments) > 0)
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Adjuntos</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach ($quotation->attachments as $attachment)
                            @php
                                $url = route('quotations.download', [$quotation, basename($attachment)]);
                            @endphp
                            <a href="{{ $url }}" target="_blank"
                                class="flex items-center p-2 border rounded hover:bg-gray-50">
                                <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="text-sm truncate">{{ basename($attachment) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="pt-4 border-t">
                <a href="{{ route('quotations.index') }}" class="text-indigo-600 hover:text-indigo-900">← Volver a mis
                    cotizaciones</a>
            </div>
        </div>
    </div>
</x-app-layout>
