@extends('layouts.app')

@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('title', 'Contacto')

    
@section('content')
<div class="container bg-cream border-2 rounded shadow-sm border-chocolate mt-5">
    <h1 class="text-center color-coffee">Contacto</h1>
    <p class="color-chocolate fw-bold fs-primary ">Puedes contactarnos con nosotros a través del siguiente formulario:</p>


<form id="contactForm" action="{{ route('contacto.submit') }}" method="POST" novalidate>
    @csrf
    
    <!-- Campo Nombre -->
    <div class="mb-3">
        <label for="nombre" class="form-label color-coffee fw-semibold">
            Nombre: <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            class="form-control input-texto bg-sand border-chocolate color-cream @error('nombre') is-invalid @enderror" 
            id="nombre" 
            name="nombre" 
            value="{{ old('nombre') }}"
            required
        >
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Por favor ingresa tu nombre.</div>
            <div class="valid-feedback">¡Se ve bien!</div>
        @enderror
    </div>

    <!-- Campo Email -->
    <div class="mb-3">
        <label for="email" class="form-label color-coffee fw-semibold">
            Email: <span class="text-danger">*</span>
        </label>
        <input type="email" class="form-control input-texto bg-sand border-chocolate color-cream @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Por favor ingresa un email válido.</div>
            <div class="valid-feedback">¡Email válido!</div>
        @enderror
    </div>

    <!-- Campo Mensaje -->
    <div class="mb-4">
        <label for="mensaje" class="form-label color-coffee fw-semibold">
            Mensaje: <span class="text-danger">*</span>
        </label>
        <textarea class="form-control input-texto bg-sand border-chocolate color-cream @error('mensaje') is-invalid @enderror" id="mensaje" name="mensaje" rows="4" required>{{ old('mensaje') }}</textarea>
        @error('mensaje')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">El mensaje debe tener al menos 10 caracteres.</div>
            <div class="valid-feedback">¡Mensaje perfecto!</div>
        @enderror
    </div>

    <!-- Botón enviar -->
    <div class="text-end">
        <button type="submit" class="btn btn-aplicar bg-chocolate color-sand mb-2">
            Enviar Mensaje
        </button>
    </div>
</form>
</div>

<div id="atencion" class="container d-flex flex-column justify-content-center align-items-center g-2 bg-chocolate color-sand mt-5 mb-3 rounded shadow-sm border-chocolate p-4">
    <h3 class="text-center mb-4 fw-bold">¡Horarios de atención!</h3>

    <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-5 w-100">
        <div class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clock-fill me-2" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z"/>
            </svg>
            <p class="mb-0 fw-semibold text-center">Martes a Sábado de 08:00 a 14:00 y de 17:00 a 21:00</p>
        </div>

        <div class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-calendar-fill me-2" viewBox="0 0 16 16">
                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5h16V4H0V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5"/>
            </svg>
            <p class="mb-0 fw-semibold text-center">Domingo de 08:00 a 14:00</p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const inputs = form.querySelectorAll('input, textarea');

    // Validación en tiempo real
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
        } else if (field.name === 'mensaje') {
            isValid = value.length >= 10;
        } else {
            isValid = value.length >= 2;
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