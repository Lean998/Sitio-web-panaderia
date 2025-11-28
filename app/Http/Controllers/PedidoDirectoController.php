<?php
// app/Http/Controllers/PedidoDirectoController.php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Pedido;
use App\Models\PedidoItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PedidoDirectoController extends Controller
{


    /**
     * Preparar compra directa del producto
     */
    public function comprarDirecto(Request $request, $productoId)
    {
        $producto = Producto::findOrFail($productoId);
        $cantidad = $request->filled('cantidad') ? abs(floatval($request->input('cantidad'))) : 1;

        // Validar stock
        if ($cantidad > $producto->cantidad) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        // Guardar en sesión temporal para el checkout
        session()->put('compra_directa', [
            'producto_id' => $producto->id,
            'cantidad' => $cantidad,
        ]);

        // Redirigir al checkout directo
        return redirect()->route('pedido.checkout-directo');
    }

    /**
     * Mostrar checkout para compra directa
     */
    public function checkoutDirecto()
    {
        $compraDirecta = session()->get('compra_directa');

        if (!$compraDirecta) {
            return redirect()->route('home')
                ->with('error', 'No hay productos para comprar');
        }

        $producto = Producto::findOrFail($compraDirecta['producto_id']);
        $cantidad = $compraDirecta['cantidad'];

        // Calcular total
        $subtotal = $producto->precio * $cantidad;

        return view('pedido.checkout-directo', compact('producto', 'cantidad', 'subtotal'));
    }

    /**
     * Crear pedido desde compra directa
     */
    public function crearPedidoDirecto(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'correo' => 'required|email|max:100',
        ]);

        $compraDirecta = session()->get('compra_directa');

        if (!$compraDirecta) {
            return redirect()->route('home')
                ->with('error', 'Sesión expirada');
        }

        $producto = Producto::findOrFail($compraDirecta['producto_id']);
        $cantidad = $compraDirecta['cantidad'];

        // Validar stock nuevamente
        if ($cantidad > $producto->cantidad) {
            return back()->with('error', 'No hay suficiente stock');
        }

        // Crear pedido manualmente
        DB::beginTransaction();
        try {
            $monto_total = $producto->precio * $cantidad;

            $pedido = Pedido::create([
                'nombre' => $validated['nombre'],
                'correo' => $validated['correo'],
                'monto_total' => $monto_total,
                'descuento' => 0,
                'monto_final' => $monto_total,
                'estado' => 'pendiente',
            ]);

            // Crear item del pedido
            PedidoItems::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'nombre_producto' => $producto->nombre,
                'unidad_venta' => $producto->unidad_venta,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
                'subtotal' => $monto_total,
            ]);

            // Limpiar sesión
            session()->forget('compra_directa');

            DB::commit();

            return redirect()->route('pedido.pago', $pedido->id)
                ->with('success', 'Pedido creado exitosamente');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}