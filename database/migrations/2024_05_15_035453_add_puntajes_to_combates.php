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
        Schema::table('combates', function (Blueprint $table) {
            $table->integer('puntos_participante1')->default(0);
            $table->integer('puntos_participante2')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combates', function (Blueprint $table) {
            $table->dropColumn('puntos_participante1');
            $table->dropColumn('puntos_participante2');
        });
    }
};
