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
        Schema::table('categoria_torneo', function (Blueprint $table) {
            $table->unsignedBigInteger('ganador_id')->nullable()->after('horario');
            $table->foreign('ganador_id')->references('id')->on('registros_torneos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categoria_torneo', function (Blueprint $table) {
            $table->dropForeign(['ganador_id']);
            $table->dropColumn('ganador_id');
        });
    }
};
