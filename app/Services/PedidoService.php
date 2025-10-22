<?php
// app/Services/PedidoService.php

namespace App\Services;

use App\Models\Pedido;
use App\Models\PedidoItems;
use App\Models\Producto;
use App\Models\Carrito;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionPedido;
use App\Mail\ActualizacionPedido;

class PedidoService
{
    protected CarritoService $carritoService;
    protected Pedido $pedidoModel;

    public function __construct()
    {
        $this->carritoService = new CarritoService();
        $this->pedidoModel = new Pedido();
    }

    /**
     * Crear pedido desde el carrito
     */
    public function crearPedidoDesdeCarrito(array $datosCliente): Pedido
    {
        // Validar que el carrito no esté vacío
        if ($this->carritoService->estaVacio()) {
            throw new Exception('El carrito está vacío');
        }

        // Validar stock
        $erroresStock = $this->carritoService->verificarStock();
        if (!empty($erroresStock)) {
            $mensaje = "Stock insuficiente para:\n";
            foreach ($erroresStock as $error) {
                $mensaje .= "- {$error['producto']}: solicitado {$error['solicitado']}, disponible {$error['disponible']}\n";
            }
            throw new Exception($mensaje);
        }

        DB::beginTransaction();
        try {
            // Obtener carrito
            $carritoModel = $this->carritoService->getCarritoModel();
            $totales = $this->carritoService->getTotales();

            // Crear el pedido
            $pedido = $this->pedidoModel->nuevoPedido($datosCliente['nombre'], $datosCliente['correo'], $totales['subtotal'], 0, $totales['subtotal'], 'pendiente');

            
            foreach ($carritoModel->items as $item) {
                PedidoItems::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item->producto_id,
                    'nombre_producto' => $item->producto->nombre,
                    'unidad_venta' => $item->producto->unidad_venta,
                    'cantidad' => $item->cantidad,
                    'precio_unitario' => $item->precio_unitario,
                    'subtotal' => $item->subtotal,
                ]);
            }

            DB::commit();
            return $pedido;

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Confirmar pago del pedido
     */
    public function confirmarPago(Pedido $pedido, string $medioPago): Pedido
{
    if ($pedido->estado !== 'pendiente') {
        throw new Exception('El pedido ya fue procesado');
    }

    DB::beginTransaction();
    try {
        // Calcular monto final con recargo
        $montos = $this->calcularMontoFinal($pedido->monto_total, $medioPago);
        
        // Actualizar pedido
        $pedido->update([
            'estado' => 'pagado',
            'medio_pago' => $medioPago,
            'descuento' => 0,
            'monto_final' => $montos['monto_final'],
            'fecha_pago' => now(),
        ]);

        // Descontar stock
        foreach ($pedido->items as $item) {
            $producto = $item->producto;
            
            if ($producto->cantidad < $item->cantidad) {
                throw new Exception("Stock insuficiente para {$producto->nombre}");
            }

            $producto->decrement('cantidad', $item->cantidad);
        }

        // Vaciar carrito
        $this->carritoService->vaciarCarrito();
        /*
        try {
            Mail::to($pedido->correo)->send(new ConfirmacionPedido($pedido));
        } catch (Exception $e) {
            throw $e;
        }
        */
        DB::commit();
        return $pedido->fresh();

    } catch (Exception $e) {
        DB::rollBack();
        throw $e;
    }
    }   

    /**
     * Cambiar estado del pedido
     */
    public function cambiarEstado(Pedido $pedido, string $nuevoEstado): Pedido
    {
        $estadosValidos = ['pendiente', 'pagado', 'preparando', 'listo', 'entregado', 'cancelado'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            throw new Exception('Estado inválido');
        }

        $estadoAnterior = $pedido->estado;
        $updates = ['estado' => $nuevoEstado];
        
        if ($nuevoEstado === 'entregado') {
            $updates['fecha_entrega'] = now();
        }

        $pedido->update($updates);
        /*
        if ($estadoAnterior !== $nuevoEstado) {
            try {
                Mail::to($pedido->correo)->queue(new ActualizacionPedido($pedido, $estadoAnterior));
            } catch (Exception $e) {
                throw $e;
            }
        }
        */
        return $pedido->fresh();
    }

