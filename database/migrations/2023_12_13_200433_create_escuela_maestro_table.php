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
        Schema::create('escuela_maestro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('escuela_id');
            $table->unsignedBigInteger('maestro_id');
            $table->timestamps();

            $table->foreign('escuela_id')->references('id')->on('escuelas')->onDelete('cascade');
            $table->foreign('maestro_id')->references('id')->on('maestros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escuela_maestro');
    }
};
