@props([
    'config' => [],
    'currencyY' => false,
])

<div class="bg-white rounded-lg shadow p-4" style="height: 300px;">
    <canvas
        class="js-chart"
        data-chart-config="{{ json_encode($config) }}"
        @if($currencyY) data-chart-currency-y="true" @endif
    ></canvas>
</div>