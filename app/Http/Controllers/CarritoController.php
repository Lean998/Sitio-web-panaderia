<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Producto;
class CarritoController extends Controller
{
    public function index(){
        $carrito = Carrito::getCarrito(session()->getId());
        $total = array_sum(array_map(fn($item) => $item['precio'] * $item['cantidad'], $carrito));
        return view('carrito', compact('carrito', 'total'));
    }

    public function agregarAlCarrito(Request $request, $productoId = null, $cantidad = 1){
        $carrito = session()->get('carrito', []);
        $producto = Producto::find($productoId);
        if (isset($carrito[$productoId])) {
            

            $cantidadEnCarrito = $carrito[$productoId]['cantidad'] ?? 0;

            $total = round($cantidadEnCarrito + $cantidad, 2);
            $stock = round($producto->cantidad, 2);

            if ($total > $stock) {
                return back()->with('error', 'No hay stock disponible para agregar '.$cantidad.' de '.$producto->nombre);
            }

            $carrito[$productoId]['cantidad'] = $total;
            session()->put('carrito', $carrito);
            
            Carrito::actualizarCarrito(session()->getId(), session()->get('carrito', []));
            
            if(!$this->actualizarCarrito()){
                return back()->with('error', 'Ocurrio un error inesperado al agregar el producto al carrito de compras.');
            }

            return back()->with('success', 'Agregaste una unidad de '.$producto->nombre.' al carrito.');
        } else {
            if($producto->cantidad - $cantidad < 0){
                return back()->with('error', 'No hay stock disponible para agregar '.$producto->nombre);
            }
            $carrito[$productoId] = [
                "id" => $producto->id,
                "nombre" => $producto->nombre,
                "cantidad" => $cantidad,
                'unidad_venta' => $producto->unidad_venta,
                "precio" => $producto->precio,
                "imagen" => $producto->imagen
            ];
    }

    session()->put('carrito', $carrito);
    Carrito::actualizarCarrito(session()->getId(), session()->get('carrito', []));
    
    if(!$this->actualizarCarrito()){
        return back()->with('error', 'Ocurrio un error inesperado al agregar el producto al carrito de compras.');
    }
    
    return back()->with('success', 'Agregaste '. $producto->nombre .' al carrito de compras.');
}

public function eliminarProducto($productoId){
    $carrito = session()->get('carrito', []);
    $nombre = $carrito[$productoId]['nombre'] ?? '';
    if (isset($carrito[$productoId])) {
        unset($carrito[$productoId]);
        session()->put('carrito', $carrito);
    }
    Carrito::actualizarCarrito(session()->getId(), session()->get('carrito', []));
    if(!$this->actualizarCarrito()){
        return back()->with('error', 'Ocurrio un error inesperado al eliminar '.$nombre.' del carrito de compras.');
    }
    return back()->with('warning', 'Eliminaste '.$nombre.' del carrito de compras.');
}

public function eliminarUnidad(Request $request, $productoId, $cantidad = null){
    $carrito = session()->get('carrito', []);
    $nombre = '';
    if (isset($carrito[$productoId])) {
        if($request->filled('cantidad')){
            $cantidad = abs(floatval($request->input('cantidad')));  
        } else{
            $cantidad = 1; // Valor por defecto si no se proporciona
        }
        $carrito[$productoId]['cantidad']-= $cantidad;
        if ($carrito[$productoId]['cantidad'] <= 0) {
            $nombre = $carrito[$productoId]['nombre'];
            unset($carrito[$productoId]);
        }
        session()->put('carrito', $carrito);
    }
    if(!Carrito::actualizarCarrito(session()->getId(), session()->get('carrito', []))){
        return back()->with('error', 'Ocurrio un error inesperado al eliminar el producto del carrito de compras.');
    }
    if(!$this->actualizarCarrito()){
        return back()->with('error', 'Ocurrio un error inesperado al restar el producto del carrito de compras.');
    }
    if(!isset($carrito[$productoId])){
        return back()->with('warning', 'Eliminaste '.$nombre.' del carrito de compras.');
    }
    return redirect()->to('carrito');
}

public function agregarUnidad(Request $request, $productoId, $cantidad = null){
    $carrito = session()->get('carrito', []);

    if (isset($carrito[$productoId])) {
        $producto = Producto::find($productoId);

        if ($request->filled('cantidad')) {
            $cantidad = abs(floatval($request->input('cantidad')));
        } else {
            $cantidad = 1; // Valor por defecto
        }

        $cantidadEnCarrito = $carrito[$productoId]['cantidad'] ?? 0;

        $total = round($cantidadEnCarrito + $cantidad, 2);
        $stock = round($producto->cantidad, 2);

        if ($total > $stock) {
            return back()->with('error', 'No hay stock disponible para agregar '.$cantidad.' de '.$producto->nombre);
        }

        $carrito[$productoId]['cantidad'] = $total;
        
        session()->put('carrito', $carrito);
    }

    if(!Carrito::actualizarCarrito(session()->getId(), session()->get('carrito', []))){
        return back()->with('error', 'Ocurrio un error inesperado al agregar '.$producto->nombre.' al carrito de compras.');
    }

    if(!$this->actualizarCarrito()){
        return back()->with('error', 'Ocurrio un error inesperado al agregar '.$producto->nombre.' al carrito de compras.');
    }
    return back();
}

public function eliminarCarrito(){
    session()->forget('carrito');
    if(!Carrito::actualizarCarrito(session()->getId(), session()->get('carrito', []))){
        return back()->with('error', 'Ocurrio un error inesperado al vaciar el carrito de compras.');
    }
    if(!$this->actualizarCarrito()){
        return back()->with('error', 'Ocurrio un error inesperado al vaciar el carrito de compras.');
    }
    return back()->with('success', 'Carrito de compras vaciado con exito.');
}

public function actualizarCarrito(){
    session()->put('carrito', Carrito::getCarrito(session()->getId()));
    return true;
}
}
