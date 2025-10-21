@extends('layouts.app')

@push('styles')
    @vite(['resources/css/productos.css'])
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('title', 'Carrito')

@section('content')
<div class="container" id="productos-container">
    <h1 class="text-center mt-4 color-coffee fw-bold">Carrito de compras</h1>

    @if (count($carrito) === 0)
        <div class="alert bg-sand border-chocolate color-chocolate fw-semibold fs-body text-center mt-4">
            El carrito está vacío
        </div>
    @else
        <div class="row g-3 mt-4">
            @foreach ($carrito as $producto)
                @php
                    $unidadMedida = match($producto['unidad_venta']) {
                        'docena' => 'docena/as',
                        'media_docena' => 'media docena/as',
                        'kg' => 'kg',
                        default => 'unidad/es',
                    };
                @endphp
                <div class="col-12">
                    <div class="producto-carrito bg-sand border-chocolate rounded p-3 shadow-sm d-flex flex-column flex-md-row align-items-md-center gap-3">
                        {{-- Imagen del producto --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('productos.ver', ['producto' => $producto['id']]) }}">
                                <img src="{{ asset('storage/' . $producto['imagen']) }}" alt="{{ $producto['nombre'] }}" title="{{ $producto['nombre'] }}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </a>
                        </div>
                        
                        {{-- Nombre y precio --}}
                        <div class="flex-grow-1">
                            <h3 class="color-chocolate mb-2 fs-5">{{ $producto['nombre'] }}</h3>
                            <p class="mb-0 fw-bold color-coffee">Precio unitario: ${{ number_format($producto['precio'], 2, ',', '.') }}</p>
                        </div>
                        
                        {{-- Controles de cantidad --}}
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            {{-- Botones de decremento --}}
                            <form action="{{ route('carrito.eliminarUnidad', ['producto' => $producto['id']]) }}" method="GET" class="d-flex gap-1">
                                @csrf
                                @if($unidadMedida === 'kg')
                                    <button type="submit" class="btn btn-danger btn-sm" name="cantidad" value="-0.05">-0.05</button>
                                    <button type="submit" class="btn btn-danger btn-sm" name="cantidad" value="-0.25">-0.25</button>
                                @endif
                                <button type="submit" class="btn btn-danger btn-sm" name="cantidad" value="-1">-1</button>
                            </form>
                            
                            {{-- Cantidad actual --}}
                            @php
                                $cantidadEnCarrito = $unidadMedida === 'kg' ? number_format($producto['cantidad'], 2, ',', '.') : (int) $producto['cantidad'];
                            @endphp
                            <span class="fw-bold fs-5 mx-2">{{ $cantidadEnCarrito . ' ' . ucfirst($unidadMedida) }}</span>
                            
                            {{-- Botones de incremento --}}
                            <form action="{{ route('carrito.agregarUnidad', ['producto' => $producto['id']]) }}" method="GET" class="d-flex gap-1">
                                @csrf
                                @if($unidadMedida === 'kg')
                                    <button type="submit" class="btn btn-success btn-sm" name="cantidad" value="+0.25">+0.25</button>
                                    <button type="submit" class="btn btn-success btn-sm" name="cantidad" value="+0.05">+0.05</button>
                                    <button type="submit" class="btn btn-success btn-sm" name="cantidad" value="+1">+1</button>
                                @else
                                    <button type="submit" class="btn btn-success btn-sm" name="cantidad" value="+1">+1</button>
                                @endif
                            </form>
                            
                            {{-- Botón de eliminar --}}
                            <form action="{{ route('carrito.eliminarProducto', ['producto' => $producto['id']]) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Total y Vaciar carrito --}}
        <div class="d-flex justify-content-between align-items-center my-4 p-3 bg-caramel rounded">
            <p class="mb-0 fw-semibold color-chocolate fs-5">Total: ${{ number_format($total, 2, ',', '.') }}</p>
            <form action="{{ route('carrito.eliminarCarrito') }}">
                @csrf
                <div class="d-flex justify-content-center align-items-center">
                    <button class="btn btn-danger">Vaciar carrito</button>
                    
                    <div class="mx-2">
                        <a href="{{ route('pedido.checkout') }}" class="btn btn-md btn-chocolate">
                            <i class="bi bi-basket"></i> Proceder al Checkout
                        </a>
                    </div>

                </div>
                
            </form>
        </div>

        
    @endif
</div>

<script>
function ajustarCantidad(productoId, cambio) {
    let input = document.getElementById("cantidad-" + productoId);
    let valor = parseFloat(input.value) || 0;
    let nuevoValor = Math.max(0, (valor + cambio).toFixed(2)); // nunca menor a 0
    input.value = nuevoValor;
}
</script>
@endsection