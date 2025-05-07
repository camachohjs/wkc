<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Torneo;

class TorneoConfiguradoSeeder extends Seeder
{
    public function run()
    {
        Torneo::query()->update(['torneo_configurado' => 1]);

        $this->command->info('Todos los torneos han sido marcados como configurados.');
    }
}
