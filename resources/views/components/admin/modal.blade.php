@props(['id' => 'default-modal', 'title' => 'Confirmar'])

<div id="{{ $id }}" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-gray-900" id="modal-title">{{ $title }}</h3>
            <div class="mt-2">
                {{ $slot }}
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-2">
            {{ $footer ?? '' }}
        </div>
    </div>
</div>