<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favoritos extends Model
{
    protected $fillable = [
        'id',
        'session_id',
        'productos',
    ];

    public static function getFavoritos($id){
        $favoritos = Favoritos::where('session_id', $id)->first();
        if($favoritos){
            $favoritos->update(['last_activity' => now()]);
            return  json_decode($favoritos->productos, true);
        }
        return [];
    }
    public static function actualizarFavoritos($id, $favoritos){
        return Favoritos::updateOrCreate(
        ['session_id' => $id], 
        ['productos' => json_encode($favoritos), 
                'last_activity' => now()] 
        
    );
    }
}
