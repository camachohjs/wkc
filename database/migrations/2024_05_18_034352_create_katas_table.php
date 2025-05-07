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
        Schema::create('katas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participante_id');
            $table->string('estado')->default('pendiente');
            $table->integer('order_position')->nullable();
            $table->float('calificacion_1');
            $table->float('calificacion_2');
            $table->float('calificacion_3');
            $table->float('calificacion_nueva_1');
            $table->float('calificacion_nueva_2');
            $table->float('calificacion_nueva_3');
            $table->integer('ronda')->nullable();
            
            $table->foreign('participante_id')->references('id')->on('registros_torneos');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katas');
    }
};
