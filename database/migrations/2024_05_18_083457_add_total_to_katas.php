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
        Schema::table('katas', function (Blueprint $table) {
            $table->float('total')->nullable();
            $table->float('total_nuevo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('katas', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('total_nuevo');
        });
    }
};
