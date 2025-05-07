<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TitanesCombateSeeder extends Seeder
{
    public function run()
    {
        $nombres = [
            "SEBASTIAN MANZO",
            "JOSE ARELLANO",
            "YAEL TAPIA",
            "LEON MANZANO",
            "MAX RIVERA",
            "IVAN BARRIOS",
            "LIAT TAPIA",
            "LIAN TAPIA",
            "SANTIAGO ALVAREZ",
            "XIMENA HILARIO",
            "GEOVANNA LOPEZ",
            "KEVIN ORTEGA",
            "SAID CHAVEZ",
            "ANGEL ROLDAN",
            "MICHELLE MONTES",
            "SHUERLYN BELLO",
            "GABY CERVANTES",
            "FABIOLA HERNANDEZ",
            "SAHIAN CORREJO",
            "AMBAR ROLDAN",
            "JUQUILA VALDEZ",
            "JESSICA CORIA",
            "MAURICIO MARTINEZ",
            "IVAN SILVA",
            "JESUS CERVANTES",
            "KEVIN MEZA",
        ];
        

        foreach ($nombres as $nombreCompleto) {
            // Dividir el nombre completo en partes
            $partesNombre = explode(' ', $nombreCompleto);
            $nombre = array_shift($partesNombre);
            $apellidos = implode(' ', $partesNombre);

            // Crear el email basado en el nombre y apellidos
            $email = strtolower($nombre . '_' . str_replace(' ', '_', $apellidos) . '@prueba.com');

            // Determinar el género basado en el nombre
            $genero = $this->determinarGenero($nombre);

            // Crear el usuario
            $user = User::create([
                'name' => $nombreCompleto,
                'email' => $email,
                'password' => Hash::make('password'), // Puedes cambiar la contraseña por defecto si lo deseas
            ]);

            // Crear el alumno asociado
            Alumno::create([
                'user_id' => $user->id,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'email' => $email,
                'fec' => '2000-01-01',
                'cinta' => 'Negra',
                'telefono' => '1',
                'peso' => '1',
                'estatura' => '1',
                'genero' => $genero,
            ]);
        }
    }

    private function determinarGenero($nombre)
    {
        $nombre = strtolower($nombre);
        $generosFemeninos = ['brenda', 'gabriela', 'denise', 'cindy', 'teresa', 'leslie', 'tamara', 'evelin', 'noemi', 'alma', 'ivana', 'camila', 'victoria', 'yuri', 'isabela', 'adriana', 'paola', 'lilia', 'vanessa', 'phanie', 'cassandra', 'karla', 'lizzeth', 'karen', 'angela', 'itzel', 'ximena', 'melany', 'fernanda', 'alexa', 'isa', 'mariana', 'aitana', 'paulina', 'carolina', 'jimena', 'dulce', 'daniela', 'natalia', 'wendy', 'carolina', 'stephanie', 'gabriela', 'jesselin', 'shinka', 'vivian', 'abigail', 'ana', 'mila', 'kyra', 'evelyn', 'maroon'];

        return in_array($nombre, $generosFemeninos) ? 'femenino' : 'masculino';
    }
}
