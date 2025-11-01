@extends('layouts.admin.admin')

@section('title', 'Estadísticas de Ventas')

@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
    <style>
        @media (max-width: 576px) {
            .table th, .table td {
                font-size: 0.9rem;
            }
            .progress {
                height: 20px !important;
            }
            .card-body h2, .card-body p.h2 {
                font-size: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-3 py-md-4" aria-label="Estadísticas de Ventas">
    
        <h1 class="h2"><i class="bi bi-graph-up me-2"></i>Estadísticas de Ventas</h1>

    <!-- Resumen de ingresos -->
    <section class="row mb-3 mb-md-4 g-2 g-md-3" aria-labelledby="ingresos-title">
        <h2 id="ingresos-title" class="visually-hidden">Resumen de Ingresos</h2>
        <div class="col-12 col-md-6">
            <article class="card bg-coffee text-white shadow-sm">
                <div class="card-body">
                    <h3>Ingresos Totales</h3>
                    <p class="h2 mb-1">${{ number_format($ingresosTotales, 2, ',', '.') }}</p>
                    <small>Total histórico</small>
                </div>
            </article>
        </div>
        <div class="col-12 col-md-6">
            <article class="card bg-caramel text-white shadow-sm">
                <div class="card-body">
                    <h3>Ingresos del Mes</h3>
                    <p class="h2 mb-1">${{ number_format($ingresosMes, 2, ',', '.') }}</p>
                    <small>{{ trans('date.months.' . now()->format('F')) . ' ' . now()->format('Y') }}</small>
                </div>
            </article>
        </div>
    </section>

    <!-- Productos más vendidos -->
    <section class="card shadow-sm mb-3 mb-md-4" aria-labelledby="top-productos-title">
        <header class="card-header bg-espresso text-white">
            <h2 class="h5 mb-0"><i class="bi bi-trophy me-2"></i><span id="top-productos-title">Top 10 Productos Más Vendidos</span></h2>
        </header>
        <div class="card-body bg-sand">
            <div class="table-responsive">
                <table class="table table-hover" aria-label="Tabla de productos más vendidos">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Categoría</th>
                            <th scope="col" class="text-center">Cantidad Vendida</th>
                            <th scope="col" class="text-center">N° Pedidos</th>
                            <th scope="col" class="text-end">Ingresos Totales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productosMasVendidos as $index => $producto)
                            <tr>
                                <td>
                                    @if($index == 0)
                                        <i class="bi bi-trophy-fill text-warning" aria-label="Primer lugar"></i>
                                    @elseif($index == 1)
                                        <i class="bi bi-trophy-fill text-secondary" aria-label="Segundo lugar"></i>
                                    @elseif($index == 2)
                                        <i class="bi bi-trophy-fill text-danger" aria-label="Tercer lugar"></i>
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
    </section>

    <!-- Ventas por categoría -->
    <section class="card shadow-sm mb-3 mb-md-4" aria-labelledby="ventas-categoria-title">
        <header class="card-header bg-espresso text-white">
            <h2 class="h5 mb-0"><i class="bi bi-pie-chart me-2"></i><span id="ventas-categoria-title">Ventas por Categoría</span></h2>
        </header>
        <div class="card-body bg-caramel">
            <div class="row g-2 g-md-3">
                @foreach($ventasPorCategoria as $venta)
                    @php
                        $porcentaje = $ingresosTotales > 0 ? ($venta->total / $ingresosTotales) * 100 : 0;
                    @endphp
                    <div class="col-12 col-sm-6 col-md-4 mb-3">
                        <article class="card bg-coffee">
                            <div class="card-body text-center">
                                <h3 class="color-espresso">{{ $venta->categoria }}</h3>
                                <p class="h2 color-chocolate mb-1">
                                    ${{ number_format($venta->total, 2, ',', '.') }}
                                </p>
                                <div class="progress" style="height: 25px;" role="progressbar" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar bg-{{ $porcentaje == 0 ? 'caramel' : 'chocolate' }}" style="width: {{ $porcentaje }}%">
                                        <span>{{ number_format($porcentaje, 1) }}%</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Ventas por día (últimos 30 días) -->
    <section class="card shadow-lg rounded-3 mb-3 mb-md-4" aria-labelledby="ventas-diarias-title">
        <header class="card-header bg-espresso text-white py-3">
            <h2 class="h5 mb-0"><i class="bi bi-calendar3 me-2"></i><span id="ventas-diarias-title">Ventas de los Últimos 30 Días</span></h2>
        </header>
        <div class="card-body bg-sand p-3 p-md-4">
            <figure>
                <figcaption class="visually-hidden">Gráfico de ventas diarias de los últimos 30 días</figcaption>
                <canvas id="ventasChart" style="max-height: 400px;"></canvas>
            </figure>
        </div>
    </section>
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
                    borderColor: '#A0522D',
                    backgroundColor: 'rgba(160, 82, 45, 0.2)',
                    fill: true,
                    tension: 0.4,
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
                    borderColor: '#2F4F4F',
                    backgroundColor: 'rgba(47, 79, 79, 0.2)',
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
                        usePointStyle: true
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
                        display: false
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
                        stepSize: 1,
                        font: { size: 12 },
                        color: '#333'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
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
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
});
</script>
@endsection