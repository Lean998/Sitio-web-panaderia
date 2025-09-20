<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Http\Controllers\Controller;
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

    public function comprarProducto(Request $request, $producto = null){
        if ($producto) {
            $producto = Producto::find($producto);
            if ($producto) {
                // Verificar stock
                if ($producto->cantidad > 0) {
                    // Disminuir stock
                    $producto->cantidad -= 1;
                    $producto->save();
                }
                return redirect()->route('productos.ver', ['producto' => $producto->id])->with('success', 'Producto comprado con éxito.');
            }      
        }
    }
}