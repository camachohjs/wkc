<?php

namespace Database\Seeders;

use App\Models\Fusion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuisonHomologoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Fusion::all();
        
        foreach ($categorias as $categoria) {
            if (str_contains($categoria->nombre, '/')) {
                $nombreParts = explode('/', $categoria->nombre);
                $nombreEnEspañol = trim(end($nombreParts)); 
                $categoria->nombre = $nombreEnEspañol;
                $categoria->save();
            }
        }
    }
}
