<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Alumno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompetidoresTorneosSeeder extends Seeder
{
    public function run()
    {
        $nombres = [
            "Brenda Cuellar Salinas",
            "Noa Tieche",
            "Gabriela Berzunza",
            "Denise Muñoz",
            "Rodrigo Retiz",
            "Balam Najera",
            "Eduardo Castro",
            "Emilio Granados",
            "Juan Manuel Valencia Castillo",
            "Cristian Gomez Fuentes",
            "Cindy Jazmin Peña Sainz",
            "Teresa Barroso",
            "Leslie Marquez",
            "Gerardo Bazan",
            "Rodolfo Castro Duran",
            "Santiago Barroso",
            "Bruno Badillo",
            "Angel Rene Vargas",
            "Jesus Rodriguez",
            "EDGAR REYNA",
            "GERARDO LEON",
            "CASSANDRA MARTINEZ",
            "MEGAN TORRES",
            "YAEL SOLER",
            "FRANCO BRINDESI",
            "FERNANDO RIVAZ",
            "DOMINIC LEDESMA",
            "MAXIMILIANO RIVERA",
            "TAMARA LEON",
            "EVELIN TORRES",
            "ADAN GARCIA",
            "RICARGO SOSA",
            "NICOLAS FORASTIERI",
            "ALAN MARTINEZ",
            "ISRAEL ESQUIVEL",
            "ISRAEL CARRASCO",
            "VANESSA LOPEZ",
            "ALDO CORDERO",
            "ANDRES VEGA",
            "NOEMI MEZA",
            "ALMA VELAZQUEZ",
            "ENRIQUE IRALA",
            "IVANA OROZCO",
            "CAMILA VELAZQUEZ",
            "VICTORIA GODINEZ",
            "YURI GALINDO",
            "BARUSH SANABRIA",
            "SANTIAGO ROMERO",
            "RENE BARRIENTOS",
            "ISABELA ALVARADO",
            "ADRIANA HERNANDEZ",
            "PAOLA RODAS",
            "VANESSA SANCHEZ",
            "NATALIA ESPINOSA",
            "LILIA VILLANUEVA",
            "PHANIE GONZALEZ",
            "KATXON NERI",
            "OSCAR GARCIA",
            "OSCAR GARRIDO",
            "RAUL CAMPUZANO",
            "ELIUTH PEÑA",
            "CESAR GARCIA",
            "FRANCISCO CERVANTES",
            "ALFREDO HERNANDEZ",
            "KARLA ZALDIVAR",
            "LIZZETH SANTOS",
            "CRISTOPHER CHAVEZ",
            "EMANUEL CUEVAS",
            "OSCAR ALCANTARA",
            "ALEXIS MADRID",
            "KAREN VENCES",
            "GIAN BERTRAND",
            "EDGAR AGUIRRE",
            "ITZEL ROCHA",
            "ELIUD ESCALANTE",
            "DIEGO ANGUIANO",
            "ANGELA AGUIRRE",
            "CARLOS CORDOVA",
            "LUIS RODRIGUEZ",
            "IVAN ROCA",
            "RODOLFO CASTRO",
            "XIMENA GONZALEZ",
            "ITZEL SIMIANO",
            "MELANY TEJAMANIL",
            "FERNANDA BARRIOS",
            "ALEXA HERNANDEZ",
            "ISA TEJAS",
            "LEONARDO CASTILLERA",
            "DONOVAN ARENAS",
            "ABRAHAM MORALES",
            "FERNANDO RIAS",
            "MARIANA OLON",
            "AITANA GONZALEZ",
            "PAULINA GARCIA",
            "YURIDIA GALINDO",
            "JESUS EDUARDO",
            "GABRIEL ROLDAN",
            "GABRIEL ORAN",
            "MIGUEL PEREZ",
            "CAROLINA HERNANDEZ",
            "JIMENA GONZALEZ",
            "DULCE GARCIA",
            "CASSANDRA ARTEAGA",
            "DANIELA OROPEZA",
            "MANUEL ARAUJO",
            "DAVID SANCHEZ",
            "ALFONSO GONZALEZ",
            "JORGE ZACARIAS",
            "NATALIA ESPINOZA",
            "WENDY AVILA",
            "ITZEL JUAREZ",
            "CAROLINA SANCHEZ",
            "EDUARDO MARTINEZ",
            "STEPHANIE RODRIGUEZ",
            "SERGIO TRANSITO",
            "GABIELA RODRIGUEZ",
            "YONATAN ALMARAZ",
            "FRANZ BRISEÑO",
            "IVANNA OROZCO",
            "IKER RODRIGUEZ",
            "ALEXANDER MEDINA",
            "SERGIO MEDINA",
            "JESUS TOVAR",
            "JESSELIN FERNANDEZ",
            "MAROON CUEVAS",
            "ABIGAIL GARCIA",
            "XIMENA JIMENEZ",
            "ANGEL GONZALEZ",
            "VLADIMIR TOSCANO",
            "ALEXIS DOMINGUEZ",
            "YAEL MARCOS",
            "ANGEL MENDOZA",
            "ANA GALLARDO",
            "LEON SEGUNDO",
            "KEVIN SALINAS",
            "FERNANDO NAVARRETE",
            "MILA TEJAS",
            "ELYTH PEÑA",
            "JAIRO MARTINEZ",
            "KYRA HERNANDEZ",
            "EVELYN MENDOZA",
            "ROBIN PEÑA",
            "AGUSTIN JIMENEZ",
            "DANIEL ORDAZ",
            "RODOLFO CHAVEZ",
            "ANTONIO AGUILAR",
            "MARIO MARIN",
            "LUIS ALVAREZ",
            "EMILIANO FERNANDEZ"
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
