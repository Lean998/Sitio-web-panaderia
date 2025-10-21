@extends('layouts.admin.admin')

@section('title', 'Iniciar Sesión - Admin')

@push('styles')
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('content')
    @if ($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                <p class="fw-semibold">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
@endif

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-cream py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body bg-caramel rounded shadow-inherit border-chocolate p-4 p-sm-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="" class="bi bi-shield-lock text-primary" viewBox="0 0 16 16">
                                    <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z"/>
                                    <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z"/>
                                </svg>
                            </div>
                            <h3 class="fw-bold mb-2 color-chocolate">Iniciar Sesión</h3>
                            <p class="mb-0 text-chocolate">Accede al panel de administración</p>
                        </div>

                        <!-- Form -->
                        <form method="POST" action="{{ route('admin.login.post') }}" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold color-chocolate">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope me-2" viewBox="0 0 16 16">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
                                    </svg>
                                    Email
                                </label>
                                <input type="email" class="form-control form-control-lg color-chocolate input-texto" id="email" name="email" required placeholder="tu@ejemplo.com">
                                <div class="invalid-feedback">
                                    Por favor ingresa un email válido.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold color-chocolate">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key me-2" viewBox="0 0 16 16">
                                        <path d="M0 8a4 4 0 0 1 7.465-2H14a.5.5 0 0 1 .354.146l1.5 1.5a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0L13 9.207l-.646.647a.5.5 0 0 1-.708 0L11 9.207l-.646.647a.5.5 0 0 1-.708 0L9 9.207l-.646.647A.5.5 0 0 1 8 10h-.535A4 4 0 0 1 0 8zm4-3a3 3 0 1 0 2.712 4.285A.5.5 0 0 1 7.163 9h.63l.853-.854a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.793-.793-1-1h-6.63a.5.5 0 0 1-.451-.285A3 3 0 0 0 4 5z"/>
                                        <path d="M4 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                                    </svg>
                                    Contraseña
                                </label>
                                <input type="password" class="form-control form-control-lg color-chocolate input-texto" id="password" name="password" required placeholder="••••••••">
                                <div class="invalid-feedback">
                                    Por favor ingresa tu contraseña.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn bg-chocolate color-sand btn-lg py-3 fw-semibold btn-aplicar">
                                    Iniciar Sesión
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.btn-primary {
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
}
</style>

<script>
// Validación de Bootstrap
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
@endsection