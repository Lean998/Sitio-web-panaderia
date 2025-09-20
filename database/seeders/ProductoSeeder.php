<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Generamos 20 productos de prueba
        Producto::factory()->count(60)->create();
    }
}
