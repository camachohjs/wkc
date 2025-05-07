<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrosTorneosTable extends Migration
{
    public function up()
    {
        Schema::create('registros_torneos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->foreignId('torneo_id')->constrained()->onDelete('cascade');
            $table->string('cinta');
            $table->float('peso');
            $table->float('estatura');
            $table->string('genero');
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('email');
            $table->date('fec');
            $table->string('telefono');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registros_torneos');
    }
}
