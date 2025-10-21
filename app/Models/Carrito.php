<?php
// app/Models/Carrito.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $fillable = [
        'session_id',
        'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];

    // Relaciones
    public function items(): HasMany
    {
        return $this->hasMany(CarritoItem::class);
    }

    // Actualizar actividad automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($carrito) {
            $carrito->touch('last_activity');
        });
    }

    // Obtener carrito por session_id
    public static function getCarritoPorSession(string $sessionId): self
    {
        return self::firstOrCreate(['session_id' => $sessionId]);
    }

    // Obtener items del carrito en formato array 
    public function getProductosArray(): array
    {
        $productos = [];
        
        foreach ($this->items as $item) {
            $productos[$item->producto_id] = [
                'id' => $item->producto->id,
                'nombre' => $item->producto->nombre,
                'cantidad' => (float) $item->cantidad,
                'unidad_venta' => $item->producto->unidad_venta,
                'precio' => (float) $item->precio_unitario,
                'imagen' => $item->producto->imagen,
            ];
        }
        
        return $productos;
    }

    // Agregar producto
    public function agregarProducto(Producto $producto, float $cantidad = 1)
    {
        $item = $this->items()->where('producto_id', $producto->id)->first();

        if ($item) {
            $item->cantidad += $cantidad;
            $item->save();
        } else {
            $this->items()->create([
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
            ]);
        }

        $this->touch('last_activity');
        return $this;
    }

    // Establecer cantidad específica
    public function setCantidadProducto(int $productoId, float $cantidad)
    {
        if ($cantidad <= 0) {
            return $this->removerProducto($productoId);
        }

        $item = $this->items()->where('producto_id', $productoId)->first();

        if ($item) {
            $item->cantidad = $cantidad;
            $item->save();
        }

        $this->touch('last_activity');
        return $this;
    }

    // Remover producto
    public function removerProducto(int $productoId)
    {
        $deleted = $this->items()->where('producto_id', $productoId)->delete();
        $this->touch('last_activity');
        return $deleted;
    }

    public function vaciar()
    {
        $this->items()->delete();
        $this->touch('last_activity');
        return $this;
    }

    public function tieneProducto(int $productoId): bool
    {
        return $this->items()->where('producto_id', $productoId)->exists();
    }

    // Obtener cantidad de un producto
    public function getCantidadProducto(int $productoId): float
    {
        $item = $this->items()->where('producto_id', $productoId)->first();
        return $item ? (float) $item->cantidad : 0;
    }

    public function getTotalAttribute(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->cantidad * $item->precio_unitario;
        });
    }

    public function getCantidadTotalAttribute(): float
    {
        return (float) $this->items->sum('cantidad');
    }

    public function getCantidadItemsAttribute(): int
    {
        return $this->items->count();
    }
}