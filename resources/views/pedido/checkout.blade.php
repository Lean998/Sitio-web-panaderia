@extends('layouts.app')

@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('title', 'Checkout')

@section('content')


<div class="container py-5">
    <div class="row justify-content-center">
        
        {{-- Columna izquierda: Resumen del carrito --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-chocolate">
                <div class="card-header bg-chocolate color-cream">
                    <h4 class="mb-0"><i class="bi bi-basket"></i> Resumen del Pedido</h4>
                </div>
                <div class="card-body bg-sand">
                    <div class="table-responsive">
                        <table class="table table-hover mb-3">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Precio</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carrito as $producto)
                                    @php
                                        $unidadMedida = match($producto['unidad_venta']) {
                                            'docena' => 'doc',
                                            'media_docena' => '½ doc',
                                            'kg' => 'kg',
                                            default => 'u',
                                        };
                                        $subtotal = $producto['precio'] * $producto['cantidad'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ Str::limit($producto['nombre'], 30) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            {{ $producto['cantidad'] }} {{ $unidadMedida }}
                                        </td>
                                        <td class="text-end color-coffee fw-bold">
                                            ${{ number_format($producto['precio'], 0, ',', '.') }}
                                        </td>
                                        <td class="text-end color-espresso">
                                            ${{ number_format($subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="3" class="text-end">
                                        <strong class="fs-5">Total:</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong class="fs-4 color-chocolate">
                                            ${{ number_format($totales['subtotal'], 0, ',', '.') }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="alert bg-espresso color-amber mb-0">
                        <i class="bi bi-info-circle"></i>
                        <small>
                            <strong>Nota:</strong> El precio final puede variar según el método de pago seleccionado.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Formulario de datos --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-chocolate bg-sand">
                <div class="card-header bg-chocolate color-cream">
                    <h4 class="mb-0"><i class="bi bi-user"></i> Datos del Comprador</h4>
                </div>
                <div class="card-body bg-cream">
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" id="">
                            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                        <div class="alert alert-danger alert-dismissible fade show d-none"  id="checkout-error">
                            <i class="bi bi-exclamation-circle"></i>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                    <form id="checkoutForm" action="{{ route('pedido.crear') }}" method="POST" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label color-coffee fw-semibold">
                                Nombre Completo: <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control input-texto bg-sand border-chocolate color-cream @error('nombre') is-invalid @enderror" 
                                id="nombre" 
                                name="nombre"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Juan Pérez"
                                required
                                minlength="3"
                                maxlength="100"
                            >
                            @error('nombre')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">
                                    Por favor ingresa tu nombre completo (mínimo 3 caracteres).
                                </div>
                                <div class="valid-feedback">¡Nombre válido!</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="correo" class="form-label color-coffee fw-semibold">
                                Correo Electrónico: <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control input-texto bg-sand border-chocolate color-cream @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" placeholder="ejemplo@email.com" required>
                            @error('correo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">
                                    Por favor ingresa un correo válido.
                                </div>
                                <div class="valid-feedback">¡Correo válido!</div>
                            @enderror
                            <small class="form-text color-espresso">
                                Recibirás el código de retiro en este correo.
                            </small>
                        </div>

                        <div class="alert alert-warning color-espresso">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Importante:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Verifica que tus datos sean correctos</li>
                                <li>Guarda el código de retiro que recibirás</li>
                                <li>El pedido estará listo en 30-60 minutos</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg bg-chocolate color-cream btn-chocolate">
                                <i class="bi bi-arrow-right"></i> Continuar al Pago
                            </button>
                            <a href="{{ route('carrito') }}" class="btn btn-coffee border-chocolate color-cream">
                                <i class="bi bi-arrow-left"></i> Volver al Carrito
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const inputs = form.querySelectorAll('input[required]');

    // Validación en tiempo real
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });

        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    // Validar al enviar
    form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        if (!isValid) {
            e.preventDefault();
            const errEl = document.getElementById('checkout-error');
            if (errEl) {
                errEl.textContent = 'Por favor completa todos los campos correctamente';
                errEl.classList.remove('d-none');
            }
            return;
        } 
        
            errEl.classList.add('d-none');
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = false;

        // Limpiar clases previas
        field.classList.remove('is-valid', 'is-invalid');

        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        } else if (field.name === 'nombre') {
            isValid = value.length >= 3 && value.length <= 100;
        }

        
        if (value.length > 0) {
            field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        }

        return isValid;
    }
});
</script>
@endsection