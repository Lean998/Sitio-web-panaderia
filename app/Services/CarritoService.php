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
    protected Carrito $carrito;
    protected string $sessionId;
    public function __construct()
    {
        $this->sessionId = session()->getId();
        $this->carrito = Carrito::getCarritoPorSession($this->sessionId)->load('items.producto');
        session()->put('carrito_id', $this->carrito->id);
    }

    public function getCarrito(): array{
        $carritoCache = session()->get('carrito_array');
        
        if ($carritoCache === null) {
            $carritoCache = $this->carrito->fresh(['items.producto'])->getProductosArray();
            session()->put('carrito_array', $carritoCache);
        }
        
        return $carritoCache;
    }

    public function getCarritoModel(): Carrito {
        return $this->carrito->fresh(['items.producto']);
    }
    public function agregarUnidad(Producto $producto, float $cantidad = 1)
    {
        $cantidadEnCarrito = $this->carrito->getCantidadProducto($producto->id);
        $total = round($cantidadEnCarrito + $cantidad, 2);
        $stock = round($producto->cantidad, 2);

        if ($total > $stock) {
            throw new StockInsuficienteException(
                "No hay stock disponible para agregar {$cantidad} de {$producto->nombre}"
            );
        }

        $this->carrito->agregarProducto($producto, $cantidad);
        $this->actualizarCache();
        
        return $this;
    }

    public function eliminarUnidad(Producto $producto, float $cantidad = 1): bool
    {
        if (!$this->carrito->tieneProducto($producto->id)) {
            throw new ProductoNoEncontradoException(
                "El producto {$producto->nombre} no está en el carrito"
            );
        }

        $cantidadEnCarrito = $this->carrito->getCantidadProducto($producto->id);
        $total = round($cantidadEnCarrito - $cantidad, 2);

        if ($total <= 0) {
            $this->carrito->removerProducto($producto->id);
            $this->actualizarCache();
            
            throw new ProductoEliminadoException(
                "Se eliminó {$producto->nombre} del carrito de compras"
            );
        }

        $this->carrito->setCantidadProducto($producto->id, $total);
        $this->actualizarCache();
        
        return true;
    }

    public function setCantidad(Producto $producto, float $cantidad)
    {
        $stock = round($producto->cantidad, 2);
        $cantidad = round($cantidad, 2);

        if ($cantidad > $stock) {
            throw new StockInsuficienteException(
                "No hay stock suficiente. Disponible: {$stock}"
            );
        }

        if ($cantidad <= 0) {
            $this->carrito->removerProducto($producto->id);
        } else {
            $this->carrito->setCantidadProducto($producto->id, $cantidad);
        }

        $this->actualizarCache();
        return $this;
    }
    public function removerProducto(int $productoId): bool
    {
        $resultado = $this->carrito->removerProducto($productoId);
        $this->actualizarCache();
        
        return $resultado > 0;
    }

    public function vaciarCarrito(): void
    {
        $this->carrito->vaciar();
        $this->actualizarCache();
    }

    protected function actualizarCache(): void
    {
        $carritoArray = $this->carrito->fresh(['items.producto'])->getProductosArray();
        session()->put('carrito_array', $carritoArray);
    }

    public function getTotales(): array
    {
        $carrito = $this->carrito->fresh(['items.producto']);
        
        return [
            'subtotal' => $carrito->total,
            'cantidad_items' => $carrito->cantidadItems,
            'cantidad_total' => $carrito->cantidadTotal,
        ];
    }

    public function estaVacio(): bool
    {
        return $this->carrito->items->isEmpty();
    }

    public function verificarStock(): array
    {
        $errores = [];
        
        foreach ($this->carrito->items as $item) {
            $producto = $item->producto;
            
            if ($item->cantidad > $producto->cantidad) {
                $errores[] = [
                    'producto' => $producto->nombre,
                    'solicitado' => $item->cantidad,
                    'disponible' => $producto->cantidad,
                ];
            }
        }
        
        return $errores;
    }

    public function enCarrito($productoId): bool{
        return $this->carrito->tieneProducto($productoId);
    }

}
