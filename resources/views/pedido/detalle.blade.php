@extends('layouts.app')

@section('title', 'Detalle del Pedido')

@push('styles')
    @vite (['resources/css/pedidoDetalle.css'])
@endpush




@section('content')
<div class="container py-5">
    
    <div id="message" class="alert alert-success d-none position-fixed bottom-0 end-0 m-3 shadow">
        Código copiado con éxito.
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Header del pedido -->
            <div class="text-center mb-4">
                <h1 class="color-chocolate">Pedido #{{ $pedido->codigo_pedido }}</h1>
                <p class="color-espresso">Creado el {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Estado actual -->
            <div class="card shadow-sm mb-4 border-{{ $pedido->estadoBadge }}">
                <div class="card-body text-center bg-sand">
                    <h5 class="mb-3">Estado del Pedido</h5>
                    <span class="badge bg-{{ $pedido->estadoBadge }} fs-4 px-4 py-2">
                        {{ ucfirst($pedido->estado) }}
                    </span>

                    @if($pedido->estado === 'listo')
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="bi bi-check-circle"></i>
                            <strong>¡Tu pedido está listo para retirar!</strong>
                            <p class="mb-0 mt-2">Presenta este código en el local: <strong>{{ $pedido->codigo_retiro }}</strong></p>
                        </div>
                    @elseif($pedido->estado === 'preparando')
                        <div class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-clock"></i>
                            Estamos preparando tu pedido. Te avisaremos cuando esté listo.
                        </div>
                    @elseif($pedido->estado === 'entregado')
                        <div class="alert alert-secondary mt-3 mb-0">
                            <i class="bi bi-check-all"></i>
                            Pedido entregado el {{ $pedido->fecha_entrega?->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del cliente -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-chocolate text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Información del Cliente</h5>
                </div>
                <div class="card-body bg-sand">
                    <div class="row mb-2">
                        <div class="col-5"><strong>Nombre:</strong></div>
                        <div class="col-7">{{ $pedido->nombre }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Correo:</strong></div>
                        <div class="col-7">{{ $pedido->correo }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5"><strong>Código de Retiro:</strong></div>
                        <div class="col-7">
                            <strong class="color-chocolate fs-5">{{ $pedido->codigo_retiro }}</strong>
                            <button class="btn btn-sm btn-coffee ms-2" onclick="copiarCodigo('{{ $pedido->codigo_retiro }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle de productos -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-chocolate text-white">
                    <h5 class="mb-0"><i class="bi bi-bag"></i> Productos del Pedido</h5>
                </div>
                <div class="card-body bg-cream p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-sand">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->nombre_producto }}</strong></td>
                                        <td class="text-center">{{ $item->cantidad }} {{ config('unidades.unidadMedida.'.$item->unidad_venta )}}</td>
                                        <td class="text-end">${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                                        <td class="text-end">${{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-active">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">${{ number_format($pedido->monto_total, 2, ',', '.') }}</td>
                                </tr>
                                @if($pedido->monto_final > $pedido->monto_total)
                                    <tr>
                                        <td colspan="3" class="text-end text-danger">
                                            <strong>Recargo ({{ ucfirst($pedido->medio_pago) }}):</strong>
                                        </td>
                                        <td class="text-end text-danger">
                                            ${{ number_format($pedido->monto_final - $pedido->monto_total, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong class="fs-5">TOTAL:</strong></td>
                                    <td class="text-end">
                                        <strong class="fs-5 color-chocolate">
                                            ${{ number_format($pedido->monto_final, 2, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="d-grid gap-2">
                @if(in_array($pedido->estado, ['pagado', 'preparando', 'listo']))
                    <a href="{{ route('pedido.comprobante', $pedido->id) }}" class="btn btn-caramel" style="color: var(--color-chocolate); target="_blank">
                        <i class="bi bi-file-pdf"></i> Descargar Comprobante
                    </a>
                @endif
                <a href="{{ route('home') }}" class="btn btn-chocolate color-sand" style="color: var(--color-amber);>
                    <i class="bi bi-house"></i> Volver al Inicio
                </a>
            </div>

        </div>
    </div>
</div>
@vite(['resources/js/pedidoDetalle.js'])
@endsection