    /**
     * Cancelar pedido
     */
    public function cancelarPedido(Pedido $pedido): Pedido
    {
        if (in_array($pedido->estado, ['entregado', 'cancelado'])) {
            throw new Exception('No se puede cancelar este pedido');
        }

        DB::beginTransaction();
        try {
            // Si ya estaba pagado, devolver el stock
            if ($pedido->estado !== 'pendiente') {
                foreach ($pedido->items as $item) {
                    $producto = $item->producto;
                    $producto->increment('cantidad', $item->cantidad);
                }
            }

            $pedido->update(['estado' => 'cancelado']);

            DB::commit();
            return $pedido->fresh();

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtener pedido por código de retiro
     */
    public function buscarPorCodigo(string $codigo): ?Pedido
    {
        return Pedido::with(['items.producto'])
            ->where('codigo_retiro', strtoupper($codigo))
            ->first();
    }

    /**
     * Obtener pedido por código de retiro o código de pedido
     */
    public function buscarPedido(string $codigo) : ?Pedido{
        return Pedido::where('codigo_retiro', $codigo)
        ->orWhere('codigo_pedido', $codigo)
        ->with(['items.producto'])
        ->first();
    }

    /**
     * Obtener pedidos por correo
     */
    public function obtenerPedidosPorCorreo(string $correo): array
    {
        return Pedido::with(['items.producto'])
            ->where('correo', $correo)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * Calcular estadísticas de productos más vendidos
     */
    public function productosMasVendidos(int $limite = 10): array
    {
        return DB::table('pedido_items')
        ->join('productos', 'pedido_items.producto_id', '=', 'productos.id')
        ->join('pedidos', 'pedido_items.pedido_id', '=', 'pedidos.id')
        ->whereIn('pedidos.estado', ['pagado', 'preparando', 'listo', 'entregado'])
        ->select(
            'productos.id',
            'productos.nombre',
            'productos.categoria',
            'productos.precio',
            'productos.unidad_venta',
            DB::raw('SUM(pedido_items.cantidad) as total_vendido'),
            DB::raw('COUNT(DISTINCT pedido_items.pedido_id) as cantidad_pedidos'),
            DB::raw('SUM(pedido_items.subtotal * pedidos.monto_final / pedidos.monto_total) as ingresos_totales')
        )
        ->groupBy('productos.id', 'productos.nombre', 'productos.categoria', 'productos.precio', 'productos.unidad_venta')
        ->orderBy('total_vendido', 'desc')
        ->limit($limite)
        ->get()
        ->toArray();
    }

    /**
     * Validar que el pedido puede ser modificado
     */
    public function puedeModificarse(Pedido $pedido): bool
    {
        return in_array($pedido->estado, ['pendiente', 'pagado']);
    }

    public function calcularMontoFinal(float $montoBase, string $medioPago): array
    {
        $porcentajeRecargo = config("pagos.recargos.{$medioPago}", 0);
        $montoRecargo = ($montoBase * $porcentajeRecargo) / 100;
        $montoFinal = $montoBase + $montoRecargo;
        
        return [
            'monto_base' => $montoBase, 
            'porcentaje_recargo' => $porcentajeRecargo,
            'monto_recargo' => $montoRecargo,
            'monto_final' => $montoFinal, 
        ];
    }

    /**
    * Obtener información de recargos
    */
    public function obtenerInfoRecargos(float $montoBase): array
    {
        $mediosPago = ['efectivo', 'transferencia', 'debito', 'credito'];
        $info = [];
        
        foreach ($mediosPago as $medio) {
            $calculo = $this->calcularMontoFinal($montoBase, $medio);
            $info[$medio] = [
                'nombre' => ucfirst($medio),
                'porcentaje' => $calculo['porcentaje_recargo'],
                'recargo' => $calculo['monto_recargo'],
                'total' => $calculo['monto_final'],
                'descripcion' => config("pagos.descripciones.{$medio}"),
            ];
        }
        
        return $info;
    }
}