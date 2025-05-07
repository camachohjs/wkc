<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class tolocanSeeder extends Seeder
{
    public function run()
    {
        $nombres = [
            "JIMENA ESPINOZA",
            "SARAH BAÑUELOS",
            "ZARA RODRIGUEZ",
            "KENJI MARTINEZ",
            "RODOLFO GARCIA",
            "ROMINA NOHPAL",
            "YAIR RODRIGUEZ",
            "CAMILA BOCARDO",
            "ELIZABETH MORALES",
            "JORGE AYALA",
            "REBEKA RODRIGUEZ",
            "ERICK MORALES",
            "LUIS CASTILLO", 
            "LUIS ROSALES",
            "PEDRO HERNANDEZ",
            "DAVID JIMENEZ",
            "JORGE NUÑEZ",
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
        $generosFemeninos = ['JIMENA', 'SARAH', 'ZARA', 'ROMINA', 'CAMILA', 'ELIZABETH', 'REBEKA'];

        return in_array($nombre, $generosFemeninos) ? 'femenino' : 'masculino';
    }
}
