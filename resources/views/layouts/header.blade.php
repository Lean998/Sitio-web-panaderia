<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="/" class="navbar-brand d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-h1 fw-bold mx-3">El Funito</span>
            </a>

            <button class="navbar-toggler bg-sand " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="color-chocolate navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a href="/productos" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sucursal') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none">Sucursal</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('contacto.show') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('favoritos') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none">Favoritos</a>
                    </li>
                    <li class="nav-item" id="cart-link">
                        <a href="{{ route('carrito') }}" class=" py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none d-lg-block d-none">
                            <x-cart />
                        </a>
                        <a href="{{ route('carrito') }}" class="py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none d-lg-none">
                            Carrito
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>