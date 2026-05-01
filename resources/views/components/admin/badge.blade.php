@props(['color' => 'gray', 'label' => ''])

@php
    $colors = [
        'green'  => 'bg-green-100 text-green-800',
        'red'    => 'bg-red-100 text-red-800',
        'yellow' => 'bg-yellow-100 text-yellow-800',
        'blue'   => 'bg-blue-100 text-blue-800',
        'gray'   => 'bg-gray-100 text-gray-800',
    ];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $colors[$color] ?? $colors['gray'] }}">
    {{ $label }}
</span>