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
        $producto = Producto::findOrFail($productoId);
        if(!$producto) {
            return back()->with('error', "Producto no encontrado.");
        }
        try {
            $this->favoritosService->eliminarProducto($productoId);
            return back()->with('warning', "Eliminaste {$producto->nombre} de la lista de favoritos.");
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

    public function toggle($productoId){
        $producto = Producto::findOrFail($productoId);

        try {
            $agregado = $this->favoritosService->toggle($producto);
            
            $mensaje = $agregado 
                ? "Agregaste {$producto->nombre} a favoritos"
                : "Eliminaste {$producto->nombre} de favoritos";
            
            return back()->with($agregado ? 'success' : 'warning', $mensaje);
            
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
