<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Docena de Medialunas de Manteca',
                'descripcion' => 'Deliciosas medialunas hojaldradas con manteca.',
                'categoria' => 'Panaderia',
                'tipo' => 'Medialunas',
                'precio' => 2500.00,
                'unidad_venta' => 'docena',
                'cantidad' => 10.00,
                'imagen_base64' => $this->getBase64Image('medialunas'),
            ],
            [
                'nombre' => 'Medialuna de Manteca',
                'descripcion' => 'Deliciosas medialunas hojaldradas con manteca.',
                'categoria' => 'Panaderia',
                'tipo' => 'Medialunas',
                'precio' => 400.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 60.00,
                'imagen_base64' => $this->getBase64Image('medialunas_individual'),
            ],
            [
                'nombre' => 'Media docena de Medialunas de Manteca',
                'descripcion' => 'Deliciosas medialunas hojaldradas con manteca.',
                'categoria' => 'Panaderia',
                'tipo' => 'Medialunas',
                'precio' => 1250.00,
                'unidad_venta' => 'media_docena',
                'cantidad' => 20.00,
                'imagen_base64' => $this->getBase64Image('medialunas_media_docena'), 
            ],
            [
                'nombre' => 'Torta de Chocolate',
                'descripcion' => 'Torta húmeda de chocolate con ganache.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tortas',
                'precio' => 12000.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 5.00,
                'imagen_base64' => $this->getBase64Image('torta_chocolate'),
            ],
            [
                'nombre' => 'Pan Integral',
                'descripcion' => 'Pan saludable hecho con harina integral.',
                'categoria' => 'Panaderia',
                'tipo' => 'Pan integral',
                'precio' => 1500.00,
                'unidad_venta' => 'kg',
                'cantidad' => 50.00,
                'imagen_base64' => $this->getBase64Image('pan_integral'),
            ],
            [
                'nombre' => 'Tarta de Frutilla',
                'descripcion' => 'Tarta fresca con crema y frutillas naturales.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 9000.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 15.00,
                'imagen_base64' => $this->getBase64Image('tarta_frutilla'),
            ],
            [
                'nombre' => 'Porcion de Tarta de Frutilla',
                'descripcion' => 'Porcion mediana de Tarta fresca con crema y frutillas naturales.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 1200.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 25.00,
                'imagen_base64' => $this->getBase64Image('tarta_frutilla_individual'),
            ],
            [
                'nombre' => 'Pizzetas',
                'descripcion' => 'Pizzetas individuales.',
                'categoria' => 'Salados',
                'tipo' => 'Pizzetas',
                'precio' => 800.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 50.00,
                'imagen_base64' => $this->getBase64Image('pizzetas'),
            ],
            [
                'nombre' => 'Chocotorta',
                'descripcion' => 'Postre frío con galletitas de chocolate y dulce de leche.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Postres',
                'precio' => 8500.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 10.00,
                'imagen_base64' => $this->getBase64Image('chocotorta'),
            ],
            [
                'nombre' => 'Baguette',
                'descripcion' => 'Baguette francesa crujiente.',
                'categoria' => 'Panaderia',
                'tipo' => 'Baguette',
                'precio' => 1200.00,
                'unidad_venta' => 'unidad', 
                'cantidad' => 30.00,
                'imagen_base64' => $this->getBase64Image('baguette'),
            ],
            [
                'nombre' => 'Docena de Facturas Saladas',
                'descripcion' => 'Variedad de facturas saladas.',
                'categoria' => 'Salados',
                'tipo' => 'Facturas saladas',
                'precio' => 600.00,
                'unidad_venta' => 'docena',
                'cantidad' => 25.00,
                'imagen_base64' => $this->getBase64Image('facturas_saladas'),
            ],
            [
                'nombre' => 'Media docena de Facturas Saladas',
                'descripcion' => 'Variedad de facturas saladas.',
                'categoria' => 'Salados',
                'tipo' => 'Facturas saladas',
                'precio' => 300.00,
                'unidad_venta' => 'media_docena',
                'cantidad' => 50.00,
                'imagen_base64' => $this->getBase64Image('facturas_saladas_media_docena'),
            ],
            [
                'nombre' => 'Tarta de Manzana',
                'descripcion' => 'Tarta casera con manzanas frescas y canela.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 9500.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 12.00,
                'imagen_base64' => $this->getBase64Image('tarta_manzana'),
            ],
            [
                'nombre' => 'Porcion de Tarta de Manzana',
                'descripcion' => 'Porcion mediana de Tarta casera con manzanas frescas y canela.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 1200.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 24.00,
                'imagen_base64' => $this->getBase64Image('tarta_manzana_individual'),
            ],
            [
                'nombre' => 'Pan Casero',
                'descripcion' => 'Pan casero tradicional.',
                'categoria' => 'Panaderia',
                'tipo' => 'Pan casero',
                'precio' => 1300.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 40.00,
                'imagen_base64' => $this->getBase64Image('pan_casero'),
            ],
            [
                'nombre' => 'Alfajores de Maicena (Docena)',
                'descripcion' => 'Clásicos alfajores rellenos de dulce de leche y cubiertos de chocolate.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Postres',
                'precio' => 4000.00,
                'unidad_venta' => 'docena',
                'cantidad' => 15.00,
                'imagen_base64' => $this->getBase64Image('alfajores_maicena'),
            ],
            [
                'nombre' => 'Alfajores de Maicena (Media Docena)',
                'descripcion' => 'Clásicos alfajores rellenos de dulce de leche y cubiertos de chocolate.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Postres',
                'precio' => 2000.00,
                'unidad_venta' => 'media_docena',
                'cantidad' => 30.00,
                'imagen_base64' => $this->getBase64Image('alfajores_maicena_media_docena'),
            ],
            [
                'nombre' => 'Alfajores de Maicena (individuales)',
                'descripcion' => 'Clásico alfajor relleno de dulce de leche y cubierto de chocolate.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Postres',
                'precio' => 400.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 180.00,
                'imagen_base64' => $this->getBase64Image('alfajores_maicena_individual'),
            ],
            [
                'nombre' => 'Criollitos (Docena)',
                'descripcion' => 'Criollitos salados perfectos para acompañar.',
                'categoria' => 'Panaderia',
                'tipo' => 'Tortitas',
                'precio' => 450.00,
                'unidad_venta' => 'docena',
                'cantidad' => 40.00,
                'imagen_base64' => $this->getBase64Image('criollitos'),
            ],
            [
                'nombre' => 'Criollitos (Media Docena)',
                'descripcion' => 'Criollitos salados perfectos para acompañar.',
                'categoria' => 'Panaderia',
                'tipo' => 'Tortitas',
                'precio' => 220.00,
                'unidad_venta' => 'media_docena',
                'cantidad' => 80.00,
                'imagen_base64' => $this->getBase64Image('criollitos_media_docena'),
            ],
            [
                'nombre' => 'Tortita Negra',
                'descripcion' => 'Clásica tortita negra con azúcar y anís.',
                'categoria' => 'Panaderia',
                'tipo' => 'Tortitas',
                'precio' => 550.00,
                'unidad_venta' => 'docena',
                'cantidad' => 35.00,
                'imagen_base64' => $this->getBase64Image('tortitas_negras'),
            ],
            [
                'nombre' => 'Pastafrola de Membrillo',
                'descripcion' => 'Tarta tradicional con dulce de membrillo.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 9000.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 18.00,
                'imagen_base64' => $this->getBase64Image('pastafrola_membrillo'),
            ],
            [
                'nombre' => 'Porcion de Pastafrola de Membrillo',
                'descripcion' => 'Porcion mediana de tarta con dulce de Membrillo.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 1200.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 32.00,
                'imagen_base64' => $this->getBase64Image('pastafrola_membrillo_individual'),
            ],
            [
                'nombre' => 'Pastafrola de Batata',
                'descripcion' => 'Tarta tradicional con dulce de Batata.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 9000.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 18.00,
                'imagen_base64' => $this->getBase64Image('pastafrola_batata'),
            ],
            [
                'nombre' => 'Porcion de Pastafrola de Batata',
                'descripcion' => 'Porcion mediana de tarta con dulce de Batata.',
                'categoria' => 'Pasteleria',
                'tipo' => 'Tartas',
                'precio' => 1200.00,
                'unidad_venta' => 'unidad',
                'cantidad' => 32.00,
                'imagen_base64' => $this->getBase64Image('pastafrola_batata_individual'),
            ],
            [
                'nombre' => 'Pan Relleno con Jamón',
                'descripcion' => 'Pan relleno de jamón y queso, ideal para un snack rápido.',
                'categoria' => 'Panaderia',
                'tipo' => 'Pan casero',
                'precio' => 1200.00,
                'unidad_venta' => 'unidad', 
                'cantidad' => 22.00,
                'imagen_base64' => $this->getBase64Image('pan_relleno_jamon'),
            ],
            ];
            

        foreach ($productos as $p) {
            $imagenBase64 = $p['imagen_base64'];
            unset($p['imagen_base64']);

            if ($imagenBase64) {
                $imagenData = explode(',', $imagenBase64);
                $imagenDecoded = base64_decode($imagenData[1] ?? $imagenData[0]);

                $nombreImagen = Str::random(12) . '.webp';
                $rutaImagen = 'productos/' . $nombreImagen;

                Storage::disk('public')->put($rutaImagen, $imagenDecoded);

                $p['imagen'] = $rutaImagen;
            }

            Producto::create($p);
        }
    }

    private function getBase64Image($nombre)
    {
        $path = database_path("seeders/images/{$nombre}.webp");
        if (file_exists($path)) {
            $image = file_get_contents($path);
            $base64 = 'data:image/webp;base64,' . base64_encode($image);
            return $base64;
        }

        // Imagen por defecto si no existe
        return $this->getDefaultImage();
    }

    private function getDefaultImage()
    {
        $path = public_path('images/default-product.webp');
        if (file_exists($path)) {
            $image = file_get_contents($path);
            return 'data:image/webp;base64,' . base64_encode($image);
        }

        return null;
    }
}