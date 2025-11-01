
@extends('layouts.app')

@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        
        {{-- Columna izquierda: Producto --}}
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-chocolate">
                <div class="card-header bg-chocolate color-cream">
                    <h4 class="mb-0"><i class="bi bi-bag-check"></i> Tu Compra</h4>
                </div>
                <div class="card-body bg-sand">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" class="img-fluid rounded" alt="{{ $producto->nombre }}">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>{{ $producto->nombre }}</h5>
                            <p class="text-muted mb-1">{{ $producto->descripcion }}</p>
                            <p class="mb-0">
                                <strong>Cantidad:</strong> {{ $cantidad }} {{ $producto->unidad_venta }}
                            </p>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="mb-2">
                                <small class="text-muted">Precio unitario:</small><br>
                                <strong class="color-chocolate">${{ number_format($producto->precio, 2, ',', '.') }}</strong>
                            </div>
                            <div>
                                <small class="text-muted">Subtotal:</small><br>
                                <h4 class="color-chocolate mb-0">
                                    ${{ number_format($subtotal, 2, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="alert bg-espresso color-amber mb-0">
                        <i class="bi bi-info-circle"></i>
                        <small>
                            <strong>Nota:</strong> El precio final puede variar según el método de pago.
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Formulario --}}
        <div class="col-lg-5">
            <div class="card shadow-sm border-chocolate">
                <div class="card-header bg-chocolate color-cream">
                    <h4 class="mb-0"><i class="bi bi-person"></i> Tus Datos</h4>
                </div>
                <div class="card-body bg-sand">
                    <form action="{{ route('pedido.crear-directo') }}" method="POST" id="checkoutForm">
                        @csrf
                        <div class="mb-3">
                            <label for="nombre" class="form-label color-coffee fw-semibold">
                                Nombre Completo: <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" class="form-control input-texto bg-sand border-chocolate color-cream @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Juan Pérez"  minlength="3" maxlength="100">
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
                            <input type="email" class="form-control input-texto bg-sand border-chocolate color-cream @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}" placeholder="ejemplo@email.com" >
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-lg btn-chocolate" style="color:var(--color-cream);">
                                <i class="bi bi-arrow-right"></i> Continuar al Pago
                            </button>
                            <a href="{{ route('productos.ver', [session()->get('compra_directa')['producto_id']]) }}" class="btn btn-coffee" style="color:var(--color-cream);">
                                <i class="bi bi-arrow-left"></i> Volver
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