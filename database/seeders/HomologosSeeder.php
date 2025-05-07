<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomologosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categorias = Categoria::all();
        
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
