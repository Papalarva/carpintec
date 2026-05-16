<x-app-layout>
    <x-slot:title>Solicitar Cotización | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-10 font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-10 mt-6">
                <span class="text-amber-800 font-bold tracking-[0.2em] uppercase text-xs mb-3 block">Estudio de Diseño</span>
                <h1 class="font-serif text-4xl sm:text-5xl text-gray-900 tracking-tight mb-4">Proyecto a Medida</h1>
                <p class="text-gray-600 max-w-xl mx-auto">Cuéntanos tu idea. Nuestros artesanos evaluarán la viabilidad, materiales y costos para hacerla realidad.</p>
            </div>

            {{-- CORRECCIÓN: El estado isSubmitting y el evento @submit ahora controlan el formulario completo --}}
            <form action="{{ route('quotations.store') }}" method="POST" enctype="multipart/form-data" 
                  class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-gray-100 space-y-8"
                  x-data="{ isSubmitting: false }" 
                  @submit="isSubmitting = true">
                @csrf
                
                @if($product) 
                    <input type="hidden" name="product_id" value="{{ $product->id }}"> 
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center gap-4 mb-2">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200 shadow-sm shrink-0">
                            <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a2.25 2.25 0 012.25-2.25h1.5a2.25 2.25 0 012.25 2.25v7.5m-6.75-6h2.25m-9-2.25h4.5M4.5 18.75h15"></path></svg>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-0.5">Inspiración Base</p>
                            <p class="font-serif text-gray-900 truncate">{{ $product->name }}</p>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Título de tu proyecto</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', $subject ?? '') }}" required maxlength="255"
                           placeholder="Ej. Mesa de comedor monumental en Nogal"
                           class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                    @error('subject') <p class="mt-2 text-sm text-red-600 font-sans">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción detallada</label>
                    <textarea name="description" id="description" rows="5" required maxlength="5000"
                              class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors resize-none"
                              placeholder="Indícanos las medidas deseadas (largo x ancho x alto), tipo de madera preferida, acabados y cualquier detalle funcional...">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600 font-sans">{{ $message }}</p> @enderror
                </div>

                <div x-data="fileUploadComponent()">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bocetos, planos o referencias (Opcional)</label>
                    
                    <div @dragover.prevent="isDropping = true" 
                         @dragleave.prevent="isDropping = false" 
                         @drop.prevent="handleDrop($event)"
                         :class="{'bg-amber-50 border-amber-400': isDropping, 'border-gray-200 hover:bg-gray-50': !isDropping}"
                         class="relative flex justify-center px-6 pt-10 pb-12 border-2 border-dashed rounded-2xl transition-colors duration-300 group">
                        
                        <div class="space-y-2 text-center pointer-events-none">
                            <svg :class="{'text-amber-600': isDropping, 'text-gray-400 group-hover:text-amber-800': !isDropping}" class="mx-auto h-12 w-12 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center font-sans pointer-events-auto">
                                <label for="attachments" class="relative cursor-pointer rounded-md font-medium text-amber-900 hover:text-amber-700 focus-within:outline-none">
                                    <span>Selecciona archivos</span>
                                    <input id="attachments" name="attachments[]" type="file" multiple class="sr-only" accept=".jpg,.jpeg,.png,.pdf"
                                           x-ref="fileInput" @change="handleFiles($event.target.files)">
                                </label>
                                <p class="pl-1 text-gray-500">o arrástralos aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">JPG, PNG o PDF. Máx 5MB (Hasta 5 archivos).</p>
                        </div>
                    </div>

                    <template x-if="files.length > 0">
                        <ul class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="(file, index) in files" :key="index">
                                <li class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                                    <div class="flex items-center overflow-hidden pr-3">
                                        <svg class="h-6 w-6 text-amber-800 mr-3 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        <div class="truncate">
                                            <p class="text-sm font-medium text-gray-900 truncate" x-text="file.name"></p>
                                            <p class="text-xs text-gray-500" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeFile(index)" class="shrink-0 p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors focus:outline-none" title="Quitar archivo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path></svg>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </template>

                    @error('attachments.*') <p class="mt-2 text-sm text-red-600 font-sans">{{ $message }}</p> @enderror
                    @error('attachments') <p class="mt-2 text-sm text-red-600 font-sans">{{ $message }}</p> @enderror
                </div>

                {{-- Los botones reaccionan al isSubmitting general del formulario --}}
                <div class="pt-8 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-4">
                    <a href="{{ route('quotations.index') }}" class="inline-flex justify-center items-center px-8 py-3.5 text-sm font-bold text-gray-600 bg-transparent hover:bg-gray-50 rounded-xl transition-colors focus:outline-none uppercase tracking-widest" :class="{ 'opacity-50 pointer-events-none': isSubmitting }">
                        Cancelar
                    </a>
                    <button type="submit" :disabled="isSubmitting" class="w-full sm:w-auto inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-3.5 transition-colors duration-200 shadow-sm disabled:opacity-75">
                        <span x-show="!isSubmitting">Enviar a Diseño</span>
                        <span x-show="isSubmitting" x-cloak class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Enviando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function fileUploadComponent() {
            return {
                isDropping: false,
                files: [],
                
                handleDrop(event) {
                    this.isDropping = false;
                    this.handleFiles(event.dataTransfer.files);
                },
                
                handleFiles(newFiles) {
                    const validFiles = Array.from(newFiles).slice(0, 5); // Limita a 5
                    this.files = [...this.files, ...validFiles].slice(0, 5);
                    this.syncInput();
                },
                
                removeFile(index) {
                    this.files.splice(index, 1);
                    this.syncInput();
                },
                
                syncInput() {
                    const dt = new DataTransfer();
                    this.files.forEach(file => dt.items.add(file));
                    this.$refs.fileInput.files = dt.files;
                }
            }
        }
    </script>
</x-app-layout>