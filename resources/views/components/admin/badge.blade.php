@props(['color' => 'gray', 'label' => ''])

@php
    $colors = [
        'green'     => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'red'       => 'bg-rose-50 text-rose-700 border border-rose-200',
        'yellow'    => 'bg-amber-50 text-amber-700 border border-amber-200',
        'terracota' => 'bg-amber-50 text-amber-900 border border-amber-200',
        'gray'      => 'bg-gray-50 text-gray-600 border border-gray-200',
    ];
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[11px] font-bold uppercase tracking-widest font-sans {{ $colors[$color] ?? $colors['gray'] }}">
    {{ $label }}
</span>