<header>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-h1 fw-bold mx-3">El Funito</span>
            </a>

            <button class="navbar-toggler bg-sand " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="color-chocolate navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (session()->get('admin_role') === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.productos') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page">Productos</a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page">Dashboard</a>
                    </li>
                    @endif

                    <li class="nav-item">
                        <a href="{{ route('admin.pedidos.index') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page"> Pedidos </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.stock.index') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page">Gestión de Stock</a>
                    </li>
                    @if (session()->get('admin_role') === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.pedidos.estadisticas') }}" class="border-sand py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page"> Estadísticas </a>
                    </li>
                    @endif
                    <li>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('formEliminar').submit();" class="py-3 px-4 fw-semibold fs-h3 nav-link text-reset text-decoration-none active" aria-current="page"> Cerrar Sesion </a>
                        <form id="formEliminar" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>