@props([
    'title',
    'value',
    'trend' => null,
    'trendType' => 'neutral',
    'icon' => 'document'
])

@php
    $trendColors = [
        'positive' => 'text-emerald-700 bg-emerald-50 border-emerald-100',
        'negative' => 'text-rose-700 bg-rose-50 border-rose-100',
        'neutral'  => 'text-gray-600 bg-gray-50 border-gray-100',
    ];
    $trendClass = $trendColors[$trendType] ?? $trendColors['neutral'];
@endphp

<div class="relative overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col transition-all duration-300 hover:shadow-md hover:border-gray-200 group">
    
    {{-- Micro-tendencia decorativa de fondo (Sparkline abstracta) --}}
    @if($trendType === 'positive')
        <svg class="absolute bottom-0 right-0 w-32 h-16 text-emerald-50 opacity-50 transform translate-y-2 translate-x-2 group-hover:scale-110 transition-transform duration-500" viewBox="0 0 100 50" fill="none" preserveAspectRatio="none">
            <path d="M0,50 L20,30 L40,40 L60,10 L80,20 L100,0 L100,50 Z" fill="currentColor"></path>
        </svg>
    @endif

    <div class="relative z-10 flex justify-between items-start mb-4">
        <h3 class="text-xs font-bold text-gray-500 font-sans uppercase tracking-widest">{{ $title }}</h3>
        
        <div class="p-2 bg-gray-50 rounded-xl text-gray-400 border border-gray-100 shadow-sm">
            @if($icon == 'money')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @elseif($icon == 'bag')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            @elseif($icon == 'users')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            @endif
        </div>
    </div>
    
    <div class="relative z-10 flex items-baseline gap-3 mt-auto">
        <span class="text-3xl font-bold text-gray-900 font-serif">{{ $value }}</span>
        
        @if($trend)
            <span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[10px] font-bold tracking-widest uppercase border font-sans {{ $trendClass }}">
                {{ $trend }}
            </span>
        @endif
    </div>
</div>