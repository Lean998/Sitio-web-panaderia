<?php

namespace App\Http\Controllers;
use App\Models\Carrito;
use App\Models\Favoritos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
class BaseController extends Controller
{
    public function index(){
            return view('home');
    }
    
    public function sucursal(){
        return view('sucursal');
    }

}