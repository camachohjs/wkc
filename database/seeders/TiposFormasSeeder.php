<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposFormasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposFormas = [
            ['nombre' => 'BASICA (KATA Y/O COMBATE)', 'descripcion' => null],
            ['nombre' => 'CATEGORIA ADICIONAL', 'descripcion' => null],
            ['nombre' => 'C. ESPECIAL O CUARTETAS', 'descripcion' => null],
            ['nombre' => 'C. ESPECIAL Y CUARTETAS', 'descripcion' => null],
            ['nombre' => 'C. ESPECIAL MAS 1 CATEGORIA', 'descripcion' => null],
            ['nombre' => 'C. ESPECIAL MAS 2 CATEGORIAS', 'descripcion' => null],
            ['nombre' => 'EQUIPOS COMBATE', 'descripcion' => null],
            ['nombre' => 'PASE DE COACH', 'descripcion' => null],
            ['nombre' => 'PASE DE ESPECTADOR', 'descripcion' => null],
        ];

        DB::table('tipos_formas')->insert($tiposFormas);
    }
}
