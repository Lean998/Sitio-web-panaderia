<?php

namespace App\Services;

use App\Exceptions\ProductoNoEncontradoException;
use App\Exceptions\StockInsuficienteException;
use App\Models\Producto;
use App\Models\Carrito;
use App\Http\Controllers\CarritoController;
use Exception;

class ProductoService
{
    public function filtrarProductos(array $params)
    {
        $query = Producto::query();

        if (!empty($params['categoria']) && strtolower($params['categoria']) !== 'todos') {
            $query->where('categoria', ucfirst(strtolower($params['categoria'])));
        }

        if (!empty($params['buscar'])) {
            $query->where('nombre', 'like', '%' . $params['buscar'] . '%');
        }

        if (!empty($params['tipo'])) {
            $query->where('tipo', ucfirst($params['tipo']));
        }

        if (!empty($params['orden'])) {
            switch ($params['orden']) {
                case 'asc': $query->orderBy('nombre', 'asc'); break;
                case 'desc': $query->orderBy('nombre', 'desc'); break;
                case 'menorPrecio': $query->orderBy('precio', 'asc'); break;
                case 'mayorPrecio': $query->orderBy('precio', 'desc'); break;
            }
        }

        return $query->paginate(12)->withQueryString();
    }

    public function getProducto(int $productoId): Producto
    {
        $producto = Producto::find($productoId);
        if (!$producto) {
            throw new ProductoNoEncontradoException("Producto no encontrado.");
        }
        return $producto;
    }

    public function validarStock(Producto $producto, float $cantidad): void
    {
        if ($cantidad < 0.1) {
            throw new Exception("Cantidad inválida.");
        }

        $carrito = session()->get('carrito', []);
        $cantidadEnCarrito = $carrito[$producto->id]['cantidad'] ?? 0;

        if ($producto->cantidad - $cantidadEnCarrito < $cantidad) {
            throw new StockInsuficienteException("No hay stock disponible para agregar {$cantidad} de {$producto->nombre}");
        }
    }

    public function comprarProducto(Producto $producto, float $cantidad): void
    {
        $this->validarStock($producto, $cantidad);

        $carritoController = new CarritoController();

        // Actualizar stock
        $producto->cantidad -= $cantidad;
        $producto->save();

        // Limpiar carrito
        $carritoController->eliminarProducto($producto->id);

        // Actualizar carrito en DB
        $carritoController->actualizarCarrito(session()->getId(), session()->get('carrito', []));

    }
}
