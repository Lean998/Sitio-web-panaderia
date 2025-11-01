<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProductoService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class AdminController extends Controller
{
    protected ProductoService $productService;

    public function __construct()
    {
        $this->productService = new ProductoService();
    }

    public function productos(Request $request, $categoria = null)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

        $productos = $this->productService->filtrarProductos([
            'categoria' => $categoria,
            'buscar' => $request->buscar ?? null,
            'tipo' => $request->tipo ?? null,
            'orden' => $request->orden ?? null,
        ]);
        
        $tipos = [
            'Panaderia' => ['Medialunas', 'Pan integral', 'Baguette', 'Tortitas', 'Pan', 'Pan casero'],
            'Pasteleria' => ['Tortas', 'Tartas', 'Postres'],
            'Salados'   => ['Empanadas', 'Pizzetas', 'Facturas saladas'],
            'Todos'     => ['Medialunas', 'Pan integral', 'Baguette', 'Tortas', 'Tartas', 'Postres', 'Empanadas', 'Pizzetas', 'Facturas saladas', 'Pan', 'Pan casero', 'Tortitas'],
        ];

        return view('admin.productos.index', ['productos' => $productos, 'categoriaKey' => $categoria ? ucfirst($categoria) : 'Todos', 'tipos' => $tipos]);
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|min:3|max:100',
        'email' => 'required|email|unique:users,email|max:100',
        'password' => 'required|string|min:6|confirmed',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Empleado creado correctamente.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'No se pudo crear el empleado. Revisa los datos.')
                ->withInput();
        }
    }

}