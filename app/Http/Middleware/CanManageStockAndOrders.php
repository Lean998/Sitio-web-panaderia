<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanManageStockAndOrders
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === 'customer') {
            $allowedRoutes = [
                'admin.pedidos.index',
                'admin.pedidos.show',
                'admin.pedidos.cambiar-estado',
                'admin.pedidos.cancelar',
                'admin.stock.index',
                'admin.stock.actualizar',
                'admin.stock.actualizar-multiple',
            ];

            if (in_array($request->route()->getName(), $allowedRoutes)) {
                return $next($request);
            }
        }

        return redirect('/')->with('error', 'Acceso no autorizado.');
    }
}