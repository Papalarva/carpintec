@props(['id' => 'default-modal', 'title' => 'Confirmar'])

<div id="{{ $id }}" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-xl shadow-2xl transform transition-all sm:max-w-md sm:w-full border border-gray-100 overflow-hidden">
        <div class="px-6 py-5">
            <h3 class="text-xl font-semibold text-gray-900 font-playfair" id="modal-title">{{ $title }}</h3>
            <div class="mt-3 text-sm text-gray-600 font-inter">
                {{ $slot }}
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            {{ $footer ?? '' }}
        </div>
    </div>
</div>