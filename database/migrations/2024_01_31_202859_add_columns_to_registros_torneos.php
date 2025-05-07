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
        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->unsignedBigInteger('maestro_id')->nullable()->after('alumno_id');
            $table->foreign('maestro_id')->references('id')->on('maestros')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->dropForeign(['maestro_id']);
            $table->dropColumn('maestro_id');
        });
    }
};
