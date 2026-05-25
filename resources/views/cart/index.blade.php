<x-app-layout>
    <x-slot:title>Tu Carrito - Carpintec</x-slot:title>

    <div class="bg-gray-50/30 min-h-screen pt-12 pb-24">
        
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8" 
             x-data="cartPage({{ $subtotal ?? 0 }}, {{ $items->sum('quantity') ?? 0 }})"
             @cart-error.window="showGlobalError($event.detail)"
             @trigger-delete-modal.window="openModal($event.detail.form, $event.detail.name)">

            <div class="text-center mb-12">
                <h1 class="text-4xl font-serif font-bold text-gray-900 tracking-tight mb-2">Tu Carrito</h1>
                <p class="text-xs text-gray-500 font-sans uppercase tracking-widest font-bold">Revisa tu selección antes de finalizar</p>
            </div>

            <div class="border-t border-b border-gray-200">
                <ul class="divide-y divide-gray-200" role="list">
                    @forelse($items as $item)
                        @php
                            $product = $item->product;
                            $image = $product->mediaUrl($product->getFirstMedia('product_images')) ?: asset('images/product-placeholder.svg');
                            $price = $product->price ?? 0;
                            
                            $maxStock = 99;
                            if ($product->track_inventory && $product->inventory) {
                                $maxStock = $product->inventory->quantity;
                            }
                        @endphp

                        <li class="py-8 flex flex-col sm:flex-row sm:items-center transition-opacity duration-300"
                            x-data="cartItem('{{ $product->slug }}', {{ $item->quantity }}, {{ $price }}, {{ $maxStock }})"
                            x-init="updateLocalSubtotal()"
                            :class="{ 'opacity-50 pointer-events-none': loading }"
                        >
                            <div class="flex-shrink-0 w-24 h-24 bg-gray-100 rounded-xl overflow-hidden relative shadow-sm border border-gray-100/50">
                                <img src="{{ $image }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover object-center" loading="lazy">
                            </div>

                            <div class="mt-6 sm:mt-0 sm:ml-8 flex-1 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                                
                                <div class="flex-1 mb-6 sm:mb-0">
                                    <div class="text-[10px] font-bold text-amber-700 uppercase tracking-widest mb-1">
                                        {{ $product->category->name ?? 'Colección' }}
                                    </div>
                                    <h3 class="text-xl font-serif font-bold text-gray-900 mb-1">
                                        <a href="{{ route('catalog.show', $product->slug) }}"
                                            class="hover:text-amber-800 transition-colors focus:outline-none">
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-xs font-sans text-gray-400 uppercase tracking-widest font-medium">SKU: {{ $product->sku }}</p>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end sm:space-x-8 w-full sm:w-auto">
                                    
                                    <div class="flex items-center border border-gray-200 rounded-lg bg-white shadow-sm">
                                        <button @click="decrement($refs.deleteForm, '{{ addslashes($product->name) }}')" aria-label="Disminuir cantidad"
                                            class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-amber-800 rounded-l-lg focus:ring-2 focus:ring-amber-800 focus:outline-none transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            :disabled="loading">
                                            <span class="text-lg leading-none">−</span>
                                        </button>

                                        <input type="number" 
                                               x-model.number="qty" 
                                               min="0" 
                                               :max="maxStock"
                                               class="w-12 h-10 text-center border-0 border-x border-gray-200 p-0 text-sm font-bold font-sans text-gray-900 focus:ring-0 focus:border-amber-800"
                                               @change="handleManualInput($refs.deleteForm, '{{ addslashes($product->name) }}')"
                                               :disabled="loading">
                                        
                                        <button @click="increment()" 
                                                class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-amber-800 rounded-r-lg focus:ring-2 focus:ring-amber-800 focus:outline-none transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-50"
                                                :disabled="qty >= maxStock || loading">
                                            <span class="text-lg leading-none">+</span>
                                        </button>
                                    </div>

                                    <div class="flex items-center space-x-6">
                                        <div class="text-right">
                                            <p class="text-xl font-sans font-medium text-gray-900" x-text="formatMoney(localSubtotal)"></p>
                                        </div>

                                        <form x-ref="deleteForm" action="{{ route('cart.remove', $product->slug) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" aria-label="Eliminar {{ $product->name }}"
                                                class="text-gray-300 hover:text-rose-700 transition-colors focus:outline-none"
                                                @click="openModal($refs.deleteForm, '{{ addslashes($product->name) }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @empty
                        <div class="text-center py-24 bg-transparent">
                            <svg class="mx-auto h-16 w-16 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                            <h2 class="text-3xl font-serif font-bold text-gray-900 mb-2">Tu carrito está vacío</h2>
                            <p class="text-sm text-gray-500 font-sans mb-8">Descubre nuestras piezas de autor y comienza tu colección.</p>
                            <a href="{{ route('catalog.index') }}"
                                class="inline-flex justify-center items-center rounded-xl bg-amber-900 px-8 py-4 text-sm font-bold tracking-widest uppercase text-white shadow-sm hover:bg-amber-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2">
                                Explorar el catálogo
                            </a>
                        </div>
                    @endforelse
                </ul>
            </div>

            <div x-show="globalErrorMessage" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-2"
                 class="mt-6 bg-rose-50 border border-rose-100 rounded-xl p-4 flex items-center justify-between shadow-sm">
                
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-xs font-bold text-rose-800 uppercase tracking-widest" x-text="globalErrorMessage"></span>
                </div>
                
                <button @click="globalErrorMessage = ''" class="text-rose-400 hover:text-rose-600 transition-colors focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if ($items->isNotEmpty())
                <div class="mt-8 bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="flex items-end justify-between mb-6">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-widest text-gray-500">Subtotal <span class="text-gray-400 font-normal lowercase">(<span x-text="totalItems"></span> piezas)</span></p>
                        </div>
                        <p class="font-serif font-bold text-4xl text-gray-900" x-text="formatMoney(cartSubtotal)"></p>
                    </div>
                    
                    <p class="text-xs text-gray-400 mb-8 border-t border-gray-100 pt-6">
                        Los impuestos y costos de envío se calcularán en el siguiente paso de seguridad.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('catalog.index') }}"
                            class="w-full sm:w-1/2 flex justify-center items-center rounded-xl border border-gray-300 bg-transparent px-8 py-4 text-sm font-bold tracking-widest uppercase text-gray-900 hover:border-gray-900 hover:bg-gray-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 text-center">
                            Seguir comprando
                        </a>
                        <a href="{{ route('checkout.index') }}"
                            class="w-full sm:w-1/2 flex justify-center items-center rounded-xl border border-transparent bg-amber-900 px-8 py-4 text-sm font-bold tracking-widest uppercase text-white shadow-sm hover:bg-amber-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-800 focus:ring-offset-2 text-center">
                            Ir al Checkout seguro
                        </a>
                    </div>
                </div>
            @endif

            <div x-show="isModalOpen" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div x-show="isModalOpen"
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-neutral-900/60 backdrop-blur-sm transition-opacity"></div>

                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div x-show="isModalOpen" @click.away="closeModal()"
                             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                             class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-gray-100">
                            
                            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-50 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                        <h3 class="text-xl font-serif font-bold text-gray-900" id="modal-title">Retirar pieza</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 font-sans">¿Estás seguro que deseas retirar <span class="font-bold text-gray-900" x-text="itemToRemoveName"></span> de tu selección?</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6">
                                <button type="button" @click="confirmDelete()" 
                                        class="inline-flex w-full justify-center rounded-xl bg-gray-900 px-6 py-3 text-sm font-bold uppercase tracking-widest text-white shadow-sm hover:bg-black sm:ml-3 sm:w-auto transition-colors focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                                    Retirar
                                </button>
                                <button type="button" @click="closeModal()" 
                                        class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-6 py-3 text-sm font-bold uppercase tracking-widest text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                                    Conservar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const formatMoney = (amount) => {
            return new Intl.NumberFormat('es-MX', {
                style: 'currency',
                currency: 'MXN'
            }).format(amount);
        };

        function cartPage(initialSubtotal = 0, initialItems = 0) {
            return {
                cartSubtotal: initialSubtotal,
                totalItems: initialItems,
                
                isModalOpen: false,
                itemToRemoveName: '',
                formToSubmit: null,

                globalErrorMessage: '',
                errorTimeout: null,

                showGlobalError(msg) {
                    this.globalErrorMessage = msg;
                    clearTimeout(this.errorTimeout);
                    this.errorTimeout = setTimeout(() => {
                        this.globalErrorMessage = '';
                    }, 4000);
                },

                openModal(formElement, itemName) {
                    this.formToSubmit = formElement;
                    this.itemToRemoveName = itemName;
                    this.isModalOpen = true;
                    document.body.classList.add('overflow-hidden');
                },

                closeModal() {
                    this.isModalOpen = false;
                    document.body.classList.remove('overflow-hidden');
                    setTimeout(() => {
                        this.formToSubmit = null;
                        this.itemToRemoveName = '';
                    }, 300);
                },

                confirmDelete() {
                    if (this.formToSubmit) {
                        this.formToSubmit.submit();
                    }
                }
            }
        }

        function cartItem(slug, initialQty, unitPrice, maxStock) {
            return {
                qty: initialQty,
                previousQty: initialQty,
                unitPrice: unitPrice,
                maxStock: maxStock,
                localSubtotal: initialQty * unitPrice,
                loading: false,
                timeout: null,

                updateLocalSubtotal() {
                    this.localSubtotal = this.qty * this.unitPrice;
                },

                handleManualInput(deleteForm, itemName) {
                    // Si el usuario introduce 0 o borra el campo, disparamos el modal
                    if (this.qty === '' || this.qty <= 0) {
                        this.qty = this.previousQty; // Restauramos el valor previo por seguridad
                        this.updateLocalSubtotal();
                        this.$dispatch('trigger-delete-modal', { form: deleteForm, name: itemName });
                        return;
                    }

                    if (this.qty > this.maxStock) {
                        this.qty = this.maxStock;
                        this.$dispatch('cart-error', `Límite alcanzado: Solo contamos con ${this.maxStock} unidades en inventario.`);
                    }
                    this.updateLocalSubtotal();
                    this.scheduleUpdate();
                },

                scheduleUpdate() {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        this.updateCart();
                    }, 400);
                },

                async updateCart() {
                    if (this.qty === this.previousQty) return;
                    this.loading = true;

                    try {
                        const resp = await fetch(`/carrito/${slug}`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ quantity: this.qty })
                        });

                        if (!resp.ok) {
                            const errorData = await resp.json().catch(() => ({}));
                            throw new Error(errorData.message || 'Error al actualizar.');
                        }

                        const data = await resp.json();
                        this.previousQty = this.qty;

                        if (data.subtotal !== undefined) {
                            this.$data.cartSubtotal = parseFloat(data.subtotal);
                        }
                        if (data.totalItems !== undefined) {
                            this.$data.totalItems = parseInt(data.totalItems);
                        }
                    } catch (e) {
                        this.$dispatch('cart-error', e.message);
                        this.qty = this.previousQty;
                        this.updateLocalSubtotal();
                    } finally {
                        this.loading = false;
                    }
                },

                increment() {
                    if (this.qty < this.maxStock) {
                        this.qty++;
                        this.updateLocalSubtotal();
                        this.scheduleUpdate();
                    } else {
                        this.$dispatch('cart-error', `Límite alcanzado: Solo contamos con ${this.maxStock} unidades en inventario.`);
                    }
                },

                decrement(deleteForm, itemName) {
                    // Si está en 1 y pulsa menos, lanzamos el modal en lugar de bloquear el botón
                    if (this.qty === 1) {
                        this.$dispatch('trigger-delete-modal', { form: deleteForm, name: itemName });
                        return;
                    }

                    if (this.qty > 1) {
                        this.qty--;
                        this.updateLocalSubtotal();
                        this.scheduleUpdate();
                    }
                }
            }
        }
    </script>
</x-app-layout>