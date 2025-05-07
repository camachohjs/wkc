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
        Schema::create('combates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participante1_id');
            $table->unsignedBigInteger('participante2_id');
            $table->unsignedBigInteger('ganador_id')->nullable();
            $table->integer('ronda');
            $table->dateTime('fecha_combate')->useCurrent();
            $table->string('estado')->default('pendiente');
            $table->json('resultados')->nullable();
            
            $table->foreign('participante1_id')->references('id')->on('registros_torneos');
            $table->foreign('participante2_id')->references('id')->on('registros_torneos');
            $table->foreign('ganador_id')->references('id')->on('registros_torneos');
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combates');
    }
};
