<?php

use App\Http\Controllers\CarritoController;
use App\Http\Controllers\FavoritosController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\BaseController;

Route::get('/', [BaseController::class, 'index'])
->name('home');

Route::get('/productos/{categoria?}', [ProductoController::class, 'index'])
->name('productos');

Route::get('/productos/ver/{producto?}', [ProductoController::class, 'getProducto'])
->name('productos.ver');

Route::post('/productos/ver/{producto?}', [ProductoController::class, 'comprarProducto'])
->name('producto.comprar');

Route::post('/producto/extendido/{producto?}', [ProductoController::class, 'productoExtendido'])
->name('producto.extendido');

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

Route::get('/favoritos', [FavoritosController::class, 'index'])
->name('favoritos');

Route::get('/favoritos/agregar/{producto?}', [FavoritosController::class, 'agregarAFavoritos'])
->name('favoritos.agregar');

Route::get('/favoritos/eliminarProducto/{producto?}', [FavoritosController::class, 'eliminarProducto'])
->name('favoritos.eliminarProducto');

Route::get('/favoritos/eliminarFavoritos', [FavoritosController::class, 'eliminarFavoritos'])
->name('favoritos.eliminarFavoritos');