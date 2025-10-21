@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4 color-chocolate">
                <i class="bi bi-bag-check"></i> Mis Pedidos
            </h2>

            @if(empty($pedidos))
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    No se encontraron pedidos asociados a este correo.
                </div>
            @else
                @foreach($pedidos as $pedido)
                    <div class="card shadow-sm mb-3 border-chocolate">
                        <div class="card-body bg-sand">
                            <div class="row align-items-center">
                                <!-- Información del pedido -->
                                <div class="col-md-3">
                                    <h5 class="mb-1 color-espresso">Pedido #{{ '2342234'/*$pedido['codigo_pedido'] */}}</h5>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($pedido['created_at'])->format('d/m/Y H:i') }}
                                    </small>
                                </div>

                                <!-- Código de retiro -->
                                <div class="col-md-2 text-center">
                                    <small class="text-muted d-block">Código Retiro:</small>
                                    <strong class="color-chocolate fs-5">{{ $pedido['codigo_retiro'] }}</strong>
                                </div>

                                <!-- Estado -->
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-{{ $pedido['estado'] === 'entregado' ? 'success' : ($pedido['estado'] === 'cancelado' ? 'danger' : 'caramel') }} fs-6">
                                        {{ ucfirst($pedido['estado']) }}
                                    </span>
                                </div>

                                <!-- Total -->
                                <div class="col-md-2 text-center">
                                    <small class="text-muted d-block">Total:</small>
                                    <strong class="color-chocolate fs-5">
                                        ${{ number_format($pedido['monto_final'], 2, ',', '.') }}
                                    </strong>
                                </div>

                                <!-- Acciones -->
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('pedido.detalle', ['pedido' => $pedido['id']]) }}" class="btn btn-coffee btn-sm">
                                        <i class="bi bi-eye"></i> Ver Detalle
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('pedido.mostrar-buscar') }}" class="btn btn-chocolate" style="color: var(--color-amber);>
                    <i class="bi bi-arrow-left"></i> Nueva Búsqueda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection