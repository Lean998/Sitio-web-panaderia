@extends('emails.layout')

@section('title', 'Actualización de Pedido')

@section('content')
    <h2>Actualización de tu Pedido</h2>
    
    <p>Hola <strong>{{ $pedido->nombre }}</strong>,</p>
    
    <p>Tu pedido <strong>{{ $pedido->codigo_pedido }}</strong> ha sido actualizado.</p>

    <div class="info-box">
        <strong>Estado anterior:</strong> {{ ucfirst($estadoAnterior) }}<br>
        <strong>Estado actual:</strong> <span style="color: #2F4F4F; font-size: 18px;">{{ ucfirst($pedido->estado) }}</span>
    </div>

    @if($pedido->estado === 'listo')
        <div class="codigo-retiro">
            <div class="label">🎉 ¡Tu pedido está listo para retirar!</div>
            <div class="label" style="margin-top: 15px;">Presenta este código:</div>
            <div class="codigo">{{ $pedido->codigo_retiro }}</div>
        </div>

        <div class="info-box">
            <strong>⏰ Horarios de retiro:</strong><br>
            Martes a Sábado: 8:00 - 20:00<br>
            Domingos: 8:00 - 14:00<br>
            Lunes: Cerrado
        </div>
    @elseif($pedido->estado === 'preparando')
        <p>Estamos preparando tu pedido con mucho cariño. Te avisaremos cuando esté listo para retirar.</p>
    @elseif($pedido->estado === 'entregado')
        <p>¡Gracias por retirar tu pedido! Esperamos que disfrutes nuestros productos.</p>
    @elseif($pedido->estado === 'cancelado')
        <p>Lamentamos informarte que tu pedido ha sido cancelado.</p>
        <p>Si pagaste con tarjeta, el reembolso se procesará en 5-10 días hábiles.</p>
        <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
    @endif

    <div style="text-align: center;">
        <a href="{{ route('pedido.detalle', $pedido->id) }}" class="button">
            Ver Detalle del Pedido
        </a>
    </div>

    <p>¡Gracias por elegirnos!</p>
@endsection