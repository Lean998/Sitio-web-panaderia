@extends('layouts.admin.admin')

@section('title', 'Gestión de Stock')

@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('content')
<div class="container-fluid py-4">
    
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="color-espresso"><i class="bi bi-boxes"></i> Gestión Rápida de Stock</h2>
            <p class="color-chocolate">Actualiza el stock haciendo click en la cantidad</p>
        </div>
        <div class="col-md-4 text-end">
            <button type="button" class="btn btn-chocolate color-sand" id="btnGuardarCambios" style="display: none;">
                <i class="bi bi-save"></i> Guardar Todos los Cambios
            </button>
        </div>
    </div>

    <div class="container">
        <div id="mensajeStock" class="alert alert-success d-none position-fixed bottom-0 end-0 m-3 shadow"></div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm mb-4" style="border: none !important ;">
        <div class="card-body bg-sand">
            <form action="{{ route('admin.stock.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="buscar" class="form-control input-texto" placeholder="Buscar producto..." value="{{ request('buscar') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="categoria" class="form-select input-texto">
                            <option value="">Todas las categorías</option>
                            <option value="Panaderia" {{ request('categoria') == 'Panaderia' ? 'selected' : '' }}>Panadería</option>
                            <option value="Pasteleria" {{ request('categoria') == 'Pasteleria' ? 'selected' : '' }}>Pastelería</option>
                            <option value="Salados" {{ request('categoria') == 'Salados' ? 'selected' : '' }}>Salados</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="orden" class="form-select input-texto">
                            <option value="nombre" {{ request('orden') == 'nombre' ? 'selected' : '' }}>Por nombre</option>
                            <option value="cantidad" {{ request('orden') == 'cantidad' ? 'selected' : '' }}>Por stock</option>
                            <option value="precio" {{ request('orden') == 'precio' ? 'selected' : '' }}>Por precio</option>
                        </select>
                    </div>

                    <!-- Eliminar filtros -->
                    @php
                        $filtrosActivos = request()->has('categoria') || request()->has('orden') || request()->has('buscar')
                    @endphp

                    @if($filtrosActivos)
                    <div class="col-md-1" id="eliminar-filtros">
                        <a href="{{ request()->url() }}" class="btn btn-caramel color-sand w-100" style="height: calc(2.3rem + 2px)">
                            Eliminar filtros
                        </a>
                    </div>
                    @endif
                    <div class="col-md-{{ $filtrosActivos ? '3' : '4' }}" >
                        <button type="submit" class="btn btn-chocolate color-sand w-100">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
    </div>
    
    <!-- Tabla de stock -->
    <div class="card shadow-sm">
        <div class="card-body p-0 border-chocolate">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tablaStock">
                    <thead class="bg-chocolate text-white sticky-top">
                        <tr>
                            <th style="width: 5%">Id</th>
                            <th style="width: 30%">Producto</th>
                            <th style="width: 15%">Categoría</th>
                            <th style="width: 15%">Precio</th>
                            <th style="width: 15%" class="text-center">Stock Actual</th>
                            <th style="width: 10%" class="text-center">Unidad de Venta</th>
                            <th style="width: 10%" class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                            <tr data-producto-id="{{ $producto->id }}">
                                <td>{{ $producto->id }}</td>
                                <td>
                                    <strong>{{ $producto->nombre }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-coffee p-2">{{ $producto->categoria }}</span>
                                </td>
                                <td>
                                    ${{ number_format($producto->precio, 2, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <input type="number" class="form-control input-texto form-control-sm text-center stock-input" value="{{ $producto->cantidad }}" data-producto-id="{{ $producto->id }}" data-original="{{ $producto->cantidad }}" min="0" step="0.01" style="width: 100px; margin: 0 auto;">
                                </td>
                                <td class="text-center">
                                    <strong>{{ config('unidades.unidadMedida.'.$producto->unidad_venta )}}</strong>
                                </td>
                                <td class="text-center">
                                    @if($producto->cantidad == 0)
                                        <span class="badge bg-danger">Sin stock</span>
                                    @elseif($producto->cantidad < 10)
                                        <span class="badge bg-warning text-dark">Bajo</span>
                                    @else
                                        <span class="badge bg-success">OK</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($productos->hasPages())
            <div class="card-footer bg-sand">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.stock-input');
    const btnGuardar = document.getElementById('btnGuardarCambios');
    const mensaje = document.getElementById('mensajeStock');
    let cambios = {};

    function mostrarMensaje(texto, tipo = 'success') {
        mensaje.textContent = texto;
        mensaje.className = `alert alert-${tipo} text-center mx-auto mt-3`;
        mensaje.style.display = 'block';
        mensaje.style.opacity = '1';

        setTimeout(() => {
            mensaje.style.transition = 'opacity 0.5s';
            mensaje.style.opacity = '0';
            setTimeout(() => mensaje.style.display = 'none', 500);
        }, 3000);
    }

    // Detectar cambios
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            const id = this.dataset.productoId;
            const original = parseFloat(this.dataset.original);
            const nuevo = parseFloat(this.value);

            if (nuevo !== original) {
                this.classList.add('border-warning', 'border-2');
                cambios[id] = nuevo;
                btnGuardar.style.display = 'inline-block';
                btnGuardar.textContent = `Guardar ${Object.keys(cambios).length} cambio(s)`;
            } else {
                this.classList.remove('border-warning', 'border-2');
                delete cambios[id];
                if (Object.keys(cambios).length === 0) btnGuardar.style.display = 'none';
            }
        });

        // Guardar individual con Enter
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                actualizarStock(this.dataset.productoId, this.value, this, true);
            }
        });
    });

    // Guardar todos los cambios
    btnGuardar.addEventListener('click', function() {
        if (Object.keys(cambios).length === 0) return;

        const stocks = Object.entries(cambios).map(([id, cantidad]) => ({
            id: parseInt(id),
            cantidad
        }));

        fetch('{{ route("admin.stock.actualizar-multiple") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ stocks })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                mostrarMensaje(data.message);
                setTimeout(() => location.reload(), 1000);
            } else {
                mostrarMensaje('Error al guardar cambios', 'danger');
            }
        })
        .catch(() => mostrarMensaje('Error al guardar cambios', 'danger'));
    });

    // Guardar individual
    function actualizarStock(id, cantidad, input, recargar = false) {
        fetch(`/admin/stock/${id}/actualizar`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ cantidad })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                input.classList.remove('border-warning', 'border-2');
                input.classList.add('border-success', 'border-2');
                input.dataset.original = data.nueva_cantidad;

                mostrarMensaje(`Stock actualizado correctamente`);
                delete cambios[id];
                if (Object.keys(cambios).length === 0) btnGuardar.style.display = 'none';

                if (recargar) setTimeout(() => location.reload(), 1000);

                setTimeout(() => input.classList.remove('border-success', 'border-2'), 800);
            } else {
                mostrarMensaje('Error al actualizar el stock', 'danger');
            }
        })
        .catch(() => mostrarMensaje('Error al actualizar el stock', 'danger'));
    }
});
</script>

<style>
#mensajeStock {
    opacity: 0;
    transition: opacity 0.3s ease;
}


.stock-input {
    transition: all 0.3s ease;
}

.stock-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(92, 58, 33, 0.25);
}

thead.sticky-top {
    position: sticky;
    top: 90;
    z-index: 10;
}
</style>
@endsection