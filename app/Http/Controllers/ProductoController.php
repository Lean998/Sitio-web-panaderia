<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CarritoController;
use App\Models\Carrito;
use Illuminate\Http\Request; 
class ProductoController extends Controller
{
    public function index(Request $request, $categoria = null)
{
    $query = Producto::query();

    // Definir la categoría para la vista
    $categoriaKey = $categoria ? ucfirst(strtolower($categoria)) : 'Todos';

    // Filtrar por categoría solo si no es "Todos"
    if ($categoria && $categoriaKey !== 'Todos') {
        $query->where('categoria', $categoriaKey);
    }

    // Filtro por búsqueda
    if ($request->filled('buscar')) {
        $query->where('nombre', 'like', '%' . $request->buscar . '%');
    }

    // Filtro por tipo
    if ($request->filled('tipo')) {
        $query->where('tipo', ucfirst($request->tipo));
    }

    // Ordenamiento
    if ($request->filled('orden')) {
        switch ($request->orden) {
            case 'asc':
                $query->orderBy('nombre', 'asc');
                break;
            case 'desc':
                $query->orderBy('nombre', 'desc');
                break;
            case 'menorPrecio':
                $query->orderBy('precio', 'asc');
                break;
            case 'mayorPrecio':
                $query->orderBy('precio', 'desc');
                break;
        }
    }

    // Paginación final
    $productos = $query->paginate(12)->withQueryString();

    // Tipos disponibles para filtros
    $tipos = [
        'Panaderia' => ['Medialunas', 'Pan integral', 'Baguette'],
        'Pasteleria' => ['Tortas', 'Tartas', 'Postres'],
        'Salados'   => ['Empanadas', 'Pizzetas', 'Facturas saladas'],
        'Todos'     => ['Medialunas', 'Pan integral', 'Baguette', 'Tortas', 'Tartas', 'Postres', 'Empanadas', 'Pizzetas', 'Facturas saladas'],
    ];

    return view('productos', compact('productos', 'categoriaKey', 'tipos'));
}

    public function getProducto($producto = null)
    {
        if ($producto) {
            $producto = Producto::find($producto);
            if ($producto) {
                return view('producto', ['producto' => $producto]);
            } else {
                return redirect()->route('productos')->with('error', 'Producto no encontrado.');
            }
        } else {
            return redirect()->route('productos')->with('error', 'Producto no especificado.');
        }
    }

    public function productoExtendido(Request $request, $producto = null){
        if($request->filled('agregar')){
            $producto = Producto::find($producto);
            if (!$producto) {
                return redirect()->route('productos')->with('error', 'Producto no encontrado.');
            }
            $cantidad = $request->filled('cantidad') ? (int)$request->cantidad : 0;
            if($cantidad < 1){
                return redirect()->route('productos')->with('error', 'Cantidad inválida.');
            }
            return redirect()->route('carrito.agregar', ['producto' => $producto->id, 'cantidad' => $cantidad]);
        }
        
        if($request->filled('comprar')){
            $producto = Producto::find($producto);
            if (!$producto) {
                return redirect()->route('productos')->with('error', 'Producto no encontrado.');
            }
            return $this->comprarProducto($request, $producto->id);
        }
    }

    public function comprarProducto(Request $request, $producto = null){
        if ($producto) {
            $producto = Producto::find($producto);
            if ($producto) {
                $carrito = session()->get('carrito', []);
                $cantidad = $request->filled('cantidad') ? (int)$request->cantidad : 0;
                if($cantidad < 1){
                    return redirect()->route('productos.ver', ['producto' => $producto->id])->with('error', 'Cantidad inválida.');
                }
                $cantidadCarrito = $carrito[$producto->id]['cantidad'] ?? 0;
                if($producto->cantidad - $cantidadCarrito + $cantidad <= 0){
                    return back()->with('error', 'No hay stock disponible para agregar otra unidad de '.$producto->nombre);
                }
                // Disminuir stock
                $producto->cantidad -= $cantidad;
                $producto->save();
                // Limpiar carrito
                $carritoController = new CarritoController();
                $carritoController->eliminarProducto($producto->id);
                // Actualizar carrito en base de datos  
                $carritoController->actualizarCarrito(session()->getId(), session()->get('carrito', []));
                //Confirmacion de Compra
                return redirect()->route('productos.ver', ['producto' => $producto->id])->with('success', 'Producto comprado con éxito.');
            }      
        }
    }
}