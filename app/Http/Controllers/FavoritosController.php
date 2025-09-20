<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Favoritos;
class FavoritosController extends Controller
{
    public function index(){
        $favoritos = Favoritos::getFavoritos(session()->getId());
        return view('favoritos', compact('favoritos'));
    }

    public function agregarAFavoritos(Request $request, $productoId = null){
        $favoritos = session()->get('favoritos', []);
        $producto = Producto::find($productoId);
        if (isset($favoritos[$productoId])) {
            return back()->with('error', 'Ya agregaste '.$producto->nombre. ' a tu lista de favoritos.');
        } else {
        $favoritos[$productoId] = [
            "id" => $producto->id,
            "nombre" => $producto->nombre,
            "precio" => $producto->precio,
            "imagen" => $producto->imagen
        ];
    }
    session()->put('favoritos', $favoritos);
    Favoritos::actualizarFavoritos(session()->getId(), session()->get('favoritos', []));
    if(!$this->actualizarFavoritos()){
        return back()->with('error', 'Ocurrio un error inesperado al agregar el producto a la lista de favoritos.');
    }
    return back()->with('success', 'Agregaste '.$producto->nombre.' a la lista de favoritos.');
}

public function eliminarProducto($productoId){
    $favoritos = session()->get('favoritos', []);
    $nombre = $favoritos[$productoId]['nombre'] ?? '';
    if (isset($favoritos[$productoId])) {
        unset($favoritos[$productoId]);
        session()->put('favoritos', $favoritos);
    }
    Favoritos::actualizarFavoritos(session()->getId(), session()->get('favoritos', []));
    if(!$this->actualizarFavoritos()){
        return back()->with('error', 'Ocurrio un error inesperado al eliminar '.$nombre.' de la lista de favoritos.');
    }
    return back()->with('warning', 'Eliminaste '.$nombre.' de la lista de favoritos.');
}


public function eliminarFavoritos(){
    session()->forget('favoritos');
    if(!Favoritos::actualizarFavoritos(session()->getId(), session()->get('favoritos', []))){
        return back()->with('error', 'Ocurrio un error inesperado al eliminar la lista de favoritos.');
    }
    if(!$this->actualizarFavoritos()){
        return back()->with('error', 'Ocurrio un error inesperado al eliminar la lista de favoritos.');
    }
    return back()->with('success', 'Lista de favoritos vaciada con exito.');
}

public function actualizarFavoritos(){
    session()->put('favoritos', Favoritos::getFavoritos(session()->getId()));
    return true;
}
}
