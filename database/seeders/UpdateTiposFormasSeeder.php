<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateTiposFormasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // id_forma = [1,2,3,4,5,6,7,8,9,10,11,12,13,15,16,17,18,19,20,21,22,23,26,28,29,30,31,33] => tipo_forma_id = 1
        $formasTipo1 = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 15, 16, 17, 18, 19, 20, 21, 22, 23, 26, 28, 29, 30, 31, 33];
        DB::table('formas')->whereIn('id', $formasTipo1)->update(['tipos_formas_id' => 1]);

        // id_forma = [24, 25] => tipo_forma_id = 3
        $formasTipo3 = [24, 25];
        DB::table('formas')->whereIn('id', $formasTipo3)->update(['tipos_formas_id' => 3]);

        // id_forma = [14, 27, 32, 34] => tipo_forma_id = 7
        $formasTipo7 = [14, 27, 32, 34];
        DB::table('formas')->whereIn('id', $formasTipo7)->update(['tipos_formas_id' => 7]);
    }
}
