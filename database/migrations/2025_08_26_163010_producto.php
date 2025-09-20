<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['Panaderia', 'Pasteleria', 'Salados']);
            $table->string('tipo', 50); // Postres, Tortas, Tartas, Medialunas, etc.
            $table->decimal('precio', 10, 2);
            $table->enum('unidad_venta', ['unidad', 'docena', 'media_docena', 'kg'])->default('unidad');
            $table->decimal('cantidad', 8, 2)->default(0);
            $table->string('imagen')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
