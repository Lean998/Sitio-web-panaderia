@extends('layouts.app')

@section('title', 'Buscar Pedido')
@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-chocolate">
                <div class="card-header bg-chocolate text-white">
                    <h4 class="mb-0"><i class="bi bi-search"></i> Buscar mi Pedido</h4>
                </div>
                <div class="card-body bg-sand">
                    <form action="{{ route('pedido.buscar') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="codigo" class="form-label color-espresso fw-semibold">
                                Código de Retiro o Código de Pedido:
                            </label>
                            <input type="text" class="form-control form-control-lg input-texto bg-cream border-chocolate text-center" id="codigo" name="codigo" placeholder="Ej: ABC12XYZ o PD-XYZ12345" style="letter-spacing: 0.2rem; font-weight: bold;" maxlength="15">
                            @error('codigo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">
                                    Por favor ingresa un código válido (mínimo 8 caracteres, maximo 20).
                                </div>
                                <div class="valid-feedback">¡Código válido!</div>
                            @enderror
                            <small class="form-text color-coffee">
                                Ingresa el código que recibiste por email
                            </small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-lg btn-chocolate color-sand">
                                <i class="bi bi-search"></i> Buscar Pedido
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="color-chocolate mb-2">¿Tienes tu correo electrónico?</p>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#misPedidosModal" class="btn btn-coffee">
                            <i class="bi bi-envelope"></i> Ver mis pedidos por Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para buscar por email -->
<div class="modal fade" id="misPedidosModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-chocolate text-white">
                <h5 class="modal-title">Buscar por Email</h5>
                <button type="button" class="btn-close bg-sand" data-bs-dismiss="modal"></button>
            </div>
            <form id="pedidosEmailForm" action="{{ route('pedido.mis-pedidos') }}" method="POST">
                @csrf
                <div class="modal-body bg-sand">
                    <div class="mb-3">
                        <label for="correo" class="form-label color-chocolate">Correo:</label>
                        <input type="email" class="form-control input-texto bg-cream border-chocolate @error('correo') is-invalid @enderror" id="correo" name="correo" placeholder="tu@email.com">
                        @error('correo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">Por favor ingresa un correo válido.</div>
                            <div class="valid-feedback">¡Correo válido!</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer bg-sand" style="border-top-color: var(--color-chocolate);">
                    <button type="button" class="btn btn-caramel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-chocolate color-sand">Buscar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pedidosEmailForm');
    const inputs = form.querySelectorAll('input');

    // Validación
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });

        input.addEventListener('blur', function() {
            validateField(this);
        });
    });

    function validateField(field) {
        const value = field.value.trim();
        let isValid = false;

        // Limpiar clases previas
        field.classList.remove('is-valid', 'is-invalid');

        if (field.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        } 

        // Aplicar clases de validación
        if (value.length > 0) {
            field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        }

        return isValid;
    }
});
</script>
@endsection