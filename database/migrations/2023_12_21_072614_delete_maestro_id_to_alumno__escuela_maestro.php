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
        Schema::table('alumno_escuela_maestro', function (Blueprint $table) {
            $table->dropForeign(['maestro_id']);
            $table->dropColumn('maestro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumno_escuela_maestro', function (Blueprint $table) {
            
            $table->unsignedBigInteger('maestro_id');
        });
    }
};
