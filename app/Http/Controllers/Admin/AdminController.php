<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductoService;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
    protected ProductoService $productService;

    public function __construct()
    {
        $this->productService = new ProductoService();
    }

    public function productos(Request $request, $categoria = null)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

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

        return view('admin.productos.index', ['productos' => $productos, 'categoriaKey' => $categoria ? ucfirst($categoria) : 'Todos', 'tipos' => $tipos]);
    }

}