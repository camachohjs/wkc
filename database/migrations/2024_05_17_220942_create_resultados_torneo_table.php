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
        Schema::create('resultados_torneo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('torneo_id');
            $table->foreign('torneo_id')->references('id')->on('torneos');
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');
            $table->unsignedBigInteger('participante_id');
            $table->foreign('participante_id')->references('id')->on('registros_torneos');
            $table->integer('posicion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultados_torneo');
    }
};
