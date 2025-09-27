<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductoEliminadoException;
use App\Exceptions\ProductoNoEncontradoException;
use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Producto;
use App\Services\CarritoService;
use Exception;
use App\Exceptions;
class CarritoController extends Controller
{
    protected CarritoService $carritoService;

    public function __construct()
    {
        $this->carritoService = new CarritoService();
    }
    public function index(){
        $carrito = $this->carritoService->getCarrito();
        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        return view('carrito', compact('carrito', 'total'));
    }

    public function agregarAlCarrito(Request $request, $productoId = null, $cantidad = 1){
        $producto = Producto::findOrFail($productoId);
        $cantidad = $request->filled('cantidad') ? abs(floatval($request->input('cantidad'))) : 1;
    
        try {
            $this->carritoService->agregarUnidad($producto, $cantidad);
            return back()->with('success', "Agregaste {$cantidad} de {$producto->nombre} al carrito.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
}

public function eliminarProducto($productoId){
    $producto = Producto::findOrFail($productoId);
        try {
            $this->carritoService->eliminarUnidad($producto, $this->carritoService->getCarrito()[$productoId]['cantidad'] ?? 0);
            return back()->with('warning', "Eliminaste {$producto->nombre} del carrito.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
}

public function eliminarUnidad(Request $request, $productoId, $cantidad = null){
    $producto = Producto::findOrFail($productoId);
        $cantidad = $request->filled('cantidad') ? abs(floatval($request->input('cantidad'))) : 1;
        try {
            $this->carritoService->eliminarUnidad($producto, $cantidad);
            return back()->with('success', "Se eliminó {$cantidad} {$producto->unidad_venta} de {$producto->nombre} del carrito.");
        } catch (ProductoNoEncontradoException $e) {
            return back()->with('error', $e->getMessage());
        }
        catch (ProductoEliminadoException $e) {
            return back()->with('warning', $e->getMessage());
        }
}

public function agregarUnidad(Request $request, $productoId, $cantidad = null){
    $producto = Producto::findOrFail($productoId);
    $cantidad = $request->filled('cantidad') ? abs(floatval($request->input('cantidad'))) : 1;


    try {
        $this->carritoService->agregarUnidad($producto, $cantidad);
        return back()->with('success', "Agregaste {$cantidad} de {$producto->nombre} al carrito.");
    } catch (Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}

public function eliminarCarrito(){
    try {
        $this->carritoService->vaciarCarrito();
        return back()->with('success', 'Carrito de compras vaciado con éxito.');
    } catch (Exception $e) {
        return back()->with('error', 'Ocurrió un error inesperado al vaciar el carrito de compras.');
    }
}
public function actualizarCarrito(): bool{
    session()->put('carrito', Carrito::getCarrito(session()->getId()) ?? []);
    return true;
}

}
