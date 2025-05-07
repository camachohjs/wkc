<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fusion;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class FixFusionCintasSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            // Obtener fusiones con cinta NULL o vacía
            $fusiones = Fusion::whereNull('cinta')->orWhere('cinta', '')->get();

            foreach ($fusiones as $fusion) {
                // Obtener divisiones de la fusión
                $divisiones = explode(' / ', $fusion->division);

                // Buscar categorías asociadas a esas divisiones
                $categorias = Categoria::whereIn('division', $divisiones)->with('cintas')->get();

                // Obtener las cintas únicas de esas categorías
                $cintas = $categorias->pluck('cintas')->flatten()->pluck('cinta')->unique()->implode(', ');

                if (!empty($cintas)) {
                    // Actualizar la fusión con las cintas correctas
                    $fusion->update(['cinta' => $cintas]);
                    echo "✅ Fusión ID {$fusion->id} actualizada con cintas: {$cintas}\n";
                } else {
                    echo "⚠️ No se encontraron cintas para la fusión ID {$fusion->id}\n";
                }
            }
        });
    }
}
