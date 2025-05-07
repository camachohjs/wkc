<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\RegistroTorneo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class MigrarRegistrosCombinadosSeeder extends Seeder
{
    public function run(): void
    {
        $torneoId = 77;
        $totalIntentos = 0;
        $totalInsertados = 0;

        $categoriasCombinadas = Categoria::where('division', 'like', '%/%')
            ->whereHas('torneos', fn($q) => $q->where('torneo_id', $torneoId))
            ->get();

        foreach ($categoriasCombinadas as $categoriaCombinada) {
            $divisiones = collect(explode('/', strtoupper($categoriaCombinada->division)))
                ->map(fn($d) => trim(preg_replace('/[^A-Z0-9\-]/', '', $d))); // Limpieza mejorada

            foreach ($divisiones as $division) {
                $registros = RegistroTorneo::with('categoria')
                    ->where('torneo_id', $torneoId)
                    ->whereHas('categoria', function ($q) use ($division) {
                        $q->whereRaw("REPLACE(UPPER(division), ' ', '') = ?", [$division]);
                    })
                    ->get();

                    foreach ($registros as $registro) {
                        $totalIntentos++;
                    
                        // Validar si ya está en esa categoría combinada EXACTAMENTE con mismo alumno y categoría
                        $yaExiste = RegistroTorneo::where('torneo_id', $torneoId)
                            ->where('categoria_id', $categoriaCombinada->id)
                            ->where('alumno_id', $registro->alumno_id)
                            ->exists();
                    
                        if (!$yaExiste) {
                            RegistroTorneo::create([
                                'alumno_id'    => $registro->alumno_id,
                                'maestro_id'   => $registro->maestro_id,
                                'torneo_id'    => $torneoId,
                                'cinta'        => $registro->cinta,
                                'peso'         => $registro->peso,
                                'estatura'     => $registro->estatura,
                                'genero'       => $registro->genero,
                                'nombre'       => $registro->nombre,
                                'apellidos'    => $registro->apellidos,
                                'email'        => $registro->email,
                                'fec'          => $registro->fec,
                                'telefono'     => $registro->telefono,
                                'categoria_id' => $categoriaCombinada->id,
                                'check_pago'   => $registro->check_pago,
                            ]);
                    
                            $totalInsertados++;
                            Log::info("✅ Migrado: {$registro->nombre} {$registro->apellidos} de {$registro->categoria->division} a {$categoriaCombinada->division}");
                        } else {
                            Log::info("⚠️ Ya estaba: {$registro->nombre} {$registro->apellidos} en {$categoriaCombinada->division} (mismo alumno_id)");
                        }
                    }
                    
            }
        }

        Log::info("📊 Migración completada. Intentos: $totalIntentos, Insertados: $totalInsertados para torneo_id = $torneoId");
    }
}
