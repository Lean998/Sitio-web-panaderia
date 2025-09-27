<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CarritoController;
use App\Services\ProductoService;
use Illuminate\Http\Request; 
use Exception;
use App\Exceptions\ProductoNoEncontradoException;
class ProductoController extends Controller
{
    protected ProductoService $productService;

    public function __construct()
    {
        $this->productService = new ProductoService();
    }
    public function index(Request $request, $categoria = null)
{
   
    $productos = $this->productService->filtrarProductos([
            'categoria' => $categoria,
            'buscar' => $request->buscar ?? null,
            'tipo' => $request->tipo ?? null,
            'orden' => $request->orden ?? null,
        ]);
        
        $tipos = [
            'Panaderia' => ['Medialunas', 'Pan integral', 'Baguette'],
            'Pasteleria' => ['Tortas', 'Tartas', 'Postres'],
            'Salados'   => ['Empanadas', 'Pizzetas', 'Facturas saladas'],
            'Todos'     => ['Medialunas', 'Pan integral', 'Baguette', 'Tortas', 'Tartas', 'Postres', 'Empanadas', 'Pizzetas', 'Facturas saladas'],
        ];

    return view('productos', ['productos' => $productos, 'categoriaKey' => $categoria ? ucfirst($categoria) : 'Todos', 'tipos' => $tipos]);
}

    public function getProducto($productoId)
    {
        try {
            $producto = $this->productService->getProducto($productoId);
            return view('producto', compact('producto'));
        } catch (ProductoNoEncontradoException $e) {
            return redirect()->route('productos')->with('error', $e->getMessage());
        }
    }

public function agregarYComprar(Request $request, $productoId)
    {
        try {
            $producto = $this->productService->getProducto($productoId);
            $cantidad = $request->filled('cantidad') ? (float)$request->cantidad : 1;

            if ($request->filled('agregar')) {
                $this->productService->validarStock($producto, $cantidad);
                return redirect()->route('carrito.agregar', ['producto' => $producto->id, 'cantidad' => $cantidad]);
            }

            if ($request->filled('comprar')) {
                $this->productService->comprarProducto($producto, $cantidad);
                return redirect()->route('productos.ver', ['producto' => $producto->id])->with('success', 'Producto comprado con éxito.');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }   
}