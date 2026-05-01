<x-app-layout>
    <x-slot:title>Solicitar cotización</x-slot:title>

    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Solicitar cotización a medida</h1>

        @if(isset($product))
            <p class="text-gray-600 mb-6">Producto de referencia: <strong>{{ $product->name }}</strong></p>
        @endif

        <form action="{{ route('quotations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow space-y-6">
            @csrf
            @if($product) <input type="hidden" name="product_id" value="{{ $product->id }}"> @endif
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Asunto</label>
               <input type="text" name="subject" value="{{ old('subject', $subject ?? '') }}" required 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción detallada</label>
                <textarea name="description" id="description" rows="6" required
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Describe el mueble que deseas: dimensiones, materiales, acabados, etc.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo de Adjuntos con Reactividad Alpine.js -->
            <div x-data="{ files: [] }">
                <label class="block text-sm font-medium text-gray-700">Adjuntos (imágenes, bocetos, PDF)</label>
                
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:bg-gray-50 transition-colors duration-200">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="attachments" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                <span>Subir archivos</span>
                                <!-- El evento @change captura los archivos y los guarda en la variable 'files' de Alpine -->
                                <input id="attachments" name="attachments[]" type="file" multiple class="sr-only" accept="image/*,.pdf"
                                       @change="files = Array.from($event.target.files)">
                            </label>
                            <p class="pl-1">o haz clic aquí</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, WEBP, PDF hasta 5 MB cada uno</p>
                    </div>
                </div>

                <!-- Indicador visual dinámico de archivos seleccionados -->
                <template x-if="files.length > 0">
                    <ul class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <template x-for="(file, index) in files" :key="index">
                            <li class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg border border-indigo-100 shadow-sm">
                                <div class="flex items-center overflow-hidden">
                                    <svg class="h-5 w-5 text-indigo-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    <span class="text-sm font-medium text-indigo-900 truncate" x-text="file.name"></span>
                                </div>
                                <span class="text-xs text-indigo-500 ml-3 flex-shrink-0" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></span>
                            </li>
                        </template>
                    </ul>
                </template>

                @error('attachments.*')
                    <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    Enviar solicitud
                </button>
            </div>
        </form>
    </div>
</x-app-layout>