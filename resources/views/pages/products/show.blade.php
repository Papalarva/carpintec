@php
    /** @var string|null $slug */
    $slug = $slug ?? null;
@endphp

<x-layouts.app variant="store" :title="'Producto'">
    <section class="flex flex-col gap-8">
        <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
            <a class="hover:text-zinc-900 dark:hover:text-zinc-50" href="{{ route('store.home') }}">Inicio</a>
            <span class="text-zinc-300 dark:text-zinc-700">/</span>
            <a class="hover:text-zinc-900 dark:hover:text-zinc-50" href="{{ route('products.index') }}">Productos</a>
            <span class="text-zinc-300 dark:text-zinc-700">/</span>
            <span class="text-zinc-900 dark:text-zinc-50">{{ $slug ?? 'detalle' }}</span>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="overflow-hidden rounded-3xl border border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
                <div class="aspect-[4/3] bg-zinc-100 dark:bg-zinc-900">
                    <img
                        class="h-full w-full object-cover"
                        src="https://picsum.photos/seed/{{ $slug ?? 'product' }}/1200/900"
                        alt="Producto"
                        loading="lazy"
                    />
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">
                        Producto (placeholder)
                    </h1>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                        Vista de detalle estática. El slug actual es <span class="font-medium text-zinc-900 dark:text-zinc-50">{{ $slug ?? '—' }}</span>.
                    </p>
                </div>

                <div class="flex items-baseline justify-between gap-4">
                    <div class="text-2xl font-semibold text-zinc-900 dark:text-zinc-50">$999.00</div>
                    <div class="rounded-full border border-zinc-200 bg-white px-3 py-1 text-xs font-medium text-zinc-700 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-200">
                        En stock (placeholder)
                    </div>
                </div>

                <div class="rounded-2xl border border-zinc-200 bg-white p-4 text-sm text-zinc-700 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-200">
                    Descripción (placeholder). Aquí se mostrará la descripción real del producto cuando exista lógica de negocio.
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-zinc-50 dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Agregar al carrito (placeholder)
                    </button>
                    <a
                        href="{{ route('products.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-50 dark:hover:bg-zinc-900"
                    >
                        Volver al catálogo
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
