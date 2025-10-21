@extends('layouts.app')

@push('styles')
    @vite(['resources/css/productos.css'])
@endpush

@section('title', 'Favoritos')

@section('content')
<div class="container" id="productos-container">
    <h1 class="text-center mt-4 color-coffee fw-bold">Lista de Favoritos</h1>

    @if (count($favoritos) === 0)
        <div class="alert bg-sand border-chocolate color-chocolate fw-semibold fs-body text-center mt-4">
            La lista de favoritos está vacía
        </div>
    @else
        <div class="row g-3 mt-4">
            @foreach ($favoritos as $producto)
                <div class="col-12">
                    <div class="producto-favoritos bg-sand border-chocolate rounded p-3 shadow-sm d-flex flex-column flex-md-row align-items-md-center gap-3">
                        {{-- Imagen del producto --}}
                        <div class="flex-shrink-0">
                            <a href="{{ route('productos.ver', ['producto' => $producto['id']]) }}">
                                <img src="{{ asset('storage/' . $producto['imagen']) }}" alt="{{ $producto['nombre'] }}" title="{{ $producto['nombre'] }}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </a>
                        </div>
                        
                        {{-- Nombre y precio --}}
                        <div class="flex-grow-1">
                            <h3 class="color-chocolate mb-2 fs-5">{{ $producto['nombre'] }}</h3>
                            <p class="mb-0 fw-bold color-coffee">Precio: ${{ number_format($producto['precio'], 2, ',', '.') }}</p>
                        </div>
                        
                        {{-- Controles de acciones --}}
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            @php
                                $carrito = session()->get('carrito', []);
                                $carritoService = app(\App\Services\CarritoService::class);
                                $carritoModel = $carritoService->getCarritoModel();
                                $enCarrito = $carritoModel->tieneProducto($producto['id']);
                            @endphp
                            @if ($enCarrito)
                                <form action="{{ route('carrito') }}" method="GET">
                                    <button class="btn btn-success btn-sm" type="submit">Ver en el Carrito</button>
                                </form>
                            @else
                                <form action="{{ route('carrito.agregar', ['producto' => $producto['id']]) }}" method="GET">
                                    @csrf
                                    <input id="inputCantidad" type="number" name="cantidad" min="1" value="1" class="form-control d-none">
                                    <button class="btn btn-success btn-sm" type="submit">Agregar al Carrito</button>
                                </form>
                            @endif
                            {{-- Botón para eliminar el producto de favoritos --}}
                            <form action="{{ route('favoritos.eliminarProducto', ['producto' => $producto['id']]) }}" method="GET">
                                @csrf
                                <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-end my-4 bg-caramel p-3 rounded">
            <form action="{{ route('favoritos.eliminarFavoritos') }}">
                @csrf
                <button class="btn btn-danger">Eliminar lista de favoritos</button>
            </form>
        </div>
    @endif
</div>
@endsection