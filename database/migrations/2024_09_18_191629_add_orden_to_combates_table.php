<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdenToCombatesTable extends Migration
{
    public function up()
    {
        Schema::table('combates', function (Blueprint $table) {
            $table->integer('orden')->nullable()->after('ronda');
        });
    }

    public function down()
    {
        Schema::table('combates', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
}
