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
            $table->dropColumn('profesor2');
            $table->dropColumn('profesor3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escuelas', function (Blueprint $table) {
            //
        });
    }
};
