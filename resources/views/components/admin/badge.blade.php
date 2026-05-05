@props(['color' => 'gray', 'label' => ''])

@php
    $colors = [
        'green'  => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
        'red'    => 'bg-rose-50 text-rose-700 border border-rose-200',
        'yellow' => 'bg-amber-50 text-amber-700 border border-amber-200',
        'terracota' => 'bg-[#C15C3D]/10 text-[#C15C3D] border border-[#C15C3D]/20',
        'gray'   => 'bg-gray-50 text-gray-600 border border-gray-200',
    ];
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium font-inter {{ $colors[$color] ?? $colors['gray'] }}">
    {{ $label }}
</span>