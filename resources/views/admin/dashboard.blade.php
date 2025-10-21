@extends('layouts.admin.admin')
@section('title', 'Dashboard - Admin')
@section('content')

@push('styles')
    @vite(['resources/css/admin/dashboard.css'])
@endpush)

<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-12">
            <p class="text-end fs-primary fw-bold color-espresso" id="fechaHora">Cargando...</p>
            <h2 class="text-center fw-h4 color-espresso"><i class="bi bi-bar-chart-line-fill"></i> Panel de Control</h2>
        </div>
    </div>

    <!-- TARJETAS DE ESTADÍSTICAS PRINCIPALES -->
    <div class="row mb-2">
        <!-- Total Productos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-chocolate text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Productos</div>
                            <div class="stat-value">{{ $stats['total_productos'] }}</div>
                        </div>
                        <div>
                            <i class="bi bi-box-seam fs-h1 color-amber"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Con Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-coffee text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Con Stock</div>
                            <div class="stat-value">{{ $stats['productos_disponibles'] }}</div>
                        </div>
                        <div>
                            <i class="bi bi-check-circle fs-h1 color-amber"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sin Stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-caramel text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Sin Stock</div>
                            <div class="stat-value">{{ $stats['productos_sin_stock'] }}</div>
                        </div>
                        <div>
                            <i class="bi bi-exclamation-triangle fs-h1 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valor Inventario -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-amber text-white shadow h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Valor Total</div>
                            <div class="stat-value">${{ number_format($stats['valor_inventario'], 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ACCESOS RÁPIDOS -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-espresso text-light border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"> Accesos Rápidos</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseAccesos">
                        <i class="bi bi-chevron-down color-amber"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseAccesos">
                    <div class="card-body bg-caramel">
                        <div class="row text-center">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <a href="{{ route('admin.productos.crear.get') }}" class="quick-access-card d-block p-4 text-decoration-none bg-coffee">
                                    <h6 class="text-dark mb-0">Nuevo Producto</h6>
                                </a>
                            </div>
                            <div class="col-md-6 mb-3 mb-md-0">
                                <a href="{{ route('admin.productos') }}" class="quick-access-card d-block p-4 text-decoration-none bg-coffee">
                                    <h6 class="text-dark mb-0">Ver Productos</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- COLUMNA IZQUIERDA -->
        @include('components.dashboard-izquierda')
        <!-- COLUMNA DERECHA -->
        <div class="col-lg-4">
            <!-- DISTRIBUCIÓN POR CATEGORÍA -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart color-amber"></i> Por Categoría
                    </h5>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseCategoria">
                        <i class="bi bi-chevron-down color-espresso"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseCategoria">
                    <div class="card-body bg-caramel">
                        @foreach($productosPorCategoria as $cat)
                            <div class="category-item mb-3 bg-coffee">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold color-espresso">{{ $cat->categoria }}</span>
                                    <span class="badge bg-chocolate">{{ $cat->total }}</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-chocolate" role="progressbar" style="width: {{ ($cat->total / $stats['total_productos']) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- VALOR DE INVENTARIO POR CATEGORÍA -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-cash color-amber"></i> Valor Inventario
                    </h5>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseValor">
                        <i class="bi bi-chevron-down color-espresso"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseValor">
                    <div class="card-body bg-caramel">
                        @foreach($valorPorCategoria as $val)
                            <div class="valor-item mb-4 bg-coffee">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold color-espresso">{{ $val->categoria }}</span>
                                    <span class="color-espresso fw-bold">
                                        ${{ number_format($val->valor_total, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 15px;">
                                    <div class="progress-bar bg-{{ $val->valor_total / $stats['valor_inventario'] == 0 ? 'caramel' : 'chocolate' }}" role="progressbar" style="width: {{ (($val->valor_total / $stats['valor_inventario']) * 100) == 0 ? '100' : ($val->valor_total / $stats['valor_inventario']) * 100 }}%">
                                        {{ number_format(($val->valor_total / $stats['valor_inventario']) * 100, 1) }}%
                                    </div>
                                </div>
                                <small class="color-espresso mt-1 d-block">
                                    <i class="bi bi-box"></i> {{ number_format($val->cantidad_total, 0) }} unidades
                                </small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Restaurar estados
    const collapses = document.querySelectorAll('.collapse');
    collapses.forEach(collapse => {
        const id = collapse.id;
        const savedState = localStorage.getItem(id);
        if (savedState === 'hidden') {
            collapse.classList.remove('show');
        }
    });

    const buttons = document.querySelectorAll('[data-bs-toggle="collapse"]');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const collapse = document.querySelector(target);
            
            setTimeout(() => {
                if (collapse.classList.contains('show')) {
                    localStorage.setItem(target.substring(1), 'visible');
                } else {
                    localStorage.setItem(target.substring(1), 'hidden');
                }
            }, 350);
        });
    });
});
</script>

@vite(['resources/js/fecha.js'])

@endsection