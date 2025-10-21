<?php
// app/Models/Pedido.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\PedidoItems;
class Pedido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'correo',
        'codigo_retiro',
        'estado',
        'medio_pago',
        'monto_total',
        'descuento',
        'monto_final',
        'fecha_pago',
        'fecha_entrega',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'descuento' => 'decimal:2',
        'monto_final' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'fecha_entrega' => 'datetime',
    ];

    public function nuevoPedido($nombre, $correo, $montoTotal, $descuento, $montoFinal, $estado){
        return $this::create([
                'nombre' => $nombre,
                'correo' => $correo,
                'monto_total' => $montoTotal,
                'descuento' => $descuento,
                'monto_final' => $montoFinal,
                'estado' => $estado,
            ]);
    }
    // Relaciones
    public function items(): HasMany
    {
        return $this->hasMany(PedidoItems::class);
    }

    // Generar código de retiro automáticamente
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($pedido) {
            if (!$pedido->codigo_retiro) {
                $pedido->codigo_retiro = self::generarCodigoUnico();
            }
        });

        static::creating(function ($pedido) {
            $pedido->codigo_pedido = self::generarCodigoPedidoUnico();
        });
    }

    // Generar código de pedido único
    protected static function generarCodigoPedidoUnico()
    {
        do {
            $codigo = 'PD-' . strtoupper(Str::random(8));
        } while (self::where('codigo_pedido', $codigo)->exists());

        return $codigo;
    }
    
    // Generar código único
    public static function generarCodigoUnico(): string
    {
        do {
            $codigo = strtoupper(Str::random(8));
        } while (self::where('codigo_retiro', $codigo)->exists());
        
        return $codigo;
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopePagados($query)
    {
        return $query->whereIn('estado', ['pagado', 'preparando', 'listo']);
    }

    public function scopeListosParaRetirar($query)
    {
        return $query->where('estado', 'listo');
    }

    // Accessors
    public function getEsPendienteAttribute(): bool
    {
        return $this->estado === 'pendiente';
    }

    public function getEsPagadoAttribute(): bool
    {
        return in_array($this->estado, ['pagado', 'preparando', 'listo', 'entregado']);
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente' => 'caramel',
            'pagado' => 'espresso',
            'preparando' => 'coffee',
            'listo' => 'success',
            'entregado' => 'chocolate',
            'cancelado' => 'danger',
            default => 'light'
        };
    }
}