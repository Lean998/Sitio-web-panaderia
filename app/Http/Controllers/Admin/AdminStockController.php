<?php
// app/Http/Controllers/Admin/AdminStockController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    /**
     * Mostrar tabla de stock
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        // Filtrar por categoría
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }

        // Buscar por nombre
        if ($request->filled('buscar')) {
            $query->where('nombre', 'like', "%{$request->buscar}%");
        }

        // Ordenar
        $orden = $request->get('orden', 'nombre');
        $direccion = $request->get('direccion', 'asc');
        $query->orderBy($orden, $direccion);

        $productos = $query->paginate(25);

        return view('admin.stock.index', compact('productos'));
    }

    /**
     * Actualizar stock (AJAX)
     */
    public function actualizarStock(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'cantidad' => 'required|numeric|min:0|max:999999',
        ]);

        $producto->cantidad = $validated['cantidad'];
        $producto->save();

        return response()->json([
            'success' => true,
            'message' => 'Stock actualizado',
            'nueva_cantidad' => $producto->cantidad,
        ]);
    }

    /**
     * Actualizar múltiples stocks a la vez
     */
    public function actualizarMultiple(Request $request)
    {
        $validated = $request->validate([
            'stocks' => 'required|array',
            'stocks.*.id' => 'required|exists:productos,id',
            'stocks.*.cantidad' => 'required|numeric|min:0',
        ]);

        $actualizados = 0;
        foreach ($validated['stocks'] as $stock) {
            Producto::where('id', $stock['id'])
                ->update(['cantidad' => $stock['cantidad']]);
            $actualizados++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$actualizados} productos actualizados correctamente.",
        ]);
    }
}