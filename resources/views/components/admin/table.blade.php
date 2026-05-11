@props(['headers' => []])

@php
    $currentSort = request()->query('sort');
    $currentDirection = request()->query('direction', 'asc');
@endphp

<div class="overflow-hidden bg-white rounded-2xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/80">
                <tr>
                    @foreach($headers as $key => $value)
                        @php
                            // Lógica de compatibilidad:
                            // Si mandas ['Nombre'], $key es numérico (0) y $value es 'Nombre' (No ordenable).
                            // Si mandas ['Nombre' => 'name'], $key es 'Nombre' y $value es 'name' (Ordenable).
                            $isSortable = !is_numeric($key) && !empty($value);
                            $label = is_numeric($key) ? $value : $key;
                            $column = $isSortable ? $value : null;
                        @endphp

                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider font-sans {{ $isSortable ? 'text-gray-900' : 'text-gray-500' }}">
                            @if($isSortable)
                                @php
                                    // Alternar dirección si ya estamos ordenando por esta columna
                                    $isAsc = $currentSort === $column && $currentDirection === 'asc';
                                    $nextDirection = $isAsc ? 'desc' : 'asc';
                                    $sortUrl = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $nextDirection]);
                                @endphp
                                <a href="{{ $sortUrl }}" class="group inline-flex items-center gap-1.5 hover:text-amber-900 transition-colors">
                                    {{ $label }}
                                    <span class="relative flex items-center">
                                        @if($currentSort === $column)
                                            @if($currentDirection === 'asc')
                                                <svg class="w-4 h-4 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>
                                            @else
                                                <svg class="w-4 h-4 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                            @endif
                                        @else
                                            {{-- Icono neutral (arriba/abajo) oculto por defecto, aparece en hover --}}
                                            <svg class="w-4 h-4 text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                        @endif
                                    </span>
                                </a>
                            @else
                                {{ $label }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-50 font-sans">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>