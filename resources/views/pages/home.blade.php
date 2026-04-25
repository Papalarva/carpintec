<x-layouts.app variant="store" :title="'Inicio'">
    <section class="flex flex-col gap-10">
        <div class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div class="max-w-2xl">
                    <h1 class="text-3xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50 sm:text-4xl">
                        E-commerce UI scaffolding
                    </h1>
                    <p class="mt-3 text-base text-zinc-600 dark:text-zinc-300">
                        Home estática con Tailwind v4. Sin lógica de negocio, sin checkout, sin queries.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        href="{{ route('products.index') }}"
                        class="rounded-xl bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-zinc-800 dark:bg-zinc-50 dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Ver productos
                    </a>
                    <a
                        href="{{ route('products.show', ['slug' => 'producto-demo']) }}"
                        class="rounded-xl border border-zinc-200 bg-white px-4 py-2.5 text-sm font-semibold text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-50 dark:hover:bg-zinc-900"
                    >
                        Ver detalle demo
                    </a>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50">Destacados</h2>
            <a class="text-sm font-medium text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-zinc-50" href="{{ route('products.index') }}">
                Ir al catálogo
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <x-product-card
                productName="Silla ergonómica de oficina"
                price="1299.00"
                imageUrl="https://picsum.photos/seed/chair/800/600"
                :href="route('products.show', ['slug' => 'silla-ergonomica'])"
            />
            <x-product-card
                productName="Escritorio minimalista"
                price="2499.00"
                imageUrl="https://picsum.photos/seed/desk/800/600"
                :href="route('products.show', ['slug' => 'escritorio-minimalista'])"
            />
            <x-product-card
                productName="Lámpara de lectura"
                price="499.00"
                imageUrl="https://picsum.photos/seed/lamp/800/600"
                :href="route('products.show', ['slug' => 'lampara-lectura'])"
            />
        </div>
    </section>
</x-layouts.app>
