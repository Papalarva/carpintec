{{--
    Componente Anónimo: Tarjeta de Producto
    Uso: <x-product-card :product="$product" />

    Props:
      $product->id, $product->name, $product->price,
      $product->category, $product->images (array/json),
      $product->rating, $product->brand, $product->stock
--}}
@props(['product'])

@php
    $images = is_array($product->images) ? $product->images : json_decode($product->images, true);
    $firstImage = $images[0] ?? 'https://picsum.photos/id/158/400/300';
@endphp

<div class="product-card" data-id="{{ $product->id }}" role="button" tabindex="0"
     aria-label="Ver {{ $product->name }}">
    <img class="product-img"
         src="{{ $firstImage }}"
         alt="{{ $product->name }}"
         loading="lazy">
    <div class="product-info">
        <div class="product-title">{{ $product->name }}</div>
        <div class="product-price">${{ number_format($product->price, 2) }}</div>
        <div class="rating">
            @for ($i = 0; $i < floor($product->rating ?? 0); $i++) ★ @endfor
            ({{ $product->rating ?? 0 }})
        </div>
        <small>{{ $product->brand ?? '' }}</small>
    </div>
</div>
