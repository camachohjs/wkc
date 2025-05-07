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
            $table->unsignedBigInteger('torneo_id')->after('id');
            $table->foreign('torneo_id')->references('id')->on('torneos');
            $table->unsignedBigInteger('categoria_id')->after('torneo_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('combates', function (Blueprint $table) {
            $table->dropForeign(['torneo_id']);
            $table->dropColumn('torneo_id');
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
};
