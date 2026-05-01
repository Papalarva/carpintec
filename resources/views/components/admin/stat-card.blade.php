@props([
    'label',
    'value',
    'bgColorClass' => 'bg-blue-100',
    'textColorClass' => 'text-blue-600',
])

<div class="bg-white rounded-lg shadow p-5">
    <div class="flex items-center">
        <div class="flex-shrink-0 rounded-full p-3 {{ $bgColorClass }}">
            <svg class="h-6 w-6 {{ $textColorClass }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-500 truncate">{{ $label }}</p>
            <p class="text-lg font-semibold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>