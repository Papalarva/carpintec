@csrf

{{-- SECCIÓN 1: INFORMACIÓN BÁSICA --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Información Básica</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-7">

        {{-- Fila 1 --}}
        <div>
            <label for="sku"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">SKU (Código
                Interno) <span class="text-rose-500">*</span></label>
            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku ?? '') }}" required
                maxlength="50"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans font-mono uppercase"
                placeholder="Ej. MES-001">
            @error('sku')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Categoría
                <span class="text-rose-500">*</span></label>
            <select name="category_id" id="category_id" required
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans cursor-pointer">
                <option value="">Selecciona una categoría</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Nombre de la
                Pieza <span class="text-rose-500">*</span></label>
            <input type="text" name="name" id="name" x-model="productName" @input="generateSlug()" required
                maxlength="255"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans"
                placeholder="Ej. Mesa Comedor Parota">
            @error('name')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fila 2 --}}
        <div>
            <label for="slug"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Enlace (Slug)
                <span class="text-rose-500">*</span></label>
            <input type="text" name="slug" id="slug" x-model="slug" required maxlength="255"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans font-mono text-amber-900">
            @error('slug')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="price"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Precio
                Público (MXN) <span class="text-rose-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-500 font-medium">$</span>
                </div>
                <input type="number" step="1" min="0" name="price" id="price" x-model="price"
                    required
                    class="block w-full scroll-mt-32 pl-8 rounded-xl border-gray-200 bg-gray-50/50 text-base font-bold text-gray-900 py-3 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans"
                    placeholder="0">
            </div>
            @error('price')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="cost"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Costo
                Producción (MXN) <span class="text-rose-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span class="text-gray-400 font-medium">$</span>
                </div>
                <input type="number" step="1" min="0" name="cost" id="cost" x-model="cost"
                    required
                    class="block w-full scroll-mt-32 pl-8 rounded-xl border-gray-200 bg-gray-50/50 text-base font-medium text-gray-600 py-3 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans"
                    placeholder="0">
            </div>
            @error('cost')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

