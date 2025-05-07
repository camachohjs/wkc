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
        Schema::table('formas', function (Blueprint $table) {
            $table->unsignedBigInteger('tipos_formas_id')->nullable()->after('id');
            $table->foreign('tipos_formas_id')->references('id')->on('tipos_formas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('formas', function (Blueprint $table) {
            $table->dropForeign(['tipos_formas_id']);
            $table->dropColumn('tipos_formas_id');
        });
    }
};
