<x-app-layout>
    <x-slot:title>Solicitar Cotización | Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen py-12 pt-32 font-sans">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <span class="text-amber-800 font-semibold tracking-[0.2em] uppercase text-xs sm:text-sm mb-4 block">Estudio de Diseño</span>
                <h1 class="font-serif text-4xl sm:text-5xl text-gray-900 tracking-tight mb-4">Proyecto a Medida</h1>
                <p class="text-gray-600">Cuéntanos tu idea. Nuestros artesanos evaluarán la viabilidad, materiales y costos para hacerla realidad.</p>
            </div>

            <form action="{{ route('quotations.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 sm:p-12 rounded-2xl shadow-sm border border-gray-100 space-y-8">
                @csrf
                
                {{-- Si viene de un producto del catálogo, lo indicamos de forma elegante --}}
                @if($product) 
                    <input type="hidden" name="product_id" value="{{ $product->id }}"> 
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center border border-gray-200">
                            <svg class="w-6 h-6 text-amber-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a2.25 2.25 0 012.25-2.25h1.5a2.25 2.25 0 012.25 2.25v7.5m-6.75-6h2.25m-9-2.25h4.5M4.5 18.75h15"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Inspiración Base</p>
                            <p class="font-serif text-gray-900">{{ $product->name }}</p>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Título de tu proyecto</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject', $subject ?? '') }}" required 
                           placeholder="Ej. Mesa de comedor monumental en Nogal"
                           class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors">
                    @error('subject') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción detallada</label>
                    <textarea name="description" id="description" rows="6" required
                              class="w-full rounded-xl border-gray-200 py-3.5 focus:border-amber-800 focus:ring-amber-800 shadow-sm transition-colors resize-none"
                              placeholder="Indícanos las medidas deseadas (largo x ancho x alto), tipo de madera preferida, acabados y cualquier detalle funcional...">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Zona de adjuntos con Alpine (Estilo Premium) --}}
                <div x-data="{ files: [] }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bocetos, planos o referencias (Opcional)</label>
                    
                    <div class="relative flex justify-center px-6 pt-10 pb-12 border-2 border-gray-200 border-dashed rounded-xl hover:bg-gray-50 hover:border-amber-800/50 transition-colors duration-300 group">
                        <div class="space-y-2 text-center">
                            {{-- Icono Outline 1.5px --}}
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-amber-800 transition-colors" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="attachments" class="relative cursor-pointer rounded-md font-medium text-amber-900 hover:text-amber-700 focus-within:outline-none">
                                    <span>Sube tus archivos</span>
                                    <input id="attachments" name="attachments[]" type="file" multiple class="sr-only" accept="image/*,.pdf"
                                           @change="files = Array.from($event.target.files)">
                                </label>
                                <p class="pl-1 text-gray-500">o arrástralos aquí</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG o PDF. Máximo 5MB por archivo.</p>
                        </div>
                    </div>

                    <template x-if="files.length > 0">
                        <ul class="mt-4 space-y-2">
                            <template x-for="(file, index) in files" :key="index">
                                <li class="flex items-center justify-between p-3 bg-amber-50/50 rounded-lg border border-amber-100">
                                    <div class="flex items-center overflow-hidden">
                                        <svg class="h-5 w-5 text-amber-800 mr-3 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                        <span class="text-sm font-medium text-gray-900 truncate" x-text="file.name"></span>
                                    </div>
                                    <span class="text-xs text-amber-800 font-medium shrink-0" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></span>
                                </li>
                            </template>
                        </ul>
                    </template>

                    @error('attachments.*') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center bg-amber-900 text-white hover:bg-amber-800 uppercase tracking-widest text-sm font-bold rounded-xl px-10 py-4 transition-colors duration-200 shadow-sm">
                        Enviar a Diseño
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>