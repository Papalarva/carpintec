@props(['id' => 'default-modal', 'title' => 'Confirmar'])

<div x-data="{ 
        show: false,
        init() {
            this.$watch('show', value => {
                if (value) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });
        }
     }"
     x-show="show"
     @open-modal.window="if ($event.detail === '{{ $id }}') show = true"
     @close-modal.window="show = false"
     @keydown.escape.window="show = false"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0 font-sans"
     role="dialog" aria-modal="true">
    
    {{-- Fondo con blur --}}
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100 backdrop-blur-sm"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 backdrop-blur-sm"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" 
         @click="show = false"></div>

    {{-- Contenedor del Modal --}}
    <div x-show="show" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative bg-white rounded-2xl shadow-premium transform transition-all sm:max-w-md sm:w-full border border-gray-100 overflow-hidden z-10">
        
        <div class="px-8 py-8">
            <h3 class="text-2xl font-semibold text-gray-900 font-serif mb-4">{{ $title }}</h3>
            <div class="text-sm text-gray-500 font-sans leading-relaxed">
                {{ $slot }}
            </div>
        </div>
        
        {{-- Footer limpio --}}
        <div class="px-8 py-5 border-t border-gray-100 flex justify-end">
            {{ $footer ?? '' }}
        </div>
    </div>
</div>