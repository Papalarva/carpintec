@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 py-4 border-b-2 border-amber-900 text-sm font-semibold leading-5 text-amber-900 focus:outline-none transition duration-150 ease-in-out whitespace-nowrap font-sans'
            : 'inline-flex items-center px-1 py-4 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out whitespace-nowrap font-sans';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>