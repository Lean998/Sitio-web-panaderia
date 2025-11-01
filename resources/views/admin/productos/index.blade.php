@extends('layouts.admin.admin')

@push('styles')
    @vite(['resources/css/productos.css', 'resources/css/inputsYBotones.css'])
    <style>
        @media (max-width: 576px) {
            .btn-aplicar {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
            }
            .form-control, .form-select {
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('title', 'Productos')

@section('content')
    @if($categoriaKey != "Todos")
        @php $titulo = ucfirst($categoriaKey); @endphp
    @else
        @php $titulo = "Todos los productos"; @endphp
    @endif

<div class="container my-4" aria-label="Gestión de Productos">
    <h1 class="h2 fw-bold text-center mb-3 mb-md-4 color-coffee">{{ $titulo }}</h1>

    <!-- Navegacion por categorias -->
    <nav class="d-flex justify-content-center flex-wrap gap-2 gap-md-3 mb-3 mb-md-4" aria-label="Filtro por categoría">
        <a role="button" href="{{ route('admin.productos') }}" class="bg-chocolate color-sand btn btn-aplicar px-3 py-2 {{ $categoriaKey == 'Todos' ? 'active' : '' }}" aria-current="{{ $categoriaKey == 'Todos' ? 'page' : '' }}">Ver Todos</a>
        <a role="button" href="{{ route('admin.productos', ['categoria' => 'panaderia']) }}" class="bg-chocolate color-sand btn btn-aplicar px-3 py-2 {{ $categoriaKey == 'panaderia' ? 'active' : '' }}" aria-current="{{ $categoriaKey == 'panaderia' ? 'page' : '' }}">Panadería</a>
        <a role="button" href="{{ route('admin.productos', ['categoria' => 'pasteleria']) }}" class="bg-chocolate color-sand btn btn-aplicar px-3 py-2 {{ $categoriaKey == 'pasteleria' ? 'active' : '' }}" aria-current="{{ $categoriaKey == 'pasteleria' ? 'page' : '' }}">Pastelería</a>
        <a role="button" href="{{ route('admin.productos', ['categoria' => 'salados']) }}" class="bg-chocolate color-sand btn btn-aplicar px-3 py-2 {{ $categoriaKey == 'salados' ? 'active' : '' }}" aria-current="{{ $categoriaKey == 'salados' ? 'page' : '' }}">Salados</a>
    </nav>

    <!-- Barra de filtros -->
     <form method="GET" action="{{ route('productos', ['categoria' => $categoriaKey])  }}"
      class="row g-3 justify-content-center align-items-center">

  <!-- Buscar -->
  <div class="col-auto">
    <div class="input-group" id="buscador">
      <input type="text" name="buscar" class="form-control bg-sand border-chocolate color-chocolate input-texto" placeholder="Buscar..." value="{{ request('buscar') }}">
      <button type="submit" name="action" value="aplicar" class="btn border-chocolate btn-buscar">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search color-coffee" viewBox="0 0 16 16"> <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/> </svg>
      </button>
    </div>
  </div>

  <!-- Tipo -->
  <div class="col-auto" id="tipo">
    <select name="tipo" class="form-select bg-sand border-chocolate color-chocolate">
      <option value="" disabled {{ request()->filled('tipo') ? '' : 'selected' }}>Tipo</option>
      @if(isset($tipos[$categoriaKey]))
        @foreach ($tipos[$categoriaKey] as $tipo)
          <option value="{{ strtolower($tipo) }}" {{ request('tipo') === strtolower($tipo) ? 'selected' : '' }}>
            {{ $tipo }}
          </option>
        @endforeach
      @endif
    </select>
  </div>

  <!-- Ordenar -->
  <div class="col-auto" id="ordenar">
    <select name="orden" class="form-select bg-sand border-chocolate color-chocolate">
      <option value="" disabled {{ request()->filled('orden') ? '' : 'selected' }}>Ordenar</option>
      <option value="asc"  {{ request('orden')==='asc'  ? 'selected':'' }}>A - Z</option>
      <option value="desc" {{ request('orden')==='desc' ? 'selected':'' }}>Z - A</option>
      <option value="menorPrecio" {{ request('orden')==='menorPrecio' ? 'selected':'' }}>Menor precio</option>
      <option value="mayorPrecio" {{ request('orden')==='mayorPrecio' ? 'selected':'' }}>Mayor precio</option>
    </select>
  </div>
  

  <!-- Aplicar -->
  <div class="col-auto" id="aplicar">
    <button type="submit" name="action" value="aplicar" class="btn btn-aplicar bg-chocolate color-sand">Aplicar</button>
  </div>

  <!-- Eliminar filtros (link sin tipo/orden) -->
  <div class="col-auto" id="eliminar-filtros">
    <a class="btn btn-aplicar bg-chocolate color-sand"
       href="{{ url()->current() }}@php
         $qs = http_build_query(request()->except(['tipo','orden','page']));
         echo $qs ? ('?'.$qs) : '';
       @endphp">
      Eliminar filtros
    </a>
  </div>
</form>

    <!-- Productos -->
    <section class="container my-4" id="productos" aria-labelledby="productos-title">
        <h2 id="productos-title" class="visually-hidden">Lista de Productos</h2>
        <div class="row gx-3 gx-md-4 gy-4 mt-4">
            @if ($productos->isEmpty())
                <figure class="text-center">
                    <figcaption class="color-coffee fs-h3 fw-bold">No se encontraron productos &#128546;</figcaption>
                </figure>
            @endif
            @foreach($productos as $producto)
                <article class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <x-producto-card 
                        :imagen="asset('storage/' . $producto->imagen)"
                        :producto="$producto"
                    />
                </article>
            @endforeach
        </div>
    </section>

    <!-- Paginación -->
    <nav class="d-flex justify-content-center mt-4" aria-label="Paginación de productos">
        {{ $productos->onEachSide(1)->links('pagination::bootstrap-5') }}
    </nav>
</div>
@endsection