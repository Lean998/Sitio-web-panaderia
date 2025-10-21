@extends('layouts.admin.admin')

@push('styles')
    @vite(['resources/css/productos.css', 'resources/css/inputsYBotones.css'])
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
                    <span class="fw-bold">Stock:</span> {{ $stock}} {{ config('unidades.unidadMedida.'.$producto->unidad_venta) }}
                </p>

                <p class="fs-4 fw-bold color-chocolate mt-3">
                    ${{ number_format($producto->precio, 2, ',', '.') }}
                </p>
            </div>

            {{-- Controles --}}
            <div>
                <a href="{{ route('admin.productos.editar.get', $producto) }}" class="btn btn-chocolate bg-chocolate color-sand">
                    <i class="bi bi-pencil-square"></i> Editar
                </a>
                
                <button href="{{ route('admin.productos.eliminar', $producto) }}" type="button" class="btn btn-danger color-sand" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade mt-5" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog mt-5">
        <div class="modal-content">
            <div class="modal-header bg-espresso color-cream border-0">
                <h1 class="modal-title fs-5" id="confirmDeleteModalLabel">Confirmar eliminar</h1>
                <button type="button" class="btn-close bg-sand" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-coffee color-espresso border-0 fs-primary">
                <form action="{{ route('admin.productos.eliminar') }}" method="POST" id="deleteProductForm" class="d-none"> 
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                </form>
                ¿Estás seguro de que deseas eliminar este producto? <br>Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer bg-coffee border-0">
                <button type="button" class="btn btn-espresso" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-chocolate" form="deleteProductForm">Confirmar</button>
                
            </div>
        </div>
    </div>
</div>
@endsection
