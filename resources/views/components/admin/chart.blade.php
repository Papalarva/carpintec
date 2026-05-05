@props(['config', 'currencyY' => false])

<!-- Contenedor de la gráfica -->
<div class="relative h-72 w-full">
    <canvas id="dashboardSalesChart"></canvas>
</div>

<!-- Inyectamos el script solo si se usa este componente -->
@push('scripts')
    <!-- Cargamos Chart.js desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dashboardSalesChart').getContext('2d');
            
            // Recibimos los datos de PHP (Controlador) a JavaScript
            const chartData = @json($config);
            const useCurrency = {{ $currencyY ? 'true' : 'false' }};

            new Chart(ctx, {
                type: 'line', // Tipo de gráfica elegante
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Ingresos',
                        data: chartData.data,
                        borderColor: '#C15C3D', // Nuestro color Terracota
                        backgroundColor: 'rgba(193, 92, 61, 0.1)', // Fondo semi-transparente
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#C15C3D',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.4 // Curvas suaves
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }, // Ocultamos la leyenda para más minimalismo
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
                            grid: { display: false } // Quitamos las líneas verticales
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