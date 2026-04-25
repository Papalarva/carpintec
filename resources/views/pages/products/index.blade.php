<x-layouts.app variant="store" :title="'Productos'">
    <section class="flex flex-col gap-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-50">Productos</h1>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                    Catálogo estático (scaffolding). Aquí luego entrará el listado real.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <div class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-200">
                    Filtros (placeholder)
                </div>
                <div class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-700 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-200">
                    Ordenar (placeholder)
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <x-product-card
                productName="Producto A"
                price="199.00"
                imageUrl="https://picsum.photos/seed/prod-a/800/600"
                :href="route('products.show', ['slug' => 'producto-a'])"
            />
            <x-product-card
                productName="Producto B"
                price="299.00"
                imageUrl="https://picsum.photos/seed/prod-b/800/600"
                :href="route('products.show', ['slug' => 'producto-b'])"
            />
            <x-product-card
                productName="Producto C"
                price="399.00"
                imageUrl="https://picsum.photos/seed/prod-c/800/600"
                :href="route('products.show', ['slug' => 'producto-c'])"
            />
            <x-product-card
                productName="Producto D"
                price="499.00"
                imageUrl="https://picsum.photos/seed/prod-d/800/600"
                :href="route('products.show', ['slug' => 'producto-d'])"
            />
            <x-product-card
                productName="Producto E"
                price="599.00"
                imageUrl="https://picsum.photos/seed/prod-e/800/600"
                :href="route('products.show', ['slug' => 'producto-e'])"
            />
            <x-product-card
                productName="Producto F"
                price="699.00"
                imageUrl="https://picsum.photos/seed/prod-f/800/600"
                :href="route('products.show', ['slug' => 'producto-f'])"
            />
        </div>
    </section>
</x-layouts.app>
