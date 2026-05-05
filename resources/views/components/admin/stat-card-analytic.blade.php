@props([
    'title',
    'value',
    'trend' => null,
    'trendType' => 'neutral', // Opciones: positive, negative, neutral
    'icon' => 'document'
])

@php
    // Aquí ocurre la magia semántica de los colores
    $trendColors = [
        'positive' => 'text-green-700 bg-green-50 border-green-100',
        'negative' => 'text-red-700 bg-red-50 border-red-100',
        'neutral'  => 'text-gray-600 bg-gray-50 border-gray-100',
    ];
    
    $trendClass = $trendColors[$trendType] ?? $trendColors['neutral'];
@endphp

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col transition-all duration-200 hover:shadow-md">
    <div class="flex justify-between items-start mb-4">
        <h3 class="text-sm font-medium text-gray-500 font-inter">{{ $title }}</h3>
        
        <div class="p-2 bg-gray-50 rounded-md text-gray-400">
            @if($icon == 'money')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @elseif($icon == 'bag')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            @elseif($icon == 'users')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            @endif
        </div>
    </div>
    
    <div class="flex items-baseline gap-3 mt-auto">
        <!-- Playfair Display para los números impactantes -->
        <span class="text-3xl font-semibold text-gray-900 font-playfair">{{ $value }}</span>
        
        @if($trend)
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $trendClass }}">
                {{ $trend }}
            </span>
        @endif
    </div>
</div>