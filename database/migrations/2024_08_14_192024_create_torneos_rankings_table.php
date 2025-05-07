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
        Schema::create('torneos_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torneo_id')->constrained()->onDelete('cascade');
            $table->string('ranking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torneos_rankings');
    }
};
