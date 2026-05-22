@props([
    'title',
    'count' => 0,
    'description',
    'route',
    'buttonText',
    'icon',
    'type' => 'neutral' // amber, rose, neutral
])

@php
    $styles = [
        'amber' => [
            'bg' => 'bg-amber-50', 'text' => 'text-amber-900', 'count' => 'text-amber-700',
            'btn' => 'bg-amber-900 text-white hover:bg-amber-800'
        ],
        // Actualizado de 'red' a 'rose' para sincronizar con la alerta del Dashboard
        'rose' => [
            'bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'count' => 'text-rose-600',
            'btn' => 'bg-white border border-rose-200 text-rose-700 hover:bg-rose-50'
        ],
        'neutral' => [
            'bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'count' => 'text-gray-900',
            'btn' => 'bg-gray-900 text-white hover:bg-gray-800'
        ],
    ];
    $theme = $styles[$type] ?? $styles['neutral'];
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col items-center text-center transition-all duration-300 hover:shadow-lg hover:border-gray-200 hover:-translate-y-1">
    
    <div class="p-4 {{ $theme['bg'] }} {{ $theme['text'] }} rounded-2xl mb-6 shadow-sm border border-white/50">
        @if($icon == 'clipboard')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        @elseif($icon == 'box')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
        @elseif($icon == 'warning')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        @endif
    </div>
    
    <h3 class="text-xl font-bold text-gray-900 font-serif mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-500 font-sans mb-8">
        Hay <strong class="{{ $theme['count'] }} text-lg font-bold">{{ $count }}</strong> {!! $description !!}
    </p>
    
    <a href="{{ $route }}" class="mt-auto w-full inline-flex items-center justify-center {{ $theme['btn'] }} uppercase tracking-widest text-xs font-bold rounded-xl px-8 py-4 transition-all duration-200 shadow-sm focus:outline-none font-sans">
        {{ $buttonText }}
    </a>
</div>