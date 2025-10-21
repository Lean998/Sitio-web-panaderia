<?php
// app/Http/Controllers/PedidoController.php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Services\PedidoService;
use App\Services\CarritoService;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
class PedidoController extends Controller
{
    protected PedidoService $pedidoService;
    protected CarritoService $carritoService;

    public function __construct()
    {
        $this->pedidoService = new PedidoService();
        $this->carritoService = new CarritoService();
    }

    /**
     * Mostrar formulario de checkout
     */
    public function checkout()
    {
        // Verificar que el carrito no esté vacío
        if ($this->carritoService->estaVacio()) {
            return redirect()->route('carrito')
                ->with('error', 'Tu carrito está vacío');
        }

        // Verificar stock disponible
        $erroresStock = $this->carritoService->verificarStock();
        if (!empty($erroresStock)) {
            return redirect()->route('carrito')
                ->with('error', 'Algunos productos no tienen stock suficiente')
                ->with('errores_stock', $erroresStock);
        }

        $carrito = $this->carritoService->getCarrito();
        $totales = $this->carritoService->getTotales();

        return view('pedido.checkout', compact('carrito', 'totales'));
    }

    /**
     * Crear pedido y mostrar página de pago
     */
    public function crear(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'correo' => 'required|email|max:100',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
        ]);

        try {
            $pedido = $this->pedidoService->crearPedidoDesdeCarrito($validated);

            // Redirigir a la página de pago
            return redirect()->route('pedido.pago', $pedido->id)
                ->with('success', 'Pedido creado exitosamente');

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Mostrar página de simulación de pago
     */
    public function pago(Pedido $pedido)
    {
        // Verificar que el pedido esté pendiente
        if ($pedido->estado !== 'pendiente') {
            return redirect()->route('pedido.confirmacion', $pedido->id);
        }

        // Obtener información de recargos
        $recargos = $this->pedidoService->obtenerInfoRecargos($pedido->monto_total);

        return view('pedido.pago', compact('pedido', 'recargos'));
    }

    /**
     * Procesar pago (simulado)
     */
    public function procesarPago(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'medio_pago' => 'required|in:efectivo,debito,credito,transferencia',
        ], [
            'medio_pago.required' => 'Debe seleccionar un medio de pago',
        ]);

        try {
            $this->pedidoService->confirmarPago($pedido, $validated['medio_pago']);

            return redirect()->route('pedido.confirmacion', $pedido->id)
                ->with('success', '¡Pago confirmado exitosamente!');

        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mostrar confirmación del pedido
     */
    public function confirmacion(Pedido $pedido)
    {
        $pedido->load('items.producto');
        
        return view('pedido.confirmacion', compact('pedido'));
    }

    /**
     * Buscar pedido por código
     */
    public function buscar(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|min:8|max:20',
        ], [
            'codigo.required' => 'El código es obligatorio',
            'codigo.min' => 'El código debe tener al menos 8 caracteres',
            'codigo.max' => 'El código debe tener maximo 20 caracteres',
        ]);

        $codigo = strtoupper(trim($validated['codigo']));
        $pedido = $this->pedidoService->buscarPedido($codigo);
        
        if (!$pedido) {
            return back()->with('error', 'No se encontró ningún pedido con ese código');
        }

        return redirect()->route('pedido.detalle', $pedido->id);
    }
    /**
     * Vista de búsqueda
     */
    public function mostrarBuscar()
    {
        return view('pedido.buscar');
    }

    /**
     * Ver detalle del pedido
     */
    public function detalle($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->load('items.producto');
        
        return view('pedido.detalle', compact('pedido'));
    }

    /**
     * Mis pedidos (por correo)
     */
    public function misPedidos(Request $request)
    {
        $validated = $request->validate([
            'correo' => 'required|email',
        ]);

        $pedidos = $this->pedidoService->obtenerPedidosPorCorreo($validated['correo']);

        return view('pedido.mis-pedidos', compact('pedidos'));
    }
    
    /**
    * Calcular monto con recargo (AJAX)
    */
    public function calcularRecargo(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'medio_pago' => 'required|in:efectivo,debito,credito,transferencia',
        ]);

        $montos = $this->pedidoService->calcularMontoFinal(
            $pedido->monto_total, 
            $validated['medio_pago']
        );

        return response()->json($montos);
    }

    public function descargarComprobante($id)
    {
        $pedido = Pedido::findOrFail($id);

        if(!in_array($pedido->estado, ['pagado', 'preparando', 'listo'])){
            return back()->with('error', 'El comprobante de su pedido aun no se encuentra listo.');
        }

        $pdf = Pdf::loadView('pedido.comprobante', compact('pedido'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Comprobante_{$pedido->id}-ElFunito.pdf");
    }
}