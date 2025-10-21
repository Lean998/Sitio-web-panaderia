<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PedidoService;
use App\Models\CarritoItem;
use App\Models\FavoritoItem;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

        // ESTADÍSTICAS GENERALES
        $stats = [
            'total_productos' => Producto::count(),
            'productos_disponibles' => Producto::where('cantidad', '>', 0)->count(),
            'productos_sin_stock' => Producto::where('cantidad', '=', 0)->count(),
            'valor_inventario' => Producto::sum(DB::raw('precio * cantidad')),
        ];

        // PRODUCTOS POR CATEGORÍA
        $productosPorCategoria = Producto::select('categoria', DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->get();

        // PRODUCTOS CON BAJO STOCK (menos de 10 unidades)
        $productosBajoStock = Producto::where('cantidad', '<', 10)
            ->where('cantidad', '>', 0)
            ->orderBy('cantidad', 'asc')
            ->limit(10)
            ->get();

        // PRODUCTOS SIN STOCK
        $productosSinStock = Producto::where('cantidad', '=', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $categorias = ['Panaderia', 'Pasteleria', 'Salados'];

        // VALOR POR CATEGORÍA
        $valorPorCategoriaDB = Producto::
        select(
            'categoria',
            DB::raw('COALESCE(SUM(precio * cantidad), 0) as valor_total'),
            DB::raw('COALESCE(SUM(cantidad), 0) as cantidad_total')
        )
        ->groupBy('categoria')
        ->get()
        ->keyBy('categoria');

        $valorPorCategoria = collect($categorias)->map(function ($cat) use ($valorPorCategoriaDB) {
        return (object) [
            'categoria' => $cat,
            'valor_total' => isset($valorPorCategoriaDB[$cat]) ? $valorPorCategoriaDB[$cat]->valor_total : 0,
            'cantidad_total' => isset($valorPorCategoriaDB[$cat]) ? $valorPorCategoriaDB[$cat]->cantidad_total : 0,
        ];
        });

        // ACTIVIDAD RECIENTE (últimas actualizaciones)
        $actividadReciente = Producto::orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        $pedidoService = new PedidoService();
        $masFavoritos = FavoritoItem::getMasAgregados();
        $masAgregadosCarrito = CarritoItem::getMasAgregados();
        $masVendidos = $pedidoService->productosMasVendidos(10);

        return view('admin.dashboard', compact(
            'stats',
            'productosPorCategoria',
            'productosBajoStock',
            'productosSinStock',
            'valorPorCategoria',
            'actividadReciente',
            'masFavoritos',
            'masAgregadosCarrito',
            'masVendidos'
        ));
    }
}