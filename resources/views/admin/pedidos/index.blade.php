@extends('layouts.admin.admin')

@section('title', 'Gestión de Pedidos')
@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush
@section('content')
<div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-receipt"></i> Gestión de Pedidos</h2>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.pedidos.estadisticas') }}" class="btn btn-chocolate" style="color:var(--color-sand);">
                <i class="bi bi-graph-up"></i> Ver Estadísticas
            </a>
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-caramel text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['pendientes'] }}</h3>
                    <small>Pendientes</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-espresso text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['pagados'] }}</h3>
                    <small>Pagados</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-coffee text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['preparando'] }}</h3>
                    <small>Preparando</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['listos'] }}</h3>
                    <small>Listos</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-chocolate text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['entregados'] }}</h3>
                    <small>Entregados</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['cancelados'] }}</h3>
                    <small>Cancelados</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4 border-chocolate">
        <div class="card-body bg-sand">
            <form action="{{ route('admin.pedidos.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Buscar:</label>
                        <input type="text" name="buscar" class="form-control input-texto" placeholder="Código, nombre, email..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Estado:</label>
                        <select name="estado" class="form-select input-texto">
                            <option value="">Todos</option>
                            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                            <option value="preparando" {{ request('estado') == 'preparando' ? 'selected' : '' }}>Preparando</option>
                            <option value="listo" {{ request('estado') == 'listo' ? 'selected' : '' }}>Listo</option>
                            <option value="entregado" {{ request('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Desde:</label>
                        <input type="date" name="fecha_desde" class="form-control input-texto" value="{{ request('fecha_desde') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Hasta:</label>
                        <input type="date" name="fecha_hasta" class="form-control input-texto" value="{{ request('fecha_hasta') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-chocolate flex-fill" style="color:var(--color-sand);">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-caramel">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de pedidos -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 border-chocolate">
                    <thead class="bg-chocolate text-white">
                        <tr>
                            <th>Código Pedido</th>
                            <th>Código Retiro</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Acciones</th>
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
                                        <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn btn-info" title="Ver detalle">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cambiarEstadoModal{{ $pedido->id }}" title="Cambiar estado">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Modal cambiar estado -->
                                    <div class="modal fade" id="cambiarEstadoModal{{ $pedido->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-chocolate text-white">
                                                    <h5 class="modal-title">Cambiar Estado del Pedido</h5>
                                                    <button type="button" class="btn-close bg-sand" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.pedidos.cambiar-estado', $pedido->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body bg-sand">
                                                        <p><strong>Pedido:</strong> {{ $pedido->codigo_pedido }}</p>
                                                        <p><strong>Estado actual:</strong> <span class="badge bg-{{ $pedido->estadoBadge }}">{{ ucfirst($pedido->estado) }}</span></p>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Nuevo Estado:</label>
                                                            <select name="estado" class="form-select input-texto" required>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-inbox fs-1 text-muted"></i>
                                    <p class="text-muted">No hay pedidos para mostrar</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($pedidos->hasPages())
            <div class="card-footer bg-sand">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>

</div>
@endsection