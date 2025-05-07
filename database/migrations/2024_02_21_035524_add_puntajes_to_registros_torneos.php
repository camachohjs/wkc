<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->integer('puntaje')->after('telefono')->nullable();
        });

        DB::statement('UPDATE registros_torneos rt
                       JOIN puntajes p ON rt.id = p.registro_torneo_id
                       SET rt.puntaje = p.puntaje');

        Schema::dropIfExists('puntajes');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::create('puntajes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('registro_torneo_id')->unsigned();
            $table->integer('puntaje')->nullable();
            $table->timestamps();
    
            $table->foreign('registro_torneo_id')->references('id')->on('registros_torneos')->onDelete('cascade');
        });
    
        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->dropColumn('puntaje');
        });
    }
    
};
