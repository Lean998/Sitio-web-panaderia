<header class="d-flex flex-wrap justify-content-center">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-h1 fw-bold mx-3">El Funito</span>
    </a>

    <ul class="nav">
        <li class="nav-item"><a href="/productos" class="border-sand  py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page">Productos</a></li>
        <li class="nav-item"><a href="#" class="border-sand  py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none">Sucursal</a></li>
        <li class="nav-item"><a href="#" class="border-sand  py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none">Contacto</a></li>
        <li class="nav-item"><a href="{{ route('favoritos') }}" class="border-sand  py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none">Favoritos</a></li>
        <li class="nav-item" id="cart-link">
            <a href="{{ route('carrito') }}" class="nav-link py-3 px-4">
                <x-cart 
                />
            </a>
        </li>
    </ul>
</header>