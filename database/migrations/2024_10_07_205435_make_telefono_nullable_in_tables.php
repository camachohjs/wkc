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
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('telefono')->nullable()->change();
        });

        Schema::table('maestros', function (Blueprint $table) {
            $table->string('telefono')->nullable()->change();
        });

        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->string('telefono')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('telefono')->nullable(false)->change();
        });

        Schema::table('maestros', function (Blueprint $table) {
            $table->string('telefono')->nullable(false)->change();
        });

        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->string('telefono')->nullable(false)->change();
        });
    }
};
