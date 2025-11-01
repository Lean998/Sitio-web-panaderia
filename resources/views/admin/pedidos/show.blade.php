@extends('layouts.admin.admin')

@section('title', 'Detalle del Pedido')

@push('styles')
    @vite(['resources/css/pedidoDetalle.css'])
    <style>
        @media (max-width: 576px) {
            .badge.fs-4 {
                font-size: 1.2rem !important;
            }
            .card-body h2, .card-body p.fs-5 {
                font-size: 1.25rem;
            }
            .table th, .table td {
                font-size: 0.9rem;
            }
            .btn-sm {
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="container py-4 py-md-5" aria-label="Detalle del Pedido">
    
    <div id="message" class="alert alert-success d-none position-fixed bottom-0 end-0 m-3 shadow">
        Código copiado con éxito.
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-3 mb-md-4">
                <h1 class="color-chocolate">Pedido #{{ $pedido->codigo_pedido }}</h1>
                <p class="color-espresso">Creado el {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <!-- Estado actual -->
            <section class="card shadow-sm mb-3 mb-md-4 border-{{ $pedido->estadoBadge }}" aria-labelledby="estado-title">
                <h2 id="estado-title" class="visually-hidden">Estado del Pedido</h2>
                <article class="card-body text-center bg-sand">
                    <h2 class="h5 mb-3">Estado del Pedido</h2>
                    <span class="badge bg-{{ $pedido->estadoBadge }} fs-4 px-4 py-2">
                        {{ ucfirst($pedido->estado) }}
                    </span>

                    @if($pedido->estado === 'listo')
                        <figure class="alert alert-success mt-3 mb-0">
                            <i class="bi bi-check-circle"></i>
                            <figcaption>
                                <strong>¡Tu pedido está listo para retirar!</strong>
                                <p class="mb-0 mt-2">Presenta este código en el local: <strong>{{ $pedido->codigo_retiro }}</strong></p>
                            </figcaption>
                        </figure>
                    @elseif($pedido->estado === 'preparando')
                        <figure class="alert alert-info mt-3 mb-0">
                            <i class="bi bi-clock"></i>
                            <figcaption>
                                Estamos preparando tu pedido. Te avisaremos cuando esté listo.
                            </figcaption>
                        </figure>
                    @elseif($pedido->estado === 'entregado')
                        <figure class="alert alert-secondary mt-3 mb-0">
                            <i class="bi bi-check-all"></i>
                            <figcaption>
                                Pedido entregado el {{ $pedido->fecha_entrega?->format('d/m/Y H:i') }}
                            </figcaption>
                        </figure>
                    @endif
                </article>
            </section>

            <!-- Información del cliente -->
            <section class="card shadow-sm mb-3 mb-md-4" aria-labelledby="cliente-title">
                <h2 id="cliente-title" class="visually-hidden">Información del Cliente</h2>
                <div class="card-header bg-chocolate text-white">
                    <h2 class="h5 mb-0"><i class="bi bi-person me-2"></i>Información del Cliente</h2>
                </div>
                <article class="card-body bg-sand">
                    <dl class="row mb-0">
                        <dt class="col-5">Nombre:</dt>
                        <dd class="col-7">{{ $pedido->nombre }}</dd>
                        <dt class="col-5">Correo:</dt>
                        <dd class="col-7">{{ $pedido->correo }}</dd>
                        <dt class="col-5">Código de Retiro:</dt>
                        <dd class="col-7">
                            <strong class="color-chocolate fs-5">{{ $pedido->codigo_retiro }}</strong>
                            <button class="btn btn-sm btn-coffee ms-2" onclick="copiarCodigo('{{ $pedido->codigo_retiro }}')" aria-label="Copiar código de retiro">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </dd>
                    </dl>
                </article>
            </section>

            <!-- Detalle de productos -->
            <section class="card shadow-sm mb-3 mb-md-4" aria-labelledby="productos-title">
                <h2 id="productos-title" class="visually-hidden">Productos del Pedido</h2>
                <div class="card-header bg-chocolate text-white">
                    <h2 class="h5 mb-0"><i class="bi bi-bag me-2"></i>Productos del Pedido</h2>
                </div>
                <div class="card-body bg-cream p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" aria-label="Lista de productos del pedido">
                            <thead class="bg-sand">
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-end">Precio</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pedido->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->nombre_producto }}</strong></td>
                                        <td class="text-center">{{ $item->cantidad }} {{ config('unidades.unidadMedida.'.$item->unidad_venta) }}</td>
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
            </section>

            <!-- Botones de acción -->
            <section class="d-grid gap-2" aria-labelledby="acciones-title">
                <h2 id="acciones-title" class="visually-hidden">Acciones</h2>
                @if(in_array($pedido->estado, ['pagado', 'preparando', 'listo']))
                    <a href="{{ route('pedido.comprobante', $pedido->id) }}" class="btn btn-caramel" style="color: var(--color-chocolate);" target="_blank" aria-label="Descargar comprobante del pedido">
                        <i class="bi bi-file-pdf"></i> Descargar Comprobante
                    </a>
                @endif
                <a href="{{ route(session('admin_role') == 'admin' ? 'admin.index' : 'admin.pedidos.index' ) }}" class="btn btn-chocolate color-sand" aria-label="Volver al inicio">
                    <i class="bi bi-house"></i> Volver al Inicio
                </a>
            </section>
        </div>
    </div>
</div>
@vite(['resources/js/pedidoDetalle.js'])
@endsection