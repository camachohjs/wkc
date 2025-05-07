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
            $table->string('area')->nullable()->after('torneo_id');
            $table->dateTime('horario')->nullable()->after('area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categoria_torneo', function (Blueprint $table) {
            $table->dropColumn(['area', 'horario']);
        });
    }
};
