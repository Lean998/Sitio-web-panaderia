<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarritosTable extends Migration
{
    public function up()
    {
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->timestamp('last_activity')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carritos');
    }
}
