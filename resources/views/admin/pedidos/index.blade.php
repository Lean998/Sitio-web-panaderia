@extends('layouts.admin.admin')

@section('title', 'Gestión de Pedidos')
@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
    <style>
        @media (max-width: 576px) {
            .table th, .table td {
                font-size: 0.9rem;
            }
            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
            }
            .badge {
                font-size: 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-3 py-md-4" aria-label="Gestión de Pedidos">
    
    <div class="row mb-3 mb-md-4 align-items-center">
        <div class="col-12 col-md-8 mb-2 mb-md-0">
            <h1 class="h2"><i class="bi bi-receipt me-2"></i>Gestión de Pedidos</h1>
        </div>
        @if (session('admin_role') === 'admin')
        <div class="col-12 col-md-4 text-center text-md-end">
            <a href="{{ route('admin.pedidos.estadisticas') }}" class="btn btn-chocolate" style="color:var(--color-sand);" aria-label="Ver estadísticas de pedidos">
                <i class="bi bi-graph-up"></i> Ver Estadísticas
            </a>
        </div>
        @endif
    </div>

    <!-- Estadísticas rápidas -->
    <section class="row mb-3 mb-md-4 g-2 g-md-3" aria-labelledby="estadisticas-title">
        <h2 id="estadisticas-title" class="visually-hidden">Estadísticas Rápidas</h2>
        <div class="col-6 col-sm-4 col-md-2">
            <article class="card bg-caramel text-white h-100">
                <div class="card-body text-center">
                    <h3>{{ $stats['pendientes'] }}</h3>
                    <small>Pendientes</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <article class="card bg-espresso text-white h-100">
                <div class="card-body text-center">
                    <h3>{{ $stats['pagados'] }}</h3>
                    <small>Pagados</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <article class="card bg-coffee text-white h-100">
                <div class="card-body text-center">
                    <h3>{{ $stats['preparando'] }}</h3>
                    <small>Preparando</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <article class="card bg-success text-white h-100">
                <div class="card-body text-center">
                    <h3>{{ $stats['listos'] }}</h3>
                    <small>Listos</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <article class="card bg-chocolate text-white h-100">
                <div class="card-body text-center">
                    <h3>{{ $stats['entregados'] }}</h3>
                    <small>Entregados</small>
                </div>
            </article>
        </div>
        <div class="col-6 col-sm-4 col-md-2">
            <article class="card bg-danger text-white h-100">
                <div class="card-body text-center">
                    <h3>{{ $stats['cancelados'] }}</h3>
                    <small>Cancelados</small>
                </div>
            </article>
        </div>
    </section>

    <!-- Filtros -->
    <section class="card shadow-sm mb-3 mb-md-4 border-chocolate" aria-labelledby="filtros-title">
        <h2 id="filtros-title" class="visually-hidden">Filtros de Pedidos</h2>
        <div class="card-body bg-sand">
            <form action="{{ route('admin.pedidos.index') }}" method="GET" role="search">
                <div class="row g-2 g-md-3 flex-column flex-md-row">
                    <div class="col-12 col-md-3">
                        <label class="form-label" for="buscar">Buscar:</label>
                        <input type="text" id="buscar" name="buscar" class="form-control input-texto" placeholder="Código, nombre, email..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-12 col-md-3 col-lg-2">
                        <label class="form-label" for="estado">Estado:</label>
                        <select id="estado" name="estado" class="form-select input-texto">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="preparando" {{ request('estado') == 'preparando' ? 'selected' : '' }}>Preparando</option>
                            <option value="listo" {{ request('estado') == 'listo' ? 'selected' : '' }}>Listo</option>
                            <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 col-lg-2">
                        <label class="form-label" for="fecha_desde">Desde:</label>
                        <input type="date" id="fecha_desde" name="fecha_desde" class="form-control input-texto" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-12 col-md-3 col-lg-2">
                        <label class="form-label" for="fecha_hasta">Hasta:</label>
                        <input type="date" id="fecha_hasta" name="fecha_hasta" class="form-control input-texto" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-12 col-md-3 col-lg-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-chocolate flex-fill" style="color:var(--color-sand);" aria-label="Buscar pedidos">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-caramel" aria-label="Limpiar filtros">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Tabla de pedidos -->
    <section class="card shadow-sm" aria-labelledby="pedidos-table-title">
        <h2 id="pedidos-table-title" class="visually-hidden">Tabla de Pedidos</h2>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 border-chocolate" aria-label="Lista de pedidos">
                    <thead class="bg-chocolate text-white">
                        <tr>
                            <th scope="col">Código Pedido</th>
                            <th scope="col">Código Retiro</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Total</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                            <tr>
                                <td><strong>{{ $pedido->codigo_pedido }}</strong></td>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $pedido->codigo_retiro }}</code>
                                </td>
                                <td>
                                    <div>{{ $pedido->nombre }}</div>
                                    <small class="text-muted">{{ $pedido->correo }}</small>
                                </td>
                                <td>
                                    <div>{{ $pedido->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $pedido->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $pedido->estadoBadge }} p-2">
                                        {{ ucfirst($pedido->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <strong class="color-chocolate">
                                        ${{ number_format($pedido->monto_final, 2, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm gap-2">
                                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn btn-info" title="Ver detalle" aria-label="Ver detalle del pedido">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cambiarEstadoModal{{ $pedido->id }}" title="Cambiar estado" aria-label="Cambiar estado del pedido">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            <!-- Modal cambiar estado -->
                            <div class="modal fade" id="cambiarEstadoModal{{ $pedido->id }}" tabindex="-1" aria-labelledby="cambiarEstadoModalLabel{{ $pedido->id }}">
                                <div class="modal-dialog modal-sm modal-md">
                                    <div class="modal-content">
                                        <div class="modal-header bg-chocolate text-white">
                                            <h3 class="modal-title" id="cambiarEstadoModalLabel{{ $pedido->id }}">Cambiar Estado del Pedido</h3>
                                            <button type="button" class="btn-close bg-sand" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                        </div>
                                        <form action="{{ route('admin.pedidos.cambiar-estado', $pedido->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body bg-sand">
                                                <p><strong>Pedido:</strong> {{ $pedido->codigo_pedido }}</p>
                                                <p><strong>Estado actual:</strong> <span class="badge bg-{{ $pedido->estadoBadge }}">{{ ucfirst($pedido->estado) }}</span></p>
                                                <div class="mb-3">
                                                    <label class="form-label" for="estado-{{ $pedido->id }}">Nuevo Estado:</label>
                                                    <select id="estado-{{ $pedido->id }}" name="estado" class="form-select input-texto" required>
                                                        <option value="pagado" {{ $pedido->estado == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                        <option value="preparando" {{ $pedido->estado == 'preparando' ? 'selected' : '' }}>Preparando</option>
                                                        <option value="listo" {{ $pedido->estado == 'listo' ? 'selected' : '' }}>Listo para Retirar</option>
                                                        <option value="entregado" {{ $pedido->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                                        <option value="cancelado" {{ $pedido->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-sand" style="border-top-color: var(--color-chocolate);">
                                                <button type="button" class="btn btn-caramel" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" class="btn btn-chocolate color-sand">Actualizar Estado</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <figure>
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <figcaption class="text-muted">No hay pedidos para mostrar</figcaption>
                                    </figure>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pedidos->hasPages())
            <div class="card-footer bg-sand text-center">
                {{ $pedidos->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>
</div>
@endsection