@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="flex items-center px-4 py-3 text-sm font-medium transition-colors
          {{ $active ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
    {{ $slot }}
</a>