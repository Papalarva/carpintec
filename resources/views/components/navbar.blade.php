<header class="border-b border-zinc-200 bg-white/80 backdrop-blur dark:border-zinc-800 dark:bg-zinc-950/70">
    <div class="mx-auto flex w-full max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a
            href="{{ route('store.home') }}"
            class="inline-flex items-center gap-2 font-semibold tracking-tight text-zinc-900 dark:text-zinc-50"
        >
            <span class="grid size-8 place-items-center rounded-lg bg-zinc-900 text-sm font-bold text-white dark:bg-zinc-50 dark:text-zinc-900">
                C
            </span>
            <span>Carpintec Store</span>
        </a>

        <nav class="flex items-center gap-1">
            <a
                href="{{ route('store.home') }}"
                @class([
                    'rounded-md px-3 py-2 text-sm font-medium transition',
                    request()->routeIs('store.home')
                        ? 'bg-zinc-900 text-white dark:bg-zinc-50 dark:text-zinc-900'
                        : 'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-900 dark:hover:text-zinc-50',
                ])
            >
                Inicio
            </a>
            <a
                href="{{ route('products.index') }}"
                @class([
                    'rounded-md px-3 py-2 text-sm font-medium transition',
                    request()->routeIs('products.*')
                        ? 'bg-zinc-900 text-white dark:bg-zinc-50 dark:text-zinc-900'
                        : 'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-200 dark:hover:bg-zinc-900 dark:hover:text-zinc-50',
                ])
            >
                Productos
            </a>
        </nav>

        <div class="flex items-center gap-2">
            <a
                href="{{ route('products.index') }}"
                class="rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm font-medium text-zinc-900 shadow-sm transition hover:bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-50 dark:hover:bg-zinc-900"
            >
                Ver catálogo
            </a>
        </div>
    </div>
</header>
