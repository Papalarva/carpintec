@props(['config', 'currencyY' => false])

@php
    // Generamos un ID único para el canvas (ej. chart_aB3dEfg) para evitar choques
    $chartId = 'chart_' . Str::random(8);
@endphp

<div class="relative h-72 w-full">
    <canvas id="{{ $chartId }}"></canvas>
</div>

@push('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('{{ $chartId }}').getContext('2d');
            
            const chartData = @json($config);
            const useCurrency = {{ $currencyY ? 'true' : 'false' }};

            new Chart(ctx, {
                type: 'line', 
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Ingresos',
                        data: chartData.data,
                        borderColor: '#C15C3D', // Nuestro color Terracota
                        backgroundColor: 'rgba(193, 92, 61, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#C15C3D',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    if (useCurrency) {
                                        return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 2});
                                    }
                                    return value;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                callback: function(value) {
                                    if (useCurrency) {
                                        return '$' + value.toLocaleString('en-US');
                                    }
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush