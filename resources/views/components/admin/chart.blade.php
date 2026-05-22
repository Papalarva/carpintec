@props(['config', 'currencyY' => false])

@php
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
                        // Purgado: #C15C3D reemplazado por el hex de amber-900 (#78350f)
                        borderColor: '#78350f', 
                        backgroundColor: 'rgba(120, 53, 15, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#78350f',
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
                            // Tooltip Oscuro Premium
                            backgroundColor: '#111827',
                            titleFont: { family: 'Inter', size: 13 },
                            bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
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
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 12 } }
                        },
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            grid: { color: '#f3f4f6' },
                            ticks: {
                                font: { family: 'Inter', size: 12 },
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