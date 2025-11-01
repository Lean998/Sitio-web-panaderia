<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Debes iniciar sesión como administrador');
        }

        
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'customer') {
            abort(403, 'No tienes permisos para acceder a esta área');
        }

        if (!session()->get('admin_in', false)) {
            session()->put('admin_in', true); // Solo si el usuario es admin
        }

        return $next($request);
    }
}


