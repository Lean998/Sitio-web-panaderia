<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Producto;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    
    {
        // Definimos categorías y tipos
        $categorias = ['Panaderia', 'Pasteleria', 'Salados'];
        $adjetivos = ['artesanal', 'casero', 'fresco', 'recién horneado', 'relleno', 'glaseado'];

        $tipos = [
            'Panaderia' => ['Medialunas', 'Pan integral', 'Baguette'],
            'Pasteleria' => ['Tortas', 'Tartas', 'Postres'],
            'Salados'   => ['Empanadas', 'Pizzetas', 'Facturas saladas'],
        ];

        $nombres = [
            'Panaderia' => [
                'Medialunas de manteca', 'Medialunas de grasa', 'Pan de campo',
                'Baguette', 'Pan integral', 'Pan casero', 'Tortitas negras',
                'Chipá', 'Criollitos'
            ],
            'Pasteleria' => [
                'Torta rogel', 'Chocotorta', 'Lemon pie', 'Tarta de frutilla',
                'Brownie con nuez', 'Tarta de manzana', 'Pastafrola de membrillo',
                'Alfajores de maicena'
            ],
            'Salados' => [
                'Empanadas de carne', 'Empanadas de jamón y queso',
                'Pizzetas de mozzarella', 'Fugazzeta individual',
                'Facturas saladas', 'Chipa relleno', 'Pan relleno con jamón'
            ]
        ];

        $categoria = $this->faker->randomElement($categorias);
        $tipo = $this->faker->randomElement($tipos[$categoria]);

        $nombre = $this->faker->randomElement($nombres[$categoria]);

        // Posibles unidades de venta
        $unidadesVenta = ['unidad', 'docena', 'media_docena', 'kg'];
        $unidadVenta = $this->faker->randomElement($unidadesVenta);

        switch ($unidadVenta) {
            case 'kg':
                $precio = $this->faker->randomFloat(2, 500, 2000); // precio por kg
                $cantidad = $this->faker->randomFloat(2, 1, 20); // 1 a 20 kg
                break;
            case 'docena':
                $precio = $this->faker->randomFloat(2, 800, 3000); // precio por docena
                $cantidad = $this->faker->numberBetween(1, 15); // docenas
                break;
            case 'media_docena':
                $precio = $this->faker->randomFloat(2, 400, 1500); // precio por media docena
                $cantidad = $this->faker->numberBetween(1, 20); // medias docenas
                break;
            default: // unidad
                $precio = $this->faker->randomFloat(2, 100, 800); // precio por unidad
                $cantidad = $this->faker->numberBetween(1, 50);
                break;
        }

        return [
            'nombre' => $nombre,
            'descripcion' => $this->faker->sentence(),
            'categoria' => $categoria,
            'tipo' => $tipo,
            'precio' => $precio,
            'unidad_venta' => $unidadVenta,
            'imagen' => 'default.jpg',
            'cantidad' => $cantidad,
        ];
    }
}
