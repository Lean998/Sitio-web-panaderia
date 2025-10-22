<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Guardar session_id ANTES del attempt
    $oldSessionId = $request->session()->getId();
    
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials, false)) {
        $newSessionId = $request->session()->getId();
        
        // Si Auth::attempt() cambió el session_id
        if ($oldSessionId !== $newSessionId) {
            Log::warning("Login cambió session_id: $oldSessionId -> $newSessionId");
            
            // Migrar carrito y favoritos
            DB::table('carritos')
                ->where('session_id', $oldSessionId)
                ->update(['session_id' => $newSessionId]);
            
            DB::table('favoritos')
                ->where('session_id', $oldSessionId)
                ->update(['session_id' => $newSessionId]);
        }
        
        if (Auth::user()->role === 'admin') {
            session()->put('admin_in', true);
            return redirect()->intended(route('admin.dashboard'));
        }
        
        Auth::logout();
        return back()->withErrors([
            'email' => 'No tienes permisos de administrador.',
        ]);
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden.',
    ]);
    }

    public function logout(Request $request){
        Auth::logout();
        session()->forget('admin_in');
        return redirect()->route('admin.login')->with('success', 'Sesión cerrada correctamente');
    }
}
