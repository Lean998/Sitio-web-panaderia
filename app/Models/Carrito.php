<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $fillable = [
        'id',
        'session_id',
        'productos',
    ];

    public static function getCarrito($id){
        $carrito = Carrito::where('session_id', $id)->first();
        if($carrito){
            $carrito->update(['last_activity' => now()]);
            return  json_decode($carrito->productos, true);
        }
        return [];
    }
    public static function actualizarCarrito($id, $carrito){
        return Carrito::updateOrCreate(
        ['session_id' => $id], 
        ['productos' => json_encode($carrito), 
                'last_activity' => now()] 
        
    );
    }
}
