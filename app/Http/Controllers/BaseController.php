<?php

namespace App\Http\Controllers;
use App\Models\Carrito;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
class BaseController extends Controller
{
    public function index(){
            $carrito = [];
            $favoritos = [];
            if (!session()->has('carrito')) {
                Carrito::firstOrCreate(['session_id' => session()->getId()], ['productos' => json_encode([])]);
            }

            $carrito = Carrito::getCarrito(session()->getId());
            session()->put('carrito', $carrito);
            
            if (!session()->has('favoritos')) {
                //Favoritos::firstOrCreate(['session_id' => session()->getId()], ['productos' => json_encode([])]);
            }

            return view('home');
    }    
}