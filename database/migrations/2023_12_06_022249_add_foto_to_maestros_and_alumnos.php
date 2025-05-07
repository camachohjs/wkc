<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoToMaestrosAndAlumnos extends Migration
{
    public function up()
    {
        Schema::table('maestros', function (Blueprint $table) {
            $table->string('foto')->nullable();
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('foto')->nullable();
        });
    }

    public function down()
    {
        Schema::table('maestros', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('alumnos', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
}
