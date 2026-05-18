@props([
    'label',
    'value',
    'bgColorClass' => 'bg-amber-50',
    'textColorClass' => 'text-amber-900',
])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 transition-all duration-300 hover:shadow-md hover:border-gray-200">
    <div class="flex items-center">
        <div class="flex-shrink-0 rounded-xl p-3 border border-white/50 shadow-sm {{ $bgColorClass }}">
            <svg class="h-6 w-6 {{ $textColorClass }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"></path>
            </svg>
        </div>
        <div class="ml-5">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest font-sans truncate">{{ $label }}</p>
            <p class="text-2xl font-bold text-gray-900 font-serif mt-1">{{ $value }}</p>
        </div>
    </div>
</div>