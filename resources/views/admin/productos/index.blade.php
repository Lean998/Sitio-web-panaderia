@extends('layouts.admin.admin')

@push('styles')
    @vite(['resources/css/productos.css'])
    @vite(['resources/css/inputsYBotones.css'])
@endpush

@section('title', 'Productos')

@section('content')
    @if($categoriaKey != "Todos")
        @php $titulo = ucfirst($categoriaKey); @endphp
    @else
        @php $titulo = "Todos los productos"; @endphp
    @endif


<div class="container my-4">
    <h2 class="fw-bold text-center mb-4 color-coffee">{{$titulo}}</h2>
    <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
        <a role="button" href="{{ route('admin.productos')  }}" class="bg-chocolate color-sand btn btn-aplicar">
            Ver Todos
        </a>
        <a role="button" href="{{ route('admin.productos', ['categoria' => 'panaderia'])  }}" class="bg-chocolate color-sand btn btn-aplicar">
            Panadería
        </a>
        <a role="button" href="{{ route('admin.productos', ['categoria' => 'pasteleria'])  }}" class="bg-chocolate color-sand btn btn-aplicar">
            Pastelería
        </a>
        <a role="button" href="{{ route('admin.productos', ['categoria' =>'salados'])  }}" class="bg-chocolate color-sand btn btn-aplicar">
            Salados
        </a>
    </div>
    <!-- Barra de filtros -->
    <form method="GET" action="{{ route('admin.productos', ['categoria' => $categoriaKey])  }}"
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
</div>

<section class="container my-4" id="productos">
    <div class="row gx-4 gy-4 mt-4">
        @if ($productos->isEmpty())
            <p class="text-center color-coffee fs-h3 fw-bold">No se encontraron productos &#128546; </p>
        @endif
        @foreach($productos as $producto)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 my-4 pt-1">
                <x-producto-card 
                    :imagen="asset('storage/' . $producto->imagen)"
                    :producto="$producto"
                />
            </div>
        @endforeach
    </div>
</section>


<div class="d-flex justify-content-center mt-4" aria-label="Paginación de productos">
        {{ $productos->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>

@endsection
