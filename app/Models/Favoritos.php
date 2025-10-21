<?php
// app/Models/Favoritos.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Favoritos extends Model
{
    protected $fillable = [
        'session_id',
    ];

    // Relaciones
    public function items(): HasMany
    {
        return $this->hasMany(FavoritoItem::class, 'favorito_id');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($favoritos) {
            $favoritos->touch('last_activity');
        });
    }

    // Obtener favoritos por session_id
    public static function getFavoritosPorSession(string $sessionId): self
    {
        return self::firstOrCreate(['session_id' => $sessionId]);
    }

    // Obtener items en formato array
    public function getProductosArray(): array
    {
        $productos = [];
        
        foreach ($this->items()->with('producto')->get() as $item) {
            $producto = $item->producto;
            $productos[$producto->id] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio,
                'imagen' => $producto->imagen,
            ];
        }
        
        return $productos;
    }

    public function agregarProducto(Producto $producto)
    {
        // Evitar duplicados
        if (!$this->tieneProducto($producto->id)) {
            $this->items()->create([
                'producto_id' => $producto->id
            ]);
        }

        return $this;
    }

    // Remover producto
    public function removerProducto(int $productoId)
    {
        return $this->items()->where('producto_id', $productoId)->delete();
    }

    // Verificar si tiene un producto
    public function tieneProducto(int $productoId): bool
    {
        return $this->items()->where('producto_id', $productoId)->exists();
    }

    // Toggle (agregar/remover)
    public function toggle(Producto $producto): bool
    {
        if ($this->tieneProducto($producto->id)) {
            $this->removerProducto($producto->id);
            return false;
        } else {
            $this->agregarProducto($producto);
            return true; 
        }
    }

    // Vaciar favoritos
    public function vaciar()
    {
        return $this->items()->delete();
    }

    // Método para obtener favoritos por session_id 
    public static function getFavoritos(string $sessionId): array
    {
        $favoritos = self::where('session_id', $sessionId)->first();
        return $favoritos ? $favoritos->getProductosArray() : [];
    }

    // Accessor para cantidad de favoritos
    public function getCantidadAttribute(): int
    {
        return $this->items->count();
    }
}