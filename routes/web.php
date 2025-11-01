<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\Admin\AuthController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PedidoDirectoController;
use App\Http\Controllers\Admin\AdminPedidoController;
use App\Http\Controllers\Admin\AdminStockController;

// Rutas Home y otras páginas principales
Route::get('/', [BaseController::class, 'index'])
->name('home');

Route::get('/sucursal', [BaseController::class, 'sucursal'])
->name('sucursal');

Route::post('/contacto', [ContactoController::class, 'submit'])
->name('contacto.submit');

Route::get('/contacto', [ContactoController::class, 'show'])
->name('contacto.show');

// Rutas de productos
Route::get('/productos/{categoria?}', [ProductoController::class, 'index'])
->name('productos');

Route::get('/productos/ver/{producto?}', [ProductoController::class, 'getProducto'])
->name('productos.ver');

Route::post('/productos/ver/{producto?}', [ProductoController::class, 'agregarYComprar'])
->name('producto.comprar');

Route::post('/producto/extendido/{producto?}', [ProductoController::class, 'agregarYComprar'])
->name('producto.extendido');


// Rutas de carrito
Route::get('/carrito', [CarritoController::class, 'index'])
->name('carrito');

Route::get('/carrito/agregar/{producto?}/{cantidad?}', [CarritoController::class, 'agregarAlCarrito'])
->name('carrito.agregar');

Route::get('/carrito/eliminarCarrito', [CarritoController::class, 'eliminarCarrito'])
->name('carrito.eliminarCarrito');

Route::get('/carrito/eliminarUnidad/{producto?}', [CarritoController::class, 'eliminarUnidad'])
->name('carrito.eliminarUnidad');

Route::get('/carrito/eliminarProducto/{producto?}', [CarritoController::class, 'eliminarProducto'])
->name('carrito.eliminarProducto');

Route::get('/carrito/agregarUnidad/{producto?}', [CarritoController::class, 'agregarUnidad'])
->name('carrito.agregarUnidad');

// Rutas de favoritos

Route::get('/favoritos', [FavoritosController::class, 'index'])
->name('favoritos');

Route::get('/favoritos/agregar/{producto?}', [FavoritosController::class, 'agregarAFavoritos'])
->name('favoritos.agregar');

Route::get('/favoritos/eliminarProducto/{producto?}', [FavoritosController::class, 'eliminarProducto'])
->name('favoritos.eliminarProducto');

Route::get('/favoritos/eliminarFavoritos', [FavoritosController::class, 'eliminarFavoritos'])
->name('favoritos.eliminarFavoritos');

// Límite de intentos de login para administradores
RateLimiter::for('admin-login', function (Request $request) {
    return Limit::perMinute(3)->by($request->ip());
});

// Rutas públicas de pedidos
Route::prefix('pedido')->name('pedido.')->group(function () {
    
    // Checkout y creación
    Route::get('/checkout', [PedidoController::class, 'checkout'])
    ->name('checkout');

    Route::get('/checkout-directo', [PedidoDirectoController::class, 'checkoutDirecto'])
    ->name('checkout-directo');

    Route::post('/comprar-directo/{productoId}', [PedidoDirectoController::class, 'comprarDirecto'])
    ->name('comprar-directo');
    
    Route::post('/crear', [PedidoController::class, 'crear'])
    ->name('crear');

    Route::post('/pedido/crear-directo', [PedidoDirectoController::class, 'crearPedidoDirecto'])
    ->name('crear-directo');
    
    // Pago
    Route::get('/{pedido}/pago', [PedidoController::class, 'pago'])
    ->name('pago');

    Route::post('/{pedido}/procesar-pago', [PedidoController::class, 'procesarPago'])
    ->name('procesar-pago');
    
    // Confirmación y seguimiento
    Route::get('/{pedido}/confirmacion', [PedidoController::class, 'confirmacion'])
    ->name('confirmacion');

    Route::get('/{pedido}/detalle', [PedidoController::class, 'detalle'])
    ->name('detalle');

    Route::get('/comprobante/{id}', [PedidoController::class, 'descargarComprobante'])
    ->name('comprobante');

    
    // Buscar pedido
    Route::get('/buscar', [PedidoController::class, 'mostrarBuscar'])
    ->name('mostrar-buscar');
    
    Route::post('/buscar', [PedidoController::class, 'buscar'])
    ->name('buscar');

    Route::post('/mis-pedidos', [PedidoController::class, 'misPedidos'])
    ->name('mis-pedidos');

    // Calcular recargo (AJAX)
    Route::post('/{pedido}/calcular-recargo', [PedidoController::class, 'calcularRecargo'])
    ->name('calcular-recargo');
});


// Login de administradores
Route::get('admin/acceso', [AuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('admin/acceso', [AuthController::class, 'login'])
    ->middleware('throttle:admin-login')
    ->name('admin.login.post');

// Rutas protegidas para administradores
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['admin', 'cache.headers:no_cache,no_store,must_revalidate'])
    ->group(function () {

        Route::middleware('admin')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('/', [DashboardController::class, 'index'])->name('index');
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

            Route::get('/usuarios/crear', [AdminController::class, 'create'])
            ->name('usuarios.create');

            Route::post('/usuarios', [AdminController::class, 'store'])
            ->name('usuarios.store');

            // Productos (crear, editar, eliminar)
            Route::get('/productos/crear', [ProductoController::class, 'crearProducto'])
            ->name('productos.crear.get');
            
            Route::post('/productos/crear', [ProductoController::class, 'postCrearProducto'])
            ->name('productos.crear');
            
            Route::delete('/productos/eliminar', [ProductoController::class, 'eliminarProducto'])
            ->name('productos.eliminar');
            
            Route::put('/productos/editar', [ProductoController::class, 'editarProducto'])
            ->name('productos.editar');
            
            Route::get('/productos/editar/{producto?}', [ProductoController::class, 'getEditarProducto'])
            ->name('productos.editar.get');
            
            Route::get('/productos/{categoria?}', [AdminController::class, 'productos'])
            ->name('productos');
            
            Route::get('/productos/ver/{producto?}', [ProductoController::class, 'getProducto'])
            ->name('productos.ver');
        });

        Route::middleware('can.manage.stock.orders')->group(function () {
            // Stock
            Route::prefix('stock')->name('stock.')->group(function () {
                Route::get('/', [AdminStockController::class, 'index'])
                ->name('index');

                Route::put('/{producto}/actualizar', [AdminStockController::class, 'actualizarStock']
                )->name('actualizar');

                Route::post('/actualizar-multiple', [AdminStockController::class, 'actualizarMultiple'])
                ->name('actualizar-multiple');
            });

            // Pedidos
            Route::prefix('pedidos')->name('pedidos.')->group(function () {
                Route::get('/', [AdminPedidoController::class, 'index'])
                ->name('index');

                Route::get('/{pedido}', [AdminPedidoController::class, 'show'])
                ->name('show');

                Route::put('/{pedido}/cambiar-estado', [AdminPedidoController::class, 'cambiarEstado'])
                ->name('cambiar-estado');

                Route::delete('/{pedido}/cancelar', [AdminPedidoController::class, 'cancelar'])
                ->name('cancelar');

                Route::get('/estadisticas/ventas', [AdminPedidoController::class, 'estadisticas'])
                ->name('estadisticas');
            });
        });
    });
    