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
        Schema::create('rankings_torneos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_torneo')->nullable();
            $table->unsignedBigInteger('puntos');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('alumno_id')->nullable();
            $table->unsignedBigInteger('maestro_id')->nullable();
            $table->unsignedBigInteger('torneo_id')->nullable();
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->foreign('maestro_id')->references('id')->on('maestros')->onDelete('cascade');
            $table->foreign('torneo_id')->references('id')->on('torneos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings_torneos');
    }
};
