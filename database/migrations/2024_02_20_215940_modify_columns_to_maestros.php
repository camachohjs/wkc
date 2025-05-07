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
        Schema::table('maestros', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
            $table->date('fec')->nullable()->change();
            $table->string('cinta')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->integer('peso')->nullable()->change();
            $table->integer('estatura')->nullable()->change();
            $table->string('genero')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maestros', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->date('fec')->nullable(false)->change();
            $table->string('cinta')->nullable(false)->change();
            $table->string('telefono')->nullable(false)->change();
            $table->integer('peso')->nullable(false)->change();
            $table->integer('estatura')->nullable(false)->change();
            $table->string('genero')->nullable(false)->change();
        });
    }
};
