<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('torneos_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_id')->constrained()->onDelete('cascade');
            $table->foreignId('tipos_formas_id')->constrained()->onDelete('cascade');
            $table->date('fecha');
            $table->decimal('costo_pre_registro', 8, 2);
            $table->decimal('costo_registro', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torneos_precios');
    }
};
