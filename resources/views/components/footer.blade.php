<footer class="border-t border-zinc-200 bg-white dark:border-zinc-800 dark:bg-zinc-950">
    <div class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-2">
                <div class="grid size-9 place-items-center rounded-lg bg-zinc-900 text-sm font-bold text-white dark:bg-zinc-50 dark:text-zinc-900">
                    C
                </div>
                <div class="leading-tight">
                    <div class="font-semibold text-zinc-900 dark:text-zinc-50">Carpintec Store</div>
                    <div class="text-sm text-zinc-600 dark:text-zinc-300">UI scaffolding (sin checkout)</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 text-sm text-zinc-600 dark:text-zinc-300">
                <a class="rounded-md px-2 py-1 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-900 dark:hover:text-zinc-50" href="{{ route('store.home') }}">Inicio</a>
                <a class="rounded-md px-2 py-1 transition hover:bg-zinc-100 hover:text-zinc-900 dark:hover:bg-zinc-900 dark:hover:text-zinc-50" href="{{ route('products.index') }}">Productos</a>
                <span class="text-zinc-300 dark:text-zinc-700">|</span>
                <span>&copy; {{ now()->year }}</span>
            </div>
        </div>
    </div>
</footer>
