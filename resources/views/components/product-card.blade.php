@props([
    'productName' => null,
    'price' => null,
    'imageUrl' => null,
    'href' => null,
    'product' => null,
])

@php
    $resolvedProductName = $productName ?? $product?->name ?? 'Producto';
    $resolvedPrice = $price ?? $product?->price ?? null;
    $resolvedImageUrl = $imageUrl ?? null;
    $resolvedHref = $href ?? null;
@endphp

<a
    href="{{ $resolvedHref ?? '#' }}"
    class="group block overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-950"
>
    <div class="aspect-[4/3] w-full overflow-hidden bg-zinc-100 dark:bg-zinc-900">
        <img
            src="{{ $resolvedImageUrl ?? 'https://picsum.photos/seed/carpintec/800/600' }}"
            alt="{{ $resolvedProductName }}"
            loading="lazy"
            class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
        />
    </div>

    <div class="flex flex-col gap-2 p-4">
        <div class="flex items-start justify-between gap-3">
            <h3 class="line-clamp-2 text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                {{ $resolvedProductName }}
            </h3>

            @if ($resolvedPrice !== null)
                <div class="shrink-0 text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                    ${{ number_format((float) $resolvedPrice, 2) }}
                </div>
            @endif
        </div>

        <div class="text-sm text-zinc-600 dark:text-zinc-300">
            Ver detalles
        </div>
    </div>
</a>
