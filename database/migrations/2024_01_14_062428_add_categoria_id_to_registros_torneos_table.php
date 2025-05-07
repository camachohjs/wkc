<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriaIdToRegistrosTorneosTable extends Migration
{
    public function up()
    {
        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_id')->nullable()->after('torneo_id');
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    public function down()
    {
        Schema::table('registros_torneos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
        });
    }
}
