@extends('layouts.app')

@push('styles')
    @vite(['public/css/productos.css'])
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
                    <div class="producto-carrito bg-sand border-chocolate rounded p-3 shadow-sm d-flex flex-wrap justify-content-between align-items-center">
                        {{-- Imagen del producto --}}
                        
                            <div class="flex-shrink-0 me-3">
                                <a href="{{ route('productos.ver', ['producto' => $producto['id']]) }}">
                                <img src="{{ asset('images/categorias/panaderia.jpg') }}" alt="{{ $producto['nombre'] }}" title="{{ $producto['nombre'] }}" class="img-fluid rounded" style="width: 130px; height: 130px; object-fit: cover;">
                                </a>
                            </div>
                        
                        {{-- Nombre y precio --}}
                        <div class="me-3 flex-grow-1">
                            <h3 class="color-chocolate mb-2">{{ $producto['nombre'] }}</h3>
                            <p class="mb-0 fw-bold color-coffee">Precio unitario: ${{ number_format($producto['precio'], 2, ',', '.') }}</p>
                        </div>
                        {{-- Controles de cantidad --}}
                        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                            <form action="{{ route('carrito.eliminarUnidad', ['producto' => $producto['id']]) }}">
                                @csrf
                                {{-- Botones de decremento --}}
                                @if($unidadMedida === 'kg')
                                <input type="submit" class="btn btn-danger btn-sm" name="cantidad" value="-0.50"></input>
                                <input type="submit" class="btn btn-danger btn-sm" name="cantidad" value="-0.25"></input>
                                @endif
                                <button class="btn btn-danger btn-sm" type="submit" name="1">-1</button>
                            </form>
                        @php
                        if($unidadMedida === 'kg')
                            $cantidadEnCarrito = number_format($producto['cantidad'], 2, ',', '.');
                        else
                            $cantidadEnCarrito = (int) $producto['cantidad'];
                        @endphp
                        <span class="fw-bold fs-5">{{ $cantidadEnCarrito . ' ' . ucfirst($unidadMedida) }} </span>

        @if ($unidadMedida === 'kg')
            <form action="{{ route('carrito.agregarUnidad', ['producto' => $producto['id']]) }}" method="GET" class="d-flex align-items-center gap-2">
                @csrf
                {{-- Botones de incremento --}}
                <input type="submit" class="btn btn-success btn-sm" name="cantidad" value="+0.25"></input>
                <input type="submit" class="btn btn-success btn-sm" name="cantidad" value="+0.50"></input>
                <input type="submit" class="btn btn-success btn-sm" name="cantidad" value="+1"></input>
            </form>
@else
    {{-- Caso clásico: unidades enteras --}}
    <form action="{{ route('carrito.agregarUnidad', ['producto' => $producto['id']]) }}" method="GET">
        @csrf
        <button class="btn btn-success btn-sm" type="submit">+1</button>
    </form>
@endif

                            <form action="{{ route('carrito.eliminarProducto', ['producto' => $producto['id']]) }}">
                                @csrf
                                
                                <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
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
                <button class="btn btn-danger">Vaciar carrito</button>
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