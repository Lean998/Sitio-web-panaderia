<?php
// app/Http/Controllers/Admin/AdminPedidoController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Object_;

class AdminPedidoController extends Controller
{
    protected PedidoService $pedidoService;

    public function __construct()
    {
        $this->pedidoService = new PedidoService();
    }

    /**
     * Lista de todos los pedidos con filtros
     */
    public function index(Request $request)
    {
        $query = Pedido::with(['items.producto'])->orderBy('created_at', 'desc');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por fecha
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // Búsqueda por código o nombre
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('codigo_pedido', 'like', "%{$buscar}%")
                ->orWhere('codigo_retiro', 'like', "%{$buscar}%")
                ->orWhere('nombre', 'like', "%{$buscar}%")
                ->orWhere('correo', 'like', "%{$buscar}%");
            });
        }

        $pedidos = $query->paginate(15);

        // Estadísticas rápidas
        $stats = [
            'total' => Pedido::count(),
            'pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'pagados' => Pedido::where('estado', 'pagado')->count(),
            'preparando' => Pedido::where('estado', 'preparando')->count(),
            'listos' => Pedido::where('estado', 'listo')->count(),
            'entregados' => Pedido::where('estado', 'entregado')->count(),
            'cancelados' => Pedido::where('estado', 'cancelado')->count(),
        ];

        return view('admin.pedidos.index', compact('pedidos', 'stats'));
    }

    /**
     * Ver detalle del pedido (admin)
     */
    public function show(Pedido $pedido)
    {
        $pedido->load(['items.producto']);
        
        return view('admin.pedidos.show', compact('pedido'));
    }

    /**
     * Cambiar estado del pedido
     */
    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,pagado,preparando,listo,entregado,cancelado',
        ]);

        try {
            $this->pedidoService->cambiarEstado($pedido, $validated['estado']);
            
            return back()->with('success', "Estado actualizado a: {$validated['estado']}");
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Cancelar pedido
     */
    public function cancelar(Pedido $pedido)
    {
        try {
            $this->pedidoService->cancelarPedido($pedido);
            
            return back()->with('success', 'Pedido cancelado exitosamente');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Estadísticas de ventas
     */
    public function estadisticas()
    {
        // Productos más vendidos
        $productosMasVendidos = $this->pedidoService->productosMasVendidos(10);

        // Ventas por día (últimos 30 días)
        $ventasPorDia = Pedido::whereIn('estado', ['pagado', 'preparando', 'listo', 'entregado'])
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad, SUM(monto_final) as total')
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        $categorias = ['Panaderia', 'Pasteleria', 'Salados'];
        // Ventas por categoría
        $ventas = DB::table('pedido_items')
            ->join('productos', 'pedido_items.producto_id', '=', 'productos.id')
            ->join('pedidos', 'pedido_items.pedido_id', '=', 'pedidos.id')
            ->whereIn('pedidos.estado', ['pagado', 'preparando', 'listo', 'entregado'])
            ->select(
            'productos.categoria',
            DB::raw('SUM(pedido_items.subtotal * pedidos.monto_final / pedidos.monto_total) as total')
            )
            ->groupBy('productos.categoria')
            ->get()
            ->keyBy('categoria'); 
            
            
        $ventasPorCategoria = collect($categorias)->map(function ($cat) use ($ventas) {
            return (object) [
                'categoria' => $cat,
                'total' => $ventas[$cat]->total ?? 0,
            ];
        });
        // Ingresos totales
        $ingresosTotales = Pedido::whereIn('estado', ['pagado', 'preparando', 'listo', 'entregado'])
            ->sum('monto_final');

        $ingresosMes = Pedido::whereIn('estado', ['pagado', 'preparando', 'listo', 'entregado'])
            ->whereMonth('created_at', now()->month)
            ->sum('monto_final');
        
        return view('admin.pedidos.estadisticas', compact(
            'productosMasVendidos',
            'ventasPorDia',
            'ventasPorCategoria',
            'ingresosTotales',
            'ingresosMes'
        ));
    }
}