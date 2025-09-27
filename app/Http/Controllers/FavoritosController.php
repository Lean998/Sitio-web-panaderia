<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Services\FavoritosService;
use Exception;

class FavoritosController extends Controller
{
    protected FavoritosService $favoritosService;

    public function __construct()
    {
        $this->favoritosService = new FavoritosService();
    }

    public function index()
    {
        $favoritos = $this->favoritosService->getFavoritos();
        return view('favoritos', compact('favoritos'));
    }

    public function agregarAFavoritos($productoId)
    {
        $producto = Producto::findOrFail($productoId);

        try {
            $this->favoritosService->agregarAFavoritos($producto);
            return back()->with('success', "Agregaste {$producto->nombre} a la lista de favoritos.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function eliminarProducto($productoId)
    {
        try {
            $this->favoritosService->eliminarProducto($productoId);
            return back()->with('warning', "Eliminaste el producto de la lista de favoritos.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function eliminarFavoritos()
    {
        try {
            $this->favoritosService->vaciarFavoritos();
            return back()->with('success', "Lista de favoritos vaciada con éxito.");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
