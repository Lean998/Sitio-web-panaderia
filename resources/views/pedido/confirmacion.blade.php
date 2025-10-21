@extends('layouts.app')

@section('title', 'Pedido Confirmado')

@push('styles')
    @vite(['resources/css/inputsYBotones.css', 'resources/css/pedidoDetalle.css'])
@endpush

@section('content')


<div class="container py-5 ">
    
    <div id="message" class="alert alert-success d-none position-fixed bottom-0 end-0 m-3 shadow">
        Código copiado con éxito.
    </div>

    <div class="row justify-content-center border-chocolate">
        <div class="col-lg-9">
            
            {{-- Mensaje de éxito --}}
            <div class="text-center">
                <div class="">
                    <i class="bi bi-check-circle color-espresso" style="font-size: 5rem;"></i>
                </div>
                <h1 class="color-chocolate">¡Pedido Confirmado!</h1>
                <p class="lead color-espresso">Tu pedido ha sido procesado exitosamente</p>
            </div>

            {{-- Código de retiro destacado --}}
            <div class="card shadow-lg mb-4 border-chocolate">
                <div class="card-body text-center bg-caramel p-4">
                    <h5 class="color-espresso mb-3">Tu código de retiro es:</h5>
                    <h1 class="display-3 fw-bold color-chocolate mb-3" style="letter-spacing: 0.5rem;">
                        {{ $pedido->codigo_retiro }}
                    </h1>
                    <button class="btn btn-chocolate color-sand" onclick="copiarCodigo('{{ $pedido->codigo_retiro }}')">
                        <i class="bi bi-copy"></i> Copiar Código
                    </button>
                    <p class="text-muted mt-3 mb-0">
                        <small>
                            <i class="bi bi-envelope"></i> 
                            También enviamos este código a <strong>{{ $pedido->correo }}</strong>
                        </small>
                    </p>
                </div>
            </div>

            {{-- Información del pedido --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-chocolate color-cream">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Pedido</h5>
                </div>
                <div class="card-body bg-sand">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Número de pedido:</strong></div>
                        <div class="col-sm-8">{{ $pedido->codigo_pedido }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Cliente:</strong></div>
                        <div class="col-sm-8">{{ $pedido->nombre }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Correo:</strong></div>
                        <div class="col-sm-8">{{ $pedido->correo }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Método de Pago:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-caramel color-espresso">{{ ucfirst($pedido->medio_pago) }}</span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Estado:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-{{ $pedido->estadoBadge }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4"><strong>Total Pagado:</strong></div>
                        <div class="col-sm-8">
                            <span class="fs-4 fw-bold color-chocolate">
                                ${{ number_format($pedido->monto_final, 2, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detalle de productos --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-chocolate text-white">
                    <h5 class="mb-0"><i class="fas fa-list"></i> Detalle del Pedido</h5>
                </div>
                <div class="card-body bg-cream p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 ">
                            <thead class="bg-sand">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-chocolate">
                                @foreach($pedido->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->nombre_producto }}</strong></td>
                                        <td class="text-center">
                                            {{  number_format($item->cantidad, 2, ',', '.') }} {{ config('unidades.unidadMedida.'.$item->unidad_venta) }}
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format($item->precio_unitario, 2, ',', '.') }}
                                        </td>
                                        <td class="text-end">
                                            ${{ number_format($item->subtotal, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-active">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end">
                                        ${{ number_format($pedido->monto_total, 2, ',', '.') }}
                                    </td>
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

            {{-- Instrucciones --}}
            <div class="alert bg-cream color-espresso border-chocolate">
                <h5><i class="bi bi-info-circle"></i> ¿Cómo retirar tu pedido?</h5>
                <ol class="mb-0">
                    <li>Guarda tu código de retiro: <strong>{{ $pedido->codigo_retiro }}</strong></li>
                    <li>Espera a que tu pedido esté listo (recibirás un email)</li>
                    <li>Acércate a nuestro local con tu código</li>
                    <li>Presenta el código al personal</li>
                    <li>¡Disfruta tus productos!</li>
                </ol>
            </div>

            {{-- Botones de acción --}}
            <div class="d-grid gap-2 my-3">
                <a href="{{ route('pedido.comprobante', ['id' => $pedido->id]) }}" class="btn btn-coffee" style="color: var(--color-cream);>
                    <i class="bi bi-download"></i> Descargar Comprobante
                </a>
                <a href="{{ route('home') }}" class="btn btn-chocolate" style="color: var(--color-amber);>
                    <i class="bi bi-home"></i> Volver al Inicio
                </a>
            </div>

        </div>
    </div>
</div>

@vite(['resources/js/pedidoDetalle.js'])

@endsection