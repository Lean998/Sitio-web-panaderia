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
        Schema::create('pedido_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
        $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
        $table->string('nombre_producto', 100);
        $table->string('unidad_venta', 20);
        
        $table->decimal('cantidad', 8, 2);
        $table->decimal('precio_unitario', 10, 2);
        $table->decimal('subtotal', 10, 2);
        $table->timestamps();
        
        $table->index(['producto_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_items');
    }
};
