@extends('layouts.admin')
@section('title', 'Reportes')
@section('header', 'Reportes')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-lg font-medium mb-4">Ventas por Mes</h2>
    <table class="min-w-full text-sm">
        <thead class="border-b">
            <tr><th class="text-left py-1">Mes</th><th class="text-right py-1">Pedidos</th><th class="text-right py-1">Ingresos</th></tr>
        </thead>
        <tbody>
            @foreach($salesByMonth as $data)
                <tr class="border-b">
                    <td class="py-1">{{ $data['month'] }}</td>
                    <td class="text-right">{{ $data['count'] }}</td>
                    <td class="text-right">${{ number_format($data['revenue'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection