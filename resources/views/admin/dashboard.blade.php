@extends('layouts.admin.admin')

@section('title', 'Dashboard - Admin')

@push('styles')
    @vite(['resources/css/admin/dashboard.css'])
    <style>
        @media (max-width: 576px) {
            .stat-value, .stat-label, .fs-h3 {
                font-size: 1.1rem;
            }
            .fs-h1 {
                font-size: 1.5rem;
            }
            .progress {
                height: 8px;
            }
            .btn-sm {
                padding: 0.25rem 0.5rem;
            }
            .quick-access-card {
                padding: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-3 py-md-4" aria-label="Panel de Control">
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <p class="text-end fs-primary fw-bold color-espresso" id="fechaHora" aria-live="polite">Cargando...</p>
            <h1 class="h2 text-center fw-h4 color-espresso"><i class="bi bi-bar-chart-line-fill me-2"></i>Panel de Control</h1>
        </div>
    </div>

    <!-- Estadísticas Principales -->
    <section class="row mb-3 mb-md-4" aria-labelledby="estadisticas-title">
        <h2 id="estadisticas-title" class="visually-hidden">Estadísticas Principales</h2>
        <!-- Total Productos -->
        <div class="col-xl-3 col-md-6 mb-3 mb-md-4">
            <article class="card stat-card bg-chocolate text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Productos</div>
                            <div class="stat-value">{{ $stats['total_productos'] }}</div>
                        </div>
                        <div>
                            <i class="bi bi-box-seam fs-h1 color-amber" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Productos Con Stock -->
        <div class="col-xl-3 col-md-6 mb-3 mb-md-4">
            <article class="card stat-card bg-coffee text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Con Stock</div>
                            <div class="stat-value">{{ $stats['productos_disponibles'] }}</div>
                        </div>
                        <div>
                            <i class="bi bi-check-circle fs-h1 color-amber" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sin Stock -->
        <div class="col-xl-3 col-md-6 mb-3 mb-md-4">
            <article class="card stat-card bg-caramel text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Sin Stock</div>
                            <div class="stat-value">{{ $stats['productos_sin_stock'] }}</div>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle fs-h1 text-warning" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <!-- Valor Inventario -->
        <div class="col-xl-3 col-md-6 mb-3 mb-md-4">
            <article class="card stat-card bg-amber text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Valor Total</div>
                            <div class="stat-value">${{ number_format($stats['valor_inventario'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- Accesos Rápidos -->
    <section class="row mb-3 mb-md-4" aria-labelledby="accesos-title">
        <h2 id="accesos-title" class="visually-hidden">Accesos Rápidos</h2>
        <div class="col-12">
            <nav class="card shadow-sm border-0">
                <div class="card-header bg-espresso text-light border-0 d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Accesos Rápidos</h2>
                    <button class="btn btn-sm btn-outline-secondary" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseAccesos" 
                            aria-controls="collapseAccesos" aria-expanded="true" aria-label="Alternar accesos rápidos">
                        <i class="bi bi-chevron-down color-amber" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseAccesos" role="region" aria-labelledby="accesos-title">
                    <div class="card-body bg-caramel">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <a href="{{ route('admin.productos.crear.get') }}" class="quick-access-card d-block p-4 text-decoration-none bg-coffee" aria-label="Crear nuevo producto">
                                    <h3 class="h6 text-dark mb-0">Nuevo Producto</h3>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <a href="{{ route('admin.productos') }}" class="quick-access-card d-block p-4 text-decoration-none bg-coffee" aria-label="Ver todos los productos">
                                    <h3 class="h6 text-dark mb-0">Ver Productos</h3>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <a href="{{ route('admin.usuarios.create') }}" class="quick-access-card d-block p-4 text-decoration-none bg-coffee" aria-label="Ver todos los productos">
                                    <h3 class="h6 text-dark mb-0">Crear Empleado</h3>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </section>

    <div class="row">
        <!-- Columna Izquierda -->
        @include('components.dashboard-izquierda')

        <!-- Columna Derecha -->
        <div class="col-lg-4">
            <!-- Distribución por Categoría -->
            <section class="card shadow-sm border-0 mb-3 mb-md-4" aria-labelledby="categoria-title">
                <h2 id="categoria-title" class="visually-hidden">Distribución por Categoría</h2>
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">
                        <i class="bi bi-pie-chart color-amber me-2" aria-hidden="true"></i>Por Categoría
                    </h2>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseCategoria" 
                            aria-controls="collapseCategoria" aria-expanded="true" aria-label="Alternar distribución por categoría">
                        <i class="bi bi-chevron-down color-espresso" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseCategoria" role="region" aria-labelledby="categoria-title">
                    <div class="card-body bg-caramel">
                        @foreach($productosPorCategoria as $cat)
                            <article class="category-item mb-3 bg-coffee">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold color-espresso">{{ $cat->categoria }}</span>
                                    <span class="badge bg-chocolate">{{ $cat->total }}</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-chocolate" role="progressbar" 
                                         style="width: {{ ($cat->total / $stats['total_productos']) * 100 }}%" 
                                         aria-valuenow="{{ ($cat->total / $stats['total_productos']) * 100 }}" 
                                         aria-valuemin="0" aria-valuemax="100" 
                                         aria-label="Proporción de productos en {{ $cat->categoria }}">
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Valor de Inventario por Categoría -->
            <section class="card shadow-sm border-0 mb-3 mb-md-4" aria-labelledby="valor-title">
                <h2 id="valor-title" class="visually-hidden">Valor de Inventario por Categoría</h2>
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">
                        <i class="bi bi-cash color-amber me-2" aria-hidden="true"></i>Valor Inventario
                    </h2>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseValor" 
                            aria-controls="collapseValor" aria-expanded="true" aria-label="Alternar valor de inventario por categoría">
                        <i class="bi bi-chevron-down color-espresso" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseValor" role="region" aria-labelledby="valor-title">
                    <div class="card-body bg-caramel">
                        @foreach($valorPorCategoria as $val)
                            <article class="valor-item mb-4 bg-coffee">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold color-espresso">{{ $val->categoria }}</span>
                                    <span class="color-espresso fw-bold">
                                        ${{ number_format($val->valor_total, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-{{ $stats['valor_inventario'] == 0 ? 'caramel' : 'chocolate' }}" 
                                         role="progressbar" 
                                         style="width: {{ $stats['valor_inventario'] == 0 ? '100' : ($val->valor_total / $stats['valor_inventario'] * 100) }}%" 
                                         aria-valuenow="{{ $stats['valor_inventario'] == 0 ? 0 : ($val->valor_total / $stats['valor_inventario'] * 100) }}" 
                                         aria-valuemin="0" aria-valuemax="100" 
                                         aria-label="Valor de inventario en {{ $val->categoria }}">
                                        {{ number_format($stats['valor_inventario'] == 0 ? 0 : ($val->valor_total / $stats['valor_inventario'] * 100), 1) }}%
                                    </div>
                                </div>
                                <small class="color-espresso mt-1 d-block">
                                    <i class="bi bi-box me-1" aria-hidden="true"></i>{{ number_format($val->cantidad_total, 0) }} unidades
                                </small>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@vite(['resources/js/fecha.js', 'resources/js/dashboard.js'])
@endsection