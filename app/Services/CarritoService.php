<?php

namespace App\Services;
use App\Exceptions\StockInsuficienteException;
use App\Exceptions\ProductoEliminadoException;
use App\Exceptions\ProductoNoEncontradoException;
use App\Models\Carrito;
use App\Models\Producto;
use Exception;
class CarritoService
{
    public function __construct(){
        
    }

    public function getCarrito(): array{
        return session()->get('carrito', Carrito::getCarrito(session()->getId()));
    }
    public function agregarUnidad(Producto $producto, float $cantidad = 1){

        $carrito = session()->get('carrito', []);
        $productoId = $producto->id;

        $cantidadEnCarrito = $carrito[$productoId]['cantidad'] ?? 0;
        
        $total = round($cantidadEnCarrito + $cantidad, 2);
        $stock = round($producto->cantidad, 2);

        if ($total > $stock) {
            throw new StockInsuficienteException("No hay stock disponible para agregar {$cantidad} de {$producto->nombre}");
        }

        $carrito[$productoId] = [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'cantidad' => $total,
            'unidad_venta' => $producto->unidad_venta,
            'precio' => $producto->precio,
            'imagen' => $producto->imagen
        ];

        $this->actualizarSesionYDB($carrito);
    }
    public function eliminarUnidad(Producto $producto, float $cantidad = 1): void{
        $carrito = session()->get('carrito', []);
        $productoId = $producto->id;

        if (!isset($carrito[$productoId])) {
            throw new ProductoNoEncontradoException("El producto {$producto->nombre} no está en el carrito");
        }

        $cantidadEnCarrito = $carrito[$productoId]['cantidad'] ?? 0;
        $total = round($cantidadEnCarrito - $cantidad, 2);

        if ($total <= 0) {
            unset($carrito[$productoId]);
            $this->actualizarSesionYDB($carrito);
            throw new ProductoEliminadoException("Se elimino {$producto->nombre} del carrito de compras");
        } else {
            $carrito[$productoId]['cantidad'] = $total;
        }

        $this->actualizarSesionYDB($carrito);
    }
    protected function actualizarSesionYDB(array $carrito): void{
        session()->put('carrito', $carrito);
        Carrito::actualizarCarrito(session()->getId(), $carrito);
    }
    public function vaciarCarrito(): void{
        session()->put('carrito', []);
        Carrito::actualizarCarrito(session()->getId(), []);
    }

}
