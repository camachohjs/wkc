<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Fusion;
use App\Models\Categoria;

class FusionCategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $fusiones = Fusion::all();

        foreach ($fusiones as $fusion) {
            // Obtener todas las divisiones de la fusion
            $divisiones = collect(explode(' / ', $fusion->division))
                            ->map(fn($div) => trim($div))
                            ->filter();

            foreach ($divisiones as $division) {
                $categoria = Categoria::where('division', $division)->first();

                if ($categoria) {
                    // Evitar duplicados
                    $exists = DB::table('categoria_fusion')
                        ->where('categoria_id', $categoria->id)
                        ->where('fusion_id', $fusion->id)
                        ->exists();

                    if (!$exists) {
                        DB::table('categoria_fusion')->insert([
                            'categoria_id' => $categoria->id,
                            'fusion_id' => $fusion->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        $this->command->info("Insertada categoria_id {$categoria->id} para fusion_id {$fusion->id}");
                    }
                } else {
                    $this->command->warn("No se encontró categoría con división: {$division} para la fusión ID {$fusion->id}");
                }
            }
        }
    }
}
