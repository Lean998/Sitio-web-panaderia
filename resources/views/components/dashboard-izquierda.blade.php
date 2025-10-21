<div class="col-lg-8">
            
            <!-- PRODUCTOS CON BAJO STOCK -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-circle color-amber"></i> Bajo Stock
                        <span class="badge bg-danger ms-2">{{ count($productosBajoStock) }}</span>
                    </h5>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseBajoStock">
                        <i class="bi bi-chevron-down color-espresso"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseBajoStock">
                    <div class="card-body bg-caramel">
                        @if($productosBajoStock->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="">
                                        <tr class="">
                                            <th>Producto</th>
                                            <th>Categoría</th>
                                            <th>Cantidad</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-caramel">
                                        @foreach($productosBajoStock as $producto)
                                            <tr class="">
                                                <td class="fw-bold">{{ $producto->nombre }}</td>
                                                <td><span class="badge bg-coffee">{{ $producto->categoria }}</span></td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="bi bi-box-seam"></i> {{ $producto->cantidad . ' ' . config('unidades.unidadMedida.'.$producto->unidad_venta) }} 
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.productos.editar.get', $producto) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-pencil-square"></i> Editar
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-check-circle bi-3x text-success mb-3"></i>
                                <p class="text-muted mb-0">Todos los productos tienen stock suficiente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ACTIVIDAD RECIENTE -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history color-amber"></i> Actividad Reciente
                    </h5>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseActividad">
                        <i class="bi bi-chevron-down color-amber"></i>
                    </button>
                </div>
                <div class="collapse show" id="collapseActividad">
                    <div class="card-body bg-caramel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-chocolate">
                                <thead class="">
                                    <tr class="">
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Actualización</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($actividadReciente as $producto)
                                        <tr>
                                            <td class="fw-bold">{{ Str::limit($producto->nombre, 30) }}</td>
                                            <td><span class="badge bg-coffee text-light">{{ $producto->categoria }}</span></td>
                                            <td class="color-espresso fw-bold">
                                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($producto->cantidad == 0)
                                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Sin stock</span>
                                                @elseif($producto->cantidad < 10)
                                                    <span class="badge bg-warning text-dark">
                                                        {{ $producto->cantidad }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        {{ $producto->cantidad }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="color-espresso small">{{ $producto->updated_at->diffForHumans() }}</td>
                                            <td>
                                                <a href="{{ route('admin.productos.editar.get', $producto) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PRODUCTOS MAS AGREGADOS A FAVORITOS -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history color-amber"></i> Favoritos de la gente
                    </h5>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseFavoritos">
                        <i class="bi bi-chevron-down color-amber"></i>
                    </button>
                </div>
                
                <div class="collapse show" id="collapseFavoritos">
                    <div class="card-body bg-caramel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-chocolate">
                                <thead class="">
                                    <tr class="">
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Veces agregado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($masFavoritos as $producto)
                                        <tr>
                                            <td class="fw-bold">{{ Str::limit($producto['producto']->nombre, 30) }}</td>
                                            <td><span class="badge bg-coffee text-light">{{ $producto['producto']->categoria }}</span></td>
                                            <td class="color-espresso fw-bold">
                                                ${{ number_format($producto['producto']->precio, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                @if($producto['producto']->cantidad == 0)
                                                    <span class="badge bg-danger"><i class="fas fa-times"></i> Sin stock</span>
                                                @elseif($producto['producto']->cantidad < 10)
                                                    <span class="badge bg-warning text-dark">
                                                        {{ $producto['producto']->cantidad }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        {{ $producto['producto']->cantidad }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="color-espresso small">
                                                {{ $producto['total_cantidad'] }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PRODUCTOS MAS VENDIDOS -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-espresso text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history color-amber"></i> Los mas vendidos
                    </h5>
                    <button class="btn btn-sm btn-light" type="button" 
                            data-bs-toggle="collapse" data-bs-target="#collapseVendidos">
                        <i class="bi bi-chevron-down color-amber"></i>
                    </button>
                </div>
                
                <div class="collapse show" id="collapseVendidos">
                    <div class="card-body bg-caramel">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 table-chocolate">
                                <thead class="">
                                    <tr class="">
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Veces vendido</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($masVendidos as $producto)
                                        <tr>
                                            <td class="fw-bold">{{ $producto->nombre }}</td>
                                            <td><span class="badge bg-coffee text-light">{{ $producto->categoria }}</span></td>
                                            <td class="color-espresso fw-bold">
                                                ${{ number_format($producto->precio, 0, ',', '.') }}
                                            </td>
                                            <td class="color-espresso small">
                                                {{ $producto->total_vendido}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>