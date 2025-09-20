@extends('layouts.app')

@push('styles')
    @vite(['resources/css/productos.css'])
@endpush

@php
    $unidadMedida = match($producto->unidad_venta) {
        'docena' => 'docenas',
        'media_docena' => 'medias docenas',
        'kg' => 'kg',
        default => 'unidades',
    };
@endphp

@section('content')
<div class="container my-5">

    <div class="row g-4 bg-caramel px-1 py-5 rounded shadow-sm">
        {{-- Imagen del producto --}}
        <div class="col-md-5 text-center">
            <img src="{{ asset('images/categorias/panaderia.jpg') }}" alt="{{ $producto->nombre }}" class="img-fluid rounded shadow-sm flex-shrink-0" style="max-height: 400px; object-fit: cover;">
        </div>

        {{-- Información del producto --}}
        <div class="col-md-7 d-flex flex-column justify-content-between">
            <div>
                <h1 class="color-chocolate fw-bold mb-3">{{ $producto->nombre }}</h1>
                <p class="mb-4">{{ $producto->descripcion }}</p>

                <p class="mb-2 color-chocolate">
                    <span class="fw-bold">Categoría:</span> {{ $producto->categoria }} 
                </p>
                <p class="mb-2 color-chocolate">
                    <span class="fw-bold">Tipo:</span> {{ $producto->tipo }}
                </p>
                <p class="mb-2 color-chocolate">
                    <span class="fw-bold">Cantidad:</span> {{ number_format($producto->cantidad, 2, ',', '.') }} {{ $unidadMedida }}
                </p>

                <p class="fs-4 fw-bold color-chocolate mt-3">
                    ${{ number_format($producto->precio, 2, ',', '.') }}
                </p>
            </div>

            {{-- Controles --}}
            <div class="mt-4">
                @php
                    $carrito = session()->get('carrito', []);
                    $enCarrito = isset($carrito[$producto->id]);
                    $cantidadEnCarrito = $enCarrito ? $carrito[$producto->id]['cantidad'] : 0;
                    $sinStock = $producto->cantidad - $cantidadEnCarrito <= 0;
                @endphp

                @if ($enCarrito)
                    <form action="{{ route('carrito.agregar', ['producto' => $producto->id]) }}" method="GET" class="d-inline">
                        @csrf
                        <button class="btn bg-chocolate color-sand btn-aplicar {{ $sinStock ? 'disabled' : '' }}">
                            <i class="bi bi-cart-check-fill me-1"></i>
                            Añadir más
                        </button>
                    </form>
                @else
                    <form action="{{ route('carrito.agregar', ['producto' => $producto->id]) }}" method="GET" class="d-inline">
                        @csrf
                        <button class="btn bg-chocolate color-sand btn-aplicar {{ $sinStock ? 'disabled' : '' }}">
                            <i class="bi bi-cart me-1"></i>
                            Agregar al carrito
                        </button>
                    </form>
                @endif

                @if($sinStock)
                    <p class="text-danger mt-2">Sin stock disponible</p>
                @endif

                <form action="{{ route('producto.comprar', ['producto' => $producto->id]) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn bg-chocolate color-sand btn-aplicar {{ $sinStock ? 'disabled' : '' }}">
                            <i class="bi bi-cart me-1"></i>
                            Comprar Ahora
                        </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
