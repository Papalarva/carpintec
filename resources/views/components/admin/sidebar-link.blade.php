@props(['active'])

@php
$classes = ($active ?? false)
            // ESTADO ACTIVO: Texto Terracota (#C15C3D) y borde inferior grueso
            ? 'inline-flex items-center px-1 py-4 border-b-2 border-[#C15C3D] text-sm font-medium leading-5 text-[#C15C3D] focus:outline-none transition duration-150 ease-in-out whitespace-nowrap'
            
            // ESTADO INACTIVO: Texto gris, al pasar el mouse se oscurece y sale un borde gris suave
            : 'inline-flex items-center px-1 py-4 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out whitespace-nowrap';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>