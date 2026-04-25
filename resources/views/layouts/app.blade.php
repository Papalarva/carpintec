@props([
    'title' => null,
    'variant' => 'sidebar',
])

@if ($variant === 'store')
    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            @include('partials.head', ['title' => $title])
            {{ $head ?? '' }}
        </head>
        <body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-950 dark:text-zinc-50">
            <x-navbar />

            <main class="mx-auto w-full max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                {{ $slot }}
            </main>

            <x-footer />

            {{ $scripts ?? '' }}
            @stack('scripts')
        </body>
    </html>
@else
    <x-layouts::app.sidebar :title="$title">
        <flux:main>
            {{ $slot }}
        </flux:main>

        {{ $scripts ?? '' }}
        @stack('scripts')
    </x-layouts::app.sidebar>
@endif
