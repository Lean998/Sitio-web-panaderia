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
                    <div class="producto-favoritos bg-sand border-chocolate rounded p-3 shadow-sm d-flex flex-wrap justify-content-between align-items-center">
                        {{-- Imagen del producto --}}
                            <div class="flex-shrink-0 me-3">
                                <img src="{{ asset('images/categorias/panaderia.jpg') }}" alt="{{ $producto['nombre'] }}" title="{{ $producto['nombre'] }}" class="img-fluid rounded" style="width: 130px; height: 130px; object-fit: cover;">
                            </div>
                        {{-- Nombre y precio --}}
                        <div class="me-3 flex-grow-1">
                            <h3 class="color-chocolate mb-2">{{ $producto['nombre'] }}</h3>
                            <p class="mb-0 fw-bold color-coffee">Precio: ${{ number_format($producto['precio'], 2, ',', '.') }}</p>
                        </div>

                        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
                            {{-- Botón para agregar el producto de la lista de compras al carrito --}}
                            <form action="{{ route('carrito.agregar', ['producto' => $producto['id']]) }}">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit">Agregar al Carrito</button>
                            </form>
                            {{-- Botón para eliminar el producto de favoritos --}}
                            <form action="{{ route('favoritos.eliminarProducto', ['producto' => $producto['id']]) }}">
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