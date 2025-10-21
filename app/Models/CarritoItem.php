<?php
// app/Models/CarritoItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarritoItem extends Model
{
    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
        'precio_unitario'
    ];

    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
    ];

    // Relaciones
    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    // Accessor para subtotal
    public function getSubtotalAttribute(): float
    {
        return (float) ($this->cantidad * $this->precio_unitario);
    }
    // Retornar el top 10 de productos más agregados al carrito
    public static function getMasAgregados(): array
    {
        return self::select('producto_id')
            ->selectRaw('COUNT(producto_id) as total_cantidad')
            ->groupBy('producto_id')
            ->orderByDesc('total_cantidad')
            ->with('producto')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'producto' => $item->producto,
                    'total_cantidad' => (int) $item->total_cantidad,
                ];
            })
            ->toArray();
    }
    
}