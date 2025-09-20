<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'nombre',
        'descripcion',
        'categoria',
        'tipo',
        'precio',
        'imagen',
        'cantidad',
        'unidad_venta'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'cantidad' => 'decimal:2',
    ];
}