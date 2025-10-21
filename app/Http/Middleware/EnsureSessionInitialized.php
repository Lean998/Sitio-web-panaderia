<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Favoritos;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class EnsureSessionInitialized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->session()->getId();
        
        // Inicializar favoritos si no existe
        if (!$request->session()->has('carrito_id')) {
            $carrito = Carrito::getCarritoPorSession($sessionId)->load('items.producto');
            $request->session()->put('carrito_id', $carrito->id);
        }
        // Inicializar favoritos si no existe
        if (!$request->session()->has('favoritos_id')) {
            $favoritos = Favoritos::getFavoritosPorSession($sessionId);
            $request->session()->put('favoritos_id', $favoritos->id);
        }
        if (!Auth::check() && !session()->has('admin_in')) {
            session()->put('admin_in', false);
        }
        
        return $next($request);
    }
}
