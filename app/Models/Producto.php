<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\PedidoItems;

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

    public function pedidoItems(): HasMany
    {
        return $this->hasMany(PedidoItems::class);
    }

    // Estadística de ventas
    public function getTotalVendidoAttribute(): float
    {
        return $this->pedidoItems()
            ->whereHas('pedido', function ($query) {
                $query->whereIn('estado', ['pagado', 'preparando', 'listo', 'entregado']);
            })
            ->sum('cantidad');
    }
}