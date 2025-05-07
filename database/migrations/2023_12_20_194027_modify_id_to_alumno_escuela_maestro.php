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
        Schema::table('escuelas', function (Blueprint $table) {
            $table->string('profesor1')->nullable()->change();
        });
        Schema::table('alumno_escuela_maestro', function (Blueprint $table) {
            $table->string('profesor1')->nullable();
            $table->string('profesor2')->nullable();
            $table->string('profesor3')->nullable();

            /* $table->foreign('profesor1')->references('profesor1')->on('escuelas')->onDelete('cascade');
            $table->foreign('profesor2')->references('profesor2')->on('escuelas')->onDelete('cascade');
            $table->foreign('profesor3')->references('profesor3')->on('escuelas')->onDelete('cascade'); */

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumno_escuela_maestro', function (Blueprint $table) {
            $table->dropForeign(['maestro_id']);
            $table->dropColumn('maestro_id');
        });
    }
};
