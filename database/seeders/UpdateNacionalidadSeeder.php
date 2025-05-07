<?php

namespace Database\Seeders;

use App\Models\Alumno;
use App\Models\Maestro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateNacionalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Alumno::whereNull('nacionalidad')->update(['nacionalidad' => 'Mexico']);
        Maestro::whereNull('nacionalidad')->update(['nacionalidad' => 'Mexico']);
    }
}
