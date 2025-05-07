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
        Schema::create('fusiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('genero');
            $table->float('peso_minimo')->nullable();
            $table->float('peso_maximo')->nullable();
            $table->integer('edad_minima');
            $table->integer('edad_maxima');
            $table->text('division');
            $table->string('cinta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fusiones');
    }
};
