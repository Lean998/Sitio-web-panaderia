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
        Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo_pedido', 20)->unique();
        $table->string('nombre', 100);
        $table->string('correo', 100);
        $table->string('codigo_retiro', 8)->unique();
        $table->enum('estado', [
            'pendiente',     
            'pagado',     
            'preparando',     
            'listo',          
            'entregado',     
            'cancelado'
        ])->default('pendiente');
        
        $table->enum('medio_pago', [
            'efectivo',
            'debito',
            'credito',
            'transferencia',
        ])->nullable();
        
        $table->decimal('monto_total', 10, 2);
        $table->decimal('descuento', 10, 2)->default(0);
        $table->decimal('monto_final', 10, 2);
        $table->timestamp('fecha_pago')->nullable();
        $table->timestamp('fecha_entrega')->nullable();
        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
