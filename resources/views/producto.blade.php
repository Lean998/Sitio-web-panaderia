@extends('layouts.app')

@push('styles')
    @vite(['resources/css/productos.css'])
@endpush

@php
    $unidadMedida = match($producto->unidad_venta) {
        'docena' => 'docena/as',
        'media_docena' => 'media docena/as',
        'kg' => 'kg',
        default => 'unidad/es',
    };
@endphp

@section('content')
<div class="container my-5">

    <div class="row g-4 bg-caramel px-1 py-5 rounded shadow-sm">
        {{-- Imagen del producto --}}
        <div class="col-md-5 text-center">
            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="img-fluid rounded shadow-sm flex-shrink-0" style="max-height: 400px; object-fit: cover;">
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
                    @php
                        if($unidadMedida === 'kg')
                            $stock = number_format($producto->cantidad, 2, ',', '.');
                        else
                            $stock = (int) $producto->cantidad;
                    @endphp
                    <span class="fw-bold">Stock:</span> {{ $stock}} {{ $unidadMedida }}
                </p>

                <p class="fs-4 fw-bold color-chocolate mt-3">
                    ${{ number_format($producto->precio, 2, ',', '.') }}
                </p>
            </div>

            {{-- Controles --}}
            <div>
                @php
                    $carritoService = app(\App\Services\CarritoService::class);
                    $carritoModel = $carritoService->getCarritoModel();
                    $enCarrito = $carritoModel->tieneProducto($producto->id);
                    $cantidadEnCarrito = $carritoModel->getCantidadProducto($producto->id);
                    $stockDisponible = max(0, $producto->cantidad - $cantidadEnCarrito);
                    $sinStock = $stockDisponible <= 0;
                    $unidadMedida = $producto->unidad_venta === 'kg' ? 'kg' : 'unidad';
                @endphp

                <form action="{{ route('producto.extendido', ['producto' => $producto->id]) }}" method="POST" class="row gap-3">
                        @csrf
                            <div class="col-12 col-md-4">
                                @if($unidadMedida == "kg")
                                    <label for="inputCantidad" class="form-label">Cantidad (kg): </label>
                                    <input id="inputCantidad" type="number" name="cantidad" min="0.1" step="any" max="{{ $producto->cantidad - $cantidadEnCarrito }}" value="1" class="form-control d-inline w-auto me-2" style="width: 80px;">
                                @else
                                    <label for="inputCantidad" class="form-label">Cantidad: </label>
                                    <input id="inputCantidad" type="number" name="cantidad" min="1" max="{{ $producto->cantidad - $cantidadEnCarrito }}" value="1" class="form-control d-inline w-auto me-2" style="width: 80px;">
                                @endif
                            </div>
                            
                            <div class="col-12 col-md-8 d-flex flex-wrap gap-2">
                                @if ($enCarrito)
                                        <input class="btn btn-chocolate color-sand btn-aplicar {{ $sinStock ? 'disabled d-none' : '' }}" type="submit" name="agregar" value="Añadir más">
                                        </input>
                                @else
                                        <input class="btn btn-chocolate color-sand btn-aplicar {{ $sinStock ? 'disabled d-none' : '' }}" type="submit" name="agregar" value="Agregar al carrito">
                                        </input>
                                @endif

                                @if($sinStock)
                                    <p class="text-danger fw-bold mt-2 bg-sand rounded p-2">Sin stock disponible</p>
                                @endif

                                {{-- Botón COMPRAR AHORA --}}
                                <button type="submit" name="comprar" formaction="{{ route('pedido.comprar-directo', $producto->id) }}"
                                        class="btn btn-coffee btn-aplicar {{ $sinStock ? 'disabled d-none' : '' }}" 
                                        {{ $sinStock ? 'disabled' : '' }}>
                                    <i class="bi bi-lightning-charge"></i> Comprar Ahora
                                </button>   
                            </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
