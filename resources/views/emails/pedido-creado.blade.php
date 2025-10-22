@extends('emails.layout')

@section('title', 'Pedido Confirmado')

@section('content')
    <h2>¡Pedido Confirmado! 🎉</h2>
    
    <p>Hola <strong>{{ $pedido->nombre }}</strong>,</p>
    
    <p>Tu pedido ha sido confirmado exitosamente. Aquí están los detalles:</p>

    <div class="codigo-retiro">
        <div class="label">Tu Código de Retiro es:</div>
        <div class="codigo">{{ $pedido->codigo_retiro }}</div>
    </div>

    <div class="info-box">
        <strong>📋 Número de Pedido:</strong> {{ $pedido->codigo_pedido }}<br>
        <strong>📅 Fecha:</strong> {{ $pedido->created_at->locale('es')->format('d/m/Y H:i') }}<br>
        <strong>💳 Método de Pago:</strong> {{ $pedido->medio_pago }}<br>
        <strong>💰 Total Pagado:</strong> ${{ number_format($pedido->monto_final, 2, ',', '.') }}
    </div>

    <h3>Productos del Pedido:</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items as $item)
                <tr>
                    <td>{{ $item->nombre_producto }}</td>
                    <td>{{ $item->cantidad }} {{ config('unidades.unidadMedida.'.$item->unidad_venta) }}</td>
                    <td>${{ number_format($item->precio_unitario, 2, ',', '.') }}</td>
                    <td>${{ number_format($item->subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" style="text-align: right;"><strong>RECARGO:</strong></td>
                @php
                    use App\Services\PedidoService;
                    $pedidoService = new PedidoService();
                    $recargo = $pedidoService->calcularMontoFinal($pedido->monto_total, $pedido->medio_pago)
                @endphp
                <td> ${{ number_format($recargo['monto_recargo'], 2, ',', '.')  }} </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                <td><strong>${{ number_format($pedido->monto_final, 2, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <h3>¿Cómo retirar tu pedido?</h3>
    <ol>
        <li>Guarda tu código de retiro: <strong>{{ $pedido->codigo_retiro }}</strong></li>
        <li>Espera nuestra confirmación cuando tu pedido esté listo</li>
        <li>Acércate a nuestro local</li>
        <li>Presenta tu código de retiro</li>
        <li>¡Disfruta tus productos frescos!</li>
    </ol>

    <div style="text-align: center;">
        <a href="{{ route('pedido.detalle', $pedido->id) }}" class="button">
            Ver Detalle del Pedido
        </a>
    </div>

    <p>¡Gracias por tu compra!</p>
@endsection