<?php

namespace App\Services;

use App\Exceptions\ProductoNoEncontradoException;
use App\Exceptions\StockInsuficienteException;
use App\Models\Producto;
use App\Models\Carrito;
use App\Http\Controllers\CarritoController;
use Exception;
use GuzzleHttp\Psr7\Request;

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

    public function crearProducto($data){ 
        $producto = new Producto();
        $producto->nombre = $data[0]['nombre'];
        $producto->descripcion = $data[0]['descripcion'];
        $producto->precio = $data[0]['precio'];
        $producto->categoria = $data[0]['categoria'];
        $producto->tipo = $data[0]['tipo'];
        $producto->cantidad = $data[0]['cantidad'];
        $producto->unidad_venta = $data[0]['unidad'];
        $producto->imagen = $data[1];
        $producto->save();

        return $producto;
    }

    public function eliminarProducto($productoId){
        $producto = Producto::find($productoId);
        if (!$producto) {
            throw new ProductoNoEncontradoException("Producto no encontrado.");
        }

        if (!$producto->delete()) {
            throw new Exception("Error al eliminar el producto.");
        }
        
        return true;
    }

    public function editarProducto($data, $productoId){
        $producto = Producto::find($productoId);
        if (!$producto) {
            throw new ProductoNoEncontradoException("Producto no encontrado.");
        }

        $producto->nombre = $data['nombre'];
        $producto->descripcion = $data['descripcion'];
        $producto->precio = $data['precio'];
        $producto->categoria = $data['categoria'];
        $producto->tipo = $data['tipo'];
        $producto->cantidad = $data['cantidad'];
        $producto->unidad_venta = $data['unidad'];
        
        if(!empty($data['imagen'])){
            $producto->imagen = $data['imagen'];
        }
        
        if (!$producto->save()) {
            throw new Exception("Error al modificar el producto.");
        }
        
        return true;
    }
}