@extends('layouts.app')

@push('styles')
    @vite(['resources/css/productos.css'])
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

                <div class="col-12">
                    <div class="producto-carrito bg-sand border-chocolate rounded p-3 shadow-sm d-flex flex-wrap justify-content-between align-items-center">
                        {{-- Imagen del producto --}}
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('images/categorias/panaderia.jpg') }}" alt="{{ $producto['nombre'] }}" title="{{ $producto['nombre'] }}" class="img-fluid rounded" style="width: 130px; height: 130px; object-fit: cover;">
                            </div>
                        {{-- Nombre y precio --}}
                        <div class="me-3 flex-grow-1">
                            <h3 class="color-chocolate mb-2">{{ $producto['nombre'] }}</h3>
                            <p class="mb-0 fw-bold color-coffee">Precio unitario: ${{ number_format($producto['precio'], 2, ',', '.') }}</p>
                        </div>

                        {{-- Controles de cantidad --}}
                        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                            <span class="fw-bold fs-5">{{ $producto['cantidad'] }}</span>

                            <form action="{{ route('carrito.eliminarUnidad', ['producto' => $producto['id']]) }}">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm" type="submit">-</button>
                            </form>

                            <form action="{{ route('carrito.agregarUnidad', ['producto' => $producto['id']]) }}" >
                                @csrf
                                <button class="btn btn-outline-success btn-sm" type="submit">+</button>
                            </form>

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
@endsection