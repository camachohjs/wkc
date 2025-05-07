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
        Schema::table('torneo_user', function (Blueprint $table) {
            $table->integer('area')->after('torneo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torneo_user', function (Blueprint $table) {
            $table->dropColumn('area');
        });
    }
};
