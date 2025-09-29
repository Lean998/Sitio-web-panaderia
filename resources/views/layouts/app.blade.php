<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'El funito')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
<body class="background">
    {{-- Incluir header --}}
    @include('layouts.header')
        @if (session('success'))
            <div class="container mt-3 align-items-center">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="fw-semibold">{{ session('success') }} </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @elseif (session('warning'))
            <div class="container mt-3 align-items-center">
                <div class="alert light alert-dismissible show border-chocolate" role="alert" style="background-color: #F9D976;">
                    <p class="fw-semibold">{{ session('warning') }} </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @elseif (session('error'))
            <div class="container mt-3 align-items-center">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="fw-semibold">{{ session('error') }} </p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
    <main>
        @yield('content')
    </main>
    
    {{-- Incluir footer --}}
    @include('layouts.footer')
</body>
</html>