{{-- SECCIÓN 2: ESPECIFICACIONES FÍSICAS --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Especificaciones Físicas
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-7 mb-7">
        <div>
            <label for="materials"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Materiales</label>
            <input type="text" name="materials" id="materials"
                value="{{ old('materials', $product->materials ?? '') }}" maxlength="255"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans"
                placeholder="Ej. Roble, Acero">
            @error('materials')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="dimensions"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Dimensiones</label>
            <input type="text" name="dimensions" id="dimensions"
                value="{{ old('dimensions', $product->dimensions ?? '') }}" maxlength="255"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans"
                placeholder="Ej. 120x60x75 cm">
            @error('dimensions')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="weight_kg"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Peso Estimado
                (kg)</label>
            <input type="number" step="0.01" min="0" name="weight_kg" id="weight_kg"
                value="{{ old('weight_kg', $product->weight_kg ?? '') }}"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans">
            @error('weight_kg')
                <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mb-7">
        <label for="short_description"
            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Descripción
            Corta (Catálogo)</label>
        <textarea name="short_description" id="short_description" rows="2" maxlength="500"
            class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans resize-none"
            placeholder="Un resumen atractivo para la tarjeta del producto...">{{ old('short_description', $product->short_description ?? '') }}</textarea>
        @error('short_description')
            <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="long_description"
            class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Historia y
            Detalles Técnicos</label>
        <textarea name="long_description" id="long_description" rows="5" maxlength="5000"
            class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans resize-none"
            placeholder="Escribe aquí los detalles completos, cuidados de la madera, inspiración del diseño...">{{ old('long_description', $product->long_description ?? '') }}</textarea>
        @error('long_description')
            <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
        @enderror
    </div>
</div>

{{-- SECCIÓN 3: SEO Y METADATOS (FASE 4) --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">SEO y Buscadores</h3>
    <div class="grid grid-cols-1 gap-7">
        <div>
            <label for="meta_title"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Meta Título
                (Max 60 chars)</label>
            <input type="text" name="meta_title" id="meta_title"
                value="{{ old('meta_title', $product->meta_title ?? '') }}" maxlength="60"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans"
                placeholder="Ej. Mesa de Comedor en Parota | Carpintec">
        </div>
        <div>
            <label for="meta_description"
                class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 font-sans">Meta
                Descripción (Max 160 chars)</label>
            <textarea name="meta_description" id="meta_description" rows="2" maxlength="160"
                class="block w-full scroll-mt-32 rounded-xl border-gray-200 bg-gray-50/50 text-sm py-3.5 focus:bg-white focus:ring-amber-900 focus:border-amber-900 transition-colors shadow-sm font-sans resize-none"
                placeholder="Breve descripción diseñada para convencer en Google..."></textarea>
        </div>
    </div>
</div>

{{-- SECCIÓN 4: CONFIGURACIÓN DE VENTA Y GALERÍA --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <h3 class="text-xl font-bold text-gray-900 mb-6 font-serif border-b border-gray-100 pb-4">Configuración y Galería
    </h3>

    <div class="flex flex-wrap gap-8 mb-8 pb-8 border-b border-gray-50">
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="track_inventory" id="track_inventory" value="1"
                {{ old('track_inventory', $product->track_inventory ?? false) ? 'checked' : '' }}
                class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-gray-50 transition-colors cursor-pointer shadow-sm">
            <span
                class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Rastrear
                Inventario Bodega</span>
        </label>
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="is_customizable" id="is_customizable" value="1"
                {{ old('is_customizable', $product->is_customizable ?? false) ? 'checked' : '' }}
                class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-gray-50 transition-colors cursor-pointer shadow-sm">
            <span
                class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Permitir
                Medidas Especiales (Cotizable)</span>
        </label>
        <label class="flex items-center cursor-pointer group">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                class="w-5 h-5 rounded border-gray-300 text-amber-900 focus:ring-amber-900 bg-gray-50 transition-colors cursor-pointer shadow-sm">
            <span
                class="ml-3 text-sm font-bold text-gray-700 group-hover:text-gray-900 font-sans transition-colors">Visible
                en Tienda Online</span>
        </label>
    </div>

    {{-- Área de subida de imágenes --}}
    <div class="mb-8">
        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 font-sans">Fotografías
            del Mueble</label>

        <div
            class="mt-1 flex justify-center px-6 pt-8 pb-8 border-2 border-gray-200 border-dashed rounded-2xl bg-gray-50/50 hover:bg-amber-50/30 transition-colors relative group cursor-pointer">
            <div class="space-y-2 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300 group-hover:text-amber-800 transition-colors"
                    stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true" stroke-width="1.5">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600 justify-center font-sans">
                    <label for="images"
                        class="relative cursor-pointer bg-transparent rounded-md font-bold text-amber-900 hover:text-amber-700 focus-within:outline-none transition-colors">
                        <span>Sube archivos</span>
                        <input id="images" name="images[]" type="file" multiple
                            accept="image/jpeg, image/png, image/webp" class="sr-only" @change="handleFiles($event)">
                    </label>
                    <p class="pl-1">o arrastra y suelta aquí</p>
                </div>
                <p class="text-xs text-gray-400 font-sans tracking-wide">PNG, JPG, WEBP hasta 5MB</p>
            </div>
        </div>

        {{-- Alerta de Archivo Pesado --}}
        <div x-show="fileError" x-cloak
            class="mt-4 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
            <p class="text-[10px] font-bold text-rose-800 uppercase tracking-widest font-sans" x-text="fileError"></p>
        </div>

        {{-- Grid de Previsualización Dinámica --}}
        <div x-show="imagesPreviews.length > 0" class="mt-6" x-cloak>
            <p
                class="text-[10px] font-bold text-amber-900 uppercase tracking-widest mb-3 font-sans border-b border-amber-100 pb-2">
                Nuevas imágenes listas para subir</p>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <template x-for="(image, index) in imagesPreviews" :key="index">
                    <div class="relative rounded-xl overflow-hidden shadow-sm border border-gray-100 bg-white p-1">
                        <img :src="image.url" :alt="image.name" class="w-full h-24 object-cover rounded-lg">
                        <p class="mt-1 text-[10px] text-gray-500 font-bold truncate px-1 font-sans"
                            x-text="image.name"></p>
                    </div>
                </template>
            </div>
        </div>

        @error('images.*')
            <p class="text-rose-600 text-[11px] font-bold tracking-wide mt-2 font-sans">{{ $message }}</p>
        @enderror
    </div>

    {{-- Imágenes Actuales con Efecto Fantasma (Fase 3) --}}
    @if (isset($product) && $product->getMedia('product_images')->count())
        <div class="mt-8 pt-6 border-t border-gray-50">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-4 font-sans">Imágenes
                Actuales <span class="text-gray-400 normal-case tracking-normal font-medium">(Selecciona para
                    eliminar)</span></label>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($product->getMedia('product_images') as $img)
                    <div x-data="{ isDeleted: false }"
                        class="relative group rounded-xl overflow-hidden shadow-sm border transition-all duration-300"
                        :class="isDeleted ? 'border-rose-200 bg-rose-50' : 'border-gray-100 bg-white'">

                        <img src="{{ $img->getUrl() }}" class="w-full h-32 object-cover transition-all duration-300"
                            :class="isDeleted ? 'grayscale opacity-40' : 'group-hover:scale-105'">

                        <div class="absolute inset-0 bg-rose-900/20 opacity-0 transition-opacity"
                            :class="isDeleted ? 'opacity-0' : 'group-hover:opacity-100'"></div>

                        <label
                            class="absolute top-2 right-2 px-2.5 py-1.5 rounded-lg shadow-sm text-[10px] uppercase tracking-widest font-bold cursor-pointer border flex items-center gap-2 transition-colors"
                            :class="isDeleted ? 'bg-rose-600 text-white border-rose-600' :
                                'bg-white/95 backdrop-blur-sm text-rose-700 border-rose-100 hover:bg-rose-50'">
                            <input type="checkbox" name="delete_images[]" value="{{ $img->id }}"
                                x-model="isDeleted" class="hidden">
                            <span x-text="isDeleted ? 'Marcada para borrar' : 'Eliminar'"></span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- MODAL DE SEGURIDAD PARA PRECIO O COSTO EN CERO --}}
<x-admin.modal id="zero-price-modal" title="¿Valores en Cero?">
    <div class="flex flex-col items-center text-center pb-2">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-rose-50 mb-6 border border-rose-100">
            <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        </div>
        
        <p class="text-sm text-gray-600 font-sans leading-relaxed">
            Estás a punto de guardar esta pieza con un <strong>Precio o Costo de $0 MXN</strong>. Esto significa que será gratuita o está destinada a cotización.<br><br>¿Deseas continuar?
        </p>
    </div>

    <x-slot name="footer">
        <div class="flex flex-col sm:flex-row gap-4 justify-end w-full">
            <button type="button" @click="$dispatch('close-modal')" class="w-full sm:w-auto px-8 py-3.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold uppercase tracking-widest rounded-xl transition-colors focus:outline-none">
                Cancelar
            </button>
            <button type="button" @click="confirmZeroPrice()" class="w-full sm:w-auto px-8 py-3.5 bg-amber-900 hover:bg-amber-800 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-colors shadow-sm focus:outline-none">
                Sí, Guardar Pieza
            </button>
        </div>
    </x-slot>
</x-admin.modal>

<script>
    function productForm() {
        return {
            productName: '{{ old('name', $product->name ?? '') }}',
            slug: '{{ old('slug', $product->slug ?? '') }}',
            price: {{ old('price', $product->price ?? '') ?: 0 }},
            cost: {{ old('cost', $product->cost ?? '') ?: 0 }},
            isSubmitting: false,
            isDragging: false,
            imagesPreviews: [],
            fileError: '',

            init() {
                this.$nextTick(() => {
                    this.setupValidators(this.$refs.form || document.querySelector('form'));
                });
            },

            setupValidators(form) {
                if (!form) return;
                form.querySelectorAll('input, select, textarea').forEach(el => {
                    el.oninvalid = e => {
                        e.target.setCustomValidity('');
                        if (!e.target.validity.valid) {
                            if (e.target.validity.valueMissing) e.target.setCustomValidity('Este campo es obligatorio.');
                            else if (e.target.validity.typeMismatch) e.target.setCustomValidity('Formato inválido.');
                            else if (e.target.validity.rangeUnderflow) e.target.setCustomValidity('El valor debe ser mayor o igual a ' + e.target.min + '.');
                            else if (e.target.validity.tooLong) e.target.setCustomValidity('Has superado el límite de caracteres permitido.');
                            else e.target.setCustomValidity('Valor inválido.');
                        }
                    };
                    el.oninput = e => {
                        e.target.setCustomValidity('');
                    };
                });
            },

            generateSlug() {
                this.slug = this.productName
                    .toLowerCase()
                    .normalize("NFD").replace(/[\u0300-\u036f]/g, "")
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)/g, '');
            },

            processFiles(files) {
                this.fileError = '';
                const maxSize = 5 * 1024 * 1024;

                for(let i = 0; i < files.length; i++) {
                    if(files[i].size > maxSize) {
                        this.fileError = 'Uno o más archivos superan los 5MB y fueron descartados.';
                        continue;
                    }
                    this.imagesPreviews.push({
                        name: files[i].name,
                        url: URL.createObjectURL(files[i])
                    });
                }
            },
            
            attemptSubmit(event) {
                if(this.$refs.form.checkValidity()) {
                    let parsedPrice = parseFloat(this.price);
                    let parsedCost = parseFloat(this.cost);
                    
                    if (parsedPrice === 0 || isNaN(parsedPrice) || parsedCost === 0 || isNaN(parsedCost)) {
                        event.preventDefault();
                        // Disparamos el evento para abrir tu componente modal
                        this.$dispatch('open-modal', 'zero-price-modal');
                    } else {
                        this.isSubmitting = true;
                    }
                } 
            },
            
            confirmZeroPrice() {
                // Disparamos el evento para cerrar tu componente modal
                this.$dispatch('close-modal');
                this.isSubmitting = true;
                this.$refs.form.submit();
            }
        }
    }
</script>
