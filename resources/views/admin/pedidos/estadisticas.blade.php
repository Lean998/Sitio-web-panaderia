@extends('layouts.admin.admin')

@section('title', 'Estadísticas de Ventas')

@section('content')
<div class="container-fluid py-4">
    
    <h2 class="mb-4"><i class="bi bi-graph-up"></i> Estadísticas de Ventas</h2>

    <!-- Resumen de ingresos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-coffee text-white shadow-sm">
                <div class="card-body">
                    <h5>Ingresos Totales</h5>
                    <h2>${{ number_format($ingresosTotales, 2, ',', '.') }}</h2>
                    <small>Total histórico</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-caramel text-white shadow-sm">
                <div class="card-body">
                    <h5>Ingresos del Mes</h5>
                    <h2>${{ number_format($ingresosMes, 2, ',', '.') }}</h2>
                    <small>{{ trans('date.months.' . now()->format('F')) . ' ' . now()->format('Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-espresso text-white">
            <h5 class="mb-0"><i class="bi bi-trophy"></i> Top 10 Productos Más Vendidos</h5>
        </div>
        <div class="card-body bg-sand">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Cantidad Vendida</th>
                            <th class="text-center">N° Pedidos</th>
                            <th class="text-end">Ingresos Totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productosMasVendidos as $index => $producto)
                            <tr>
                                <td>
                                    @if($index == 0)
                                        <i class="bi bi-trophy-fill text-warning"></i>
                                    @elseif($index == 1)
                                        <i class="bi bi-trophy-fill text-secondary"></i>
                                    @elseif($index == 2)
                                        <i class="bi bi-trophy-fill text-danger"></i>
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td><strong>{{ $producto->nombre }}</strong></td>
                                <td><span class="badge bg-coffee">{{ $producto->categoria }}</span></td>
                                <td class="text-center">{{ number_format($producto->total_vendido, 2) .' ' . config('unidades.unidadMedida.'.$producto->unidad_venta) }}</td>
                                <td class="text-center">{{ $producto->cantidad_pedidos }}</td>
                                <td class="text-end color-chocolate fw-bold">
                                    ${{ number_format($producto->ingresos_totales, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Ventas por categoría -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-espresso text-white">
            <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Ventas por Categoría</h5>
        </div>
        <div class="card-body bg-caramel">
            <div class="row">
                @foreach($ventasPorCategoria as $venta)
                    @php
                        $porcentaje = $ingresosTotales > 0 ? ($venta->total / $ingresosTotales) * 100 : 0;
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="card bg-coffee">
                            <div class="card-body text-center">
                                <h4 class="color-espresso">{{ $venta->categoria }}</h4>
                                <h2 class="color-chocolate">
                                    ${{ number_format($venta->total, 2, ',', '.') }}
                                </h2>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-{{ $porcentaje == 0 ? 'caramel' : 'chocolate' }}" style="width: {{ $porcentaje == 0 ? 100 : $porcentaje }}%">
                                        {{ number_format($porcentaje, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Ventas por día (últimos 30 días) -->
    <div class="card shadow-lg rounded-3 mb-4">
        <div class="card-header bg-espresso text-white py-3">
            <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Ventas de los Últimos 30 Días</h5>
        </div>
        <div class="card-body bg-sand p-4">
            <canvas id="ventasChart" style="max-height: 400px;"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ventasChart').getContext('2d');

    const fechas = @json($ventasPorDia->pluck('fecha'));
    const cantidades = @json($ventasPorDia->pluck('cantidad'));
    const totales = @json($ventasPorDia->pluck('total'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas.map(f => {
                const date = new Date(f);
                return date.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
            }),
            datasets: [
                {
                    label: 'Cantidad de Pedidos',
                    data: cantidades,
                    borderColor: '#A0522D', // Sienna, marrón cálido vibrante
                    backgroundColor: 'rgba(160, 82, 45, 0.2)', // Relleno suave
                    fill: true,
                    tension: 0.4, // Líneas suaves
                    pointBackgroundColor: '#A0522D',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#A0522D',
                    pointRadius: 4,
                    pointHoverRadius: 6
                },
                {
                    label: 'Ingresos ($)',
                    data: totales,
                    borderColor: '#2F4F4F', // Dark Slate Gray, azul oscuro
                    backgroundColor: 'rgba(47, 79, 79, 0.2)', // Relleno suave
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2F4F4F',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#2F4F4F',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            family: 'Arial, sans-serif',
                            weight: 'bold'
                        },
                        color: '#333',
                        padding: 20,
                        usePointStyle: true // Usar círculos en la leyenda para mejor estética
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 6,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.parsed.y || 0;
                            if (label === 'Ingresos ($)') {
                                return `${label}: $${value.toLocaleString('es-AR', { minimumFractionDigits: 2 })}`;
                            }
                            return `${label}: ${value}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false // Sin líneas de cuadrícula en X
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: 'Arial, sans-serif'
                        },
                        color: '#333',
                        maxRotation: 45,
                        minRotation: 45
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Cantidad de Pedidos',
                        font: { size: 14, weight: 'bold' },
                        color: '#333'
                    },
                    ticks: {
                        stepSize: 1, // Solo valores enteros
                        font: { size: 12 },
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)' // Cuadrícula suave
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Ingresos ($)',
                        font: { size: 14, weight: 'bold' },
                        color: '#333'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString('es-AR', { minimumFractionDigits: 2 });
                        },
                        font: { size: 12 },
                        color: '#333'
                    },
                    grid: {
                        drawOnChartArea: false // Sin cuadrícula en Y1
                    }
                }
            }
        }
    });
});
</script>
@endsection