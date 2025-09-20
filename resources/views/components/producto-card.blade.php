@props(['imagen', 'producto'])
<a href="{{ route('productos.ver', ['producto' => $producto->id]) }}" class="text-decoration-none d-block">
<div class="product-card d-flex flex-column shadow rounded" style="width: 100%;">
    <img src="{{ $imagen }}" alt="{{ $producto->nombre }}" class="product-img" title="{{ $producto->nombre  }}">
    <div class="d-flex flex-column justify-content-between bg-caramel h-100 px-3">
        <div class="color-chocolate mt-1">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <h5 class="fw-semibold text-truncate" title="{{ $producto->nombre  }}">{{ $producto->nombre  }}</h5>
                <p class="fw-semibold fs-5" aria-label="precio">${{ $producto->precio }}</p>
            </div>
            <p class="text-truncate mb-2" title="{{ $producto->descripcion }}">{{ $producto->descripcion }}</p>
        </div>

    <div class="row g-2 my-2 align-items-center no-gutters mt-auto">
        <div class="col-auto">
            @php
                // Verificar si el producto ya está en favoritos
                $favoritos = session()->get('favoritos', []);
                $enFavoritos = isset($favoritos[$producto->id]);
                if($enFavoritos){
                    $ruta = route("favoritos.eliminarProducto", ['producto' => $producto->id]);
                } else {
                    $ruta = route("favoritos.agregar", ['producto' => $producto->id]);
                }
            @endphp
            <form action="{{ $ruta }} " method="GET">
                @csrf
                <button class="btn bg-chocolate color-sand btn-small" aria-label="Agregar a favoritos">
                    @if($enFavoritos)
                    <x-heart-fill class="color-sand" />
                    @else
                    <x-heart class="color-sand" />
                    @endif
                </button>
            </form>
        </div>
        <div class="col-auto">
            @php
                // Verificar si el producto ya está en el carrito
                $carrito = session()->get('carrito', []);
                $enCarrito = isset($carrito[$producto->id]);
                $cantidadEnCarrito = 0;
                if($enCarrito){
                    $cantidadEnCarrito = $carrito[$producto->id]['cantidad'];
                }
                $sinStock = $producto->cantidad - $cantidadEnCarrito <= 0;
            @endphp
            <form action="{{ route("carrito.agregar", ['producto' => $producto->id]) }}" method="GET">
                @csrf
                <button class="btn bg-chocolate color-sand btn-small" {{ $sinStock ? 'disabled' : '' }} title="{{ $sinStock ? 'Sin stock disponible' : 'Agregar al carrito' }}" aria-label="Agregar al carrito">
                    @if($enCarrito)
                        <x-cart-check /> 
                    @else
                        <x-cart />
                    @endif
                </button>        
            </form>
        </div>
        <div class="col" style="min-width: 0;">
            <button class="btn bg-chocolate d-flex justify-content-center align-items-center color-sand fw-semibold btn-buy-responsive w-100">Comprar</button>
        </div>
    </div>
        </div>
</div>
</a>