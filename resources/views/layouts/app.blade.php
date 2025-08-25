<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'El funito')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="background">
    {{-- Incluir header --}}
    @include('layouts.header')

    <main>
        @yield('content')
    </main>
    
    {{-- Incluir footer --}}
    @include('layouts.footer')
</body>
</html>