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
        Schema::create('puntajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registro_torneo_id');
            $table->integer('puntaje');
            $table->timestamps();

            $table->foreign('registro_torneo_id')->references('id')->on('registros_torneos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puntajes');
    }
};
