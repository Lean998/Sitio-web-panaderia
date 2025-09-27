<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Carrito;
use App\Models\Favoritos;
use Symfony\Component\HttpFoundation\Response;

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

        if (!$request->session()->has('carrito')) {
            Carrito::firstOrCreate(
                ['session_id' => $sessionId],
                ['productos' => json_encode([])]
            );

            $carrito = Carrito::getCarrito($sessionId) ?? [];
            $request->session()->put('carrito', $carrito);
        }

        if (!$request->session()->has('favoritos')) {
            Favoritos::firstOrCreate(
                ['session_id' => $sessionId],
                ['productos' => json_encode([])]
            );

            $favoritos = Favoritos::getFavoritos($sessionId) ?? [];
            $request->session()->put('favoritos', $favoritos);
        }
        return $next($request);
    }
}
