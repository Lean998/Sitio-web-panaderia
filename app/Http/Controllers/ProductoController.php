<?php

namespace App\Http\Controllers;


use App\Services\ProductoService;
use Illuminate\Http\Request; 
use Exception;
use App\Exceptions\ProductoNoEncontradoException;
use App\Services\FavoritosService;
use App\Services\CarritoService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
class ProductoController extends Controller
{
    protected ProductoService $productService;
    protected CarritoService $carritoService;
    protected FavoritosService $favoritosService;

    public function __construct()
    {
        $this->productService = new ProductoService();
        $this->favoritosService = new FavoritosService();
        $this->carritoService = new CarritoService();
    }

    public function index(Request $request, $categoria = null)
    {
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

        return view('productos', ['productos' => $productos, 'categoriaKey' => $categoria ? ucfirst($categoria) : 'Todos', 'tipos' => $tipos]);
    }

    public function getProducto($productoId)
    {
        
        try {
            $producto = $this->productService->getProducto($productoId);
            if(session()->get('admin_in')){
                return view('admin.productos.producto', compact('producto'));
            }
            return view('producto', compact('producto'));
        } catch (ProductoNoEncontradoException $e) {
            return redirect()->route('productos')->with('error', $e->getMessage());
        }
    }

    public function agregarYComprar(Request $request, $productoId)
    {
        try {
            $producto = $this->productService->getProducto($productoId);
            $cantidad = $request->filled('cantidad') ? (float)$request->cantidad : 1;

            if ($request->filled('agregar')) {
                $this->productService->validarStock($producto, $cantidad);
                $this->carritoService->agregarUnidad($producto, $cantidad);
                return redirect()->route('carrito')->with('success', 'Agregaste '.$cantidad. ' '.$producto->unidad_venta. ' de '. $producto->nombre. ' al carrito.');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    } 


    public function crearProducto(Request $request){
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

        $tipos = [
            'Panaderia' => ['Medialunas', 'Pan integral', 'Baguette', 'Tortitas', 'Pan', 'Pan casero'],
            'Pasteleria' => ['Tortas', 'Tartas', 'Postres'],
            'Salados'   => ['Empanadas', 'Pizzetas', 'Facturas saladas'],
        ];
        return view('admin.productos.new-producto', compact('tipos'));
    }

    public function postCrearProducto(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

        $messages = [
        'nombre.required' => 'Por favor ingresa el nombre del producto.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
        'precio.required' => 'Por favor ingresa el precio del producto.',
        'precio.min' => 'Por favor ingresa un precio mayor a 0.',
        'precio.max' => 'Por favor ingrese un precio valido.',
        'cantidad.required' => 'Por favor ingresa la cantidad del producto.',
        'cantidad.min' => 'Por favor ingresa una cantidad mayor a 0.',
        'cantidad.max' => 'Por favor ingrese una cantidad valida.',
        'categoria.required' => 'Por favor selecciona una categoria.',
        'tipo.required' => 'Por favor selecciona un tipo de producto.',
        'descripcion.required' => 'Por favor escribe una descripcion.',
        'descripcion.min' => 'La descripcion debe tener al menos 5 caracteres.',
        'descripcion.max' => 'La descripcion no puede tener más de 1000 caracteres.',
        'imagen_base64.required' => 'Por favor sube una imagen del producto.',
        ];

        // Validación
        $validated = $request->validate([
        'nombre' => 'required|min:3|max:100',
        'categoria' => 'required|in:Panaderia,Pasteleria,Salados',
        'tipo' => 'required',
        'cantidad' => 'required|numeric|min:0.01|max:99999.99',
        'unidad' => 'required|in:unidad,docena,media_docena,kg',
        'precio' => 'required|numeric|min:0.01|max:99999999.99',
        'descripcion' => 'required|min:5|max:1000',
        'imagen_base64' => 'required',
        ], $messages);

        // Procesar la imagen
        $imagenBase64 = $request->input('imagen_base64');
        
        // Decodificar base64
        $imagenData = explode(',', $imagenBase64);
        $imagenDecoded = base64_decode($imagenData[1]);
        
        // Generar nombre único 
        $nombreImagen = Str::random(12) . '.webp';
        $rutaImagen = 'productos/' . $nombreImagen;
        
        //Guardar imagen
        $path = public_path('storage/' . $rutaImagen);
        $dir = dirname($path);      

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($path, $imagenDecoded);
        
        try {
            $newProducto = $this->productService->crearProducto([$request->all(), $rutaImagen]);
            if($newProducto && isset($newProducto->id)){
                return redirect()->route('admin.productos')->with('success', 'Producto creado con éxito.');
            } else {
                return back()->with('error', 'Error al crear el producto. Intenta nuevamente.');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function eliminarProducto(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

        $productoId = $request->producto_id;
        
        if(!$productoId || !is_numeric($productoId)){
            return back()->with('error', 'No se encontro el producto. Intenta nuevamente.');
        }

        try {
            $producto = $this->productService->getProducto($productoId);
            try{
                if(!$this->carritoService->eliminarUnidad($producto, 99999)){
                    return back()->with('error', 'Error al eliminar el producto del carrito. Intenta nuevamente.');
                }
            } catch (Exception $e){
                // Se arrojara una expecion 'ProductoEliminadoException' pero no interesa hacer algo con ella aqui.
            }
            
            try{
                if(!$this->favoritosService->eliminarProducto($productoId)){
                    return back()->with('error', 'Error al eliminar el producto de favoritos. Intenta nuevamente.');
                }
            } catch (Exception $e){
                // Se arrojara una expecion pero no interesa hacer algo con ella aqui.
            }

            $resultado = $this->productService->eliminarProducto($productoId);
            if($resultado){
                $path = public_path('storage/' . $producto->imagen);
                // Borrar imagen del sistema de archivos
                $this->productService->eliminarImagenProducto($producto->imagen);
                return redirect()->route('admin.productos')->with('success', 'Producto eliminado con éxito.');
            } else {
                return back()->with('error', 'Error al eliminar el producto. Intenta nuevamente.');
            }
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function getEditarProducto($producto)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }

        try {
            $producto = $this->productService->getProducto($producto);
            $tipos = [
            'Panaderia' => ['Medialunas', 'Pan integral', 'Baguette', 'Tortitas', 'Pan', 'Pan casero'],
            'Pasteleria' => ['Tortas', 'Tartas', 'Postres'],
            'Salados'   => ['Empanadas', 'Pizzetas', 'Facturas saladas'],
        ];
            return view('admin.productos.editar-producto', compact('producto', 'tipos'));
        } catch (ProductoNoEncontradoException $e) {
            return redirect()->route('admin.productos')->with('error', $e->getMessage());
        }
    }

    public function editarProducto(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin' || !session('admin_in')) {
            return redirect()->route('admin.login')->with('error', 'Debes iniciar sesión como administrador.');
        }
        
        $productoId = $request->input('producto_id');
        $producto = $this->productService->getProducto($productoId);
        
        if(!$producto || !is_numeric($productoId)){
            return back()->with('error', 'No se encontro el producto. Intenta nuevamente.');
        }

        $messages = [
        'nombre.required' => 'Por favor ingresa el nombre del producto.',
        'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
        'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
        'precio.required' => 'Por favor ingresa el precio del producto.',
        'precio.min' => 'Por favor ingresa un precio mayor a 0.',
        'precio.max' => 'Por favor ingrese un precio valido.',
        'cantidad.required' => 'Por favor ingresa la cantidad del producto.',
        'cantidad.min' => 'Por favor ingresa una cantidad mayor a 0.',
        'cantidad.max' => 'Por favor ingrese una cantidad valida.',
        'categoria.required' => 'Por favor selecciona una categoria.',
        'tipo.required' => 'Por favor selecciona un tipo de producto.',
        'descripcion.required' => 'Por favor escribe una descripcion.',
        'descripcion.min' => 'La descripcion debe tener al menos 5 caracteres.',
        'descripcion.max' => 'La descripcion no puede tener más de 1000 caracteres.',
        ];

        // Validación
        $validated = $request->validate([
        'nombre' => 'required|min:3|max:100',
        'categoria' => 'required|in:Panaderia,Pasteleria,Salados',
        'tipo' => 'required',
        'cantidad' => 'required|numeric|min:0.01|max:99999.99',
        'unidad' => 'required|in:unidad,docena,media_docena,kg',
        'precio' => 'required|numeric|min:0.01|max:99999999.99',
        'descripcion' => 'required|min:5|max:1000',
        ], $messages);

        if($request->has('imagen_base64') && $request->input('imagen_base64') != null){
            // Procesar la imagen
            $imagenBase64 = $request->input('imagen_base64');
            
            // Decodificar base64
            $imagenData = explode(',', $imagenBase64);
            $imagenDecoded = base64_decode($imagenData[1]);
            
            // Generar nombre único
            $nombreImagen = Str::random(12) . '.webp';
            $rutaImagen = 'productos/' . $nombreImagen;
            
            //Guardar nueva imagen
            $path = public_path('storage/' . $rutaImagen);
            $dir = dirname($path);      

            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents($path, $imagenDecoded);

            // Borrar imagen anterior del sistema de archivos
            $this->productService->eliminarImagenProducto($producto->imagen);
            }

        try {
            $data = $request->all();
            $data['imagen'] = $rutaImagen ?? null;
            $this->productService->editarProducto($data, $productoId);
            return redirect()->route('admin.productos')->with('success', 'Producto creado con éxito.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        
    }
}