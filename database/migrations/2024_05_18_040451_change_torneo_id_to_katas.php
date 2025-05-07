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
        Schema::table('katas', function (Blueprint $table) {
            $table->float('calificacion_1')->nullable()->change();
            $table->float('calificacion_2')->nullable()->change();
            $table->float('calificacion_3')->nullable()->change();
            $table->float('calificacion_nueva_1')->nullable()->change();
            $table->float('calificacion_nueva_2')->nullable()->change();
            $table->float('calificacion_nueva_3')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('katas', function (Blueprint $table) {
            $table->float('calificacion_1')->nullable(false)->change();
            $table->float('calificacion_2')->nullable(false)->change();
            $table->float('calificacion_3')->nullable(false)->change();
            $table->float('calificacion_nueva_1')->nullable(false)->change();
            $table->float('calificacion_nueva_2')->nullable(false)->change();
            $table->float('calificacion_nueva_3')->nullable(false)->change();
        });
    }
};
