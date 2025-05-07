<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alumno;

class AgregarCompetidores extends Command
{
    protected $signature = 'competidores:agregar {cantidad=20}';
    protected $description = 'Agrega competidores de prueba';

    public function handle()
    {
        $cantidad = $this->argument('cantidad');

        $this->info("Agregando $cantidad competidores...");

        Alumno::factory()->count($cantidad)->create();

        $this->info("Competidores agregados exitosamente!");
    }
}
