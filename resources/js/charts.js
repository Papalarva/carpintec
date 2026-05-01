import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const canvases = document.querySelectorAll('.js-chart');

    canvases.forEach(canvas => {
        let config = JSON.parse(canvas.dataset.chartConfig);

        // Aplicar callback de moneda si está activado
        if (canvas.dataset.chartCurrencyY === 'true') {
            config.options = config.options || {};
            config.options.scales = config.options.scales || {};
            config.options.scales.y = config.options.scales.y || {};
            config.options.scales.y.ticks = {
                ...config.options.scales.y.ticks,
                callback: (value) => '$' + value
            };
        }

        new Chart(canvas, config);
    });
});