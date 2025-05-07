<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Categoria;
use App\Models\CategoriaTorneo;
use App\Models\Combate;
use App\Models\Maestro;
use App\Models\RankingTorneo;
use App\Models\RegistroTorneo;
use App\Models\ResultadosTorneo;
use App\Models\Torneo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class AreasCategoriasDivisiones extends Areas
{
    public $torneoId;
    public $fechaId;
    public $areaId;
    public $areaSeleccionada;
    public $categoriaSeleccionada;
    public $categoriaId;
    public $torneo;
    public $participantes = [];
    public $partidos = [];
    public $mostrarRondas = false;
    public $rondasCompletadas = [];
    public $ronda = [];
    public $teams = [];
    public $results = [];
    public $combatePositions = [];
    public $showModal = false; 
    public $nombre;
    public $search = '';
    public $perPage = 10;
    use WithPagination;

    public $areasFecha;
    public $combateSeleccionado  = null;
    public $mostrarBotonCombates = false;
    public $combatesPendientes = [];
    public $mostrarFinalizadas = true;
    public $mostrarVacias = true;
    public $criterioDivision = 'manual'; 
    public $numeroCategorias = 2;
    public $divisiones = [];
    public $showModalSplit = false;
    public $seleccionados = [];
    public $divisionesClaves = [];
    public $asignaciones = []; 
    public $divisionesNombres = [];
    public $divisionesIds = [];

    #[Layout('components.layouts.combates')]

    protected $listeners = [
        'moverParticipante',
    ];

    public function generarEmparejamientosParaCategoria($categoria_id)
    {
        $this->mostrarRondas = true;

        $existeCombate = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $categoria_id)
            ->where('ronda', 1)
            ->exists();

        if (!$existeCombate) {
            $this->mostrarRondas = true;

            $participantes = RegistroTorneo::with(['alumno.escuelas', 'maestro.escuelas'])
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $categoria_id)
            ->where('check_pago', 1)
            ->where('asistencia', 1)
            ->whereNull('deleted_at')
            ->get();

            // Filtrar competidores únicos
            $competidores = [];
            foreach ($participantes as $participante) {
                if (!in_array($participante->id, array_column($competidores, 'id'))) {
                    $competidores[] = $participante;
                }
            }

            $this->generarTodosLosCombates($competidores, $categoria_id);

        } else {
            // Cargar los combates existentes en lugar de crear nuevos
            $this->partidos[$categoria_id] = Combate::where('torneo_id', $this->torneoId)
                ->where('categoria_id', $categoria_id)
                ->get()
                ->groupBy('ronda');
        }
        $this->js('location.reload()');
    }

    public function toggleFinalizadas()
    {
        $this->mostrarFinalizadas = !$this->mostrarFinalizadas;
        $this->filtrarCategorias();
    }

    public function toggleVacias()
    {
        $this->mostrarVacias = !$this->mostrarVacias;
        $this->filtrarCategorias();
    }

    public function toggleAsistenciaCombate($participanteId)
    {
        $participanteCombate = RegistroTorneo::find($participanteId);
        if ($participanteCombate) {
            $participanteCombate->asistencia = $participanteCombate->asistencia == 1 ? 0 : 1;
            $participanteCombate->save();
        }
    }

    private function generarTodosLosCombates($competidores, $categoria_id)
    {
        $totalCompetidores = count($competidores);
        $totalRondas = $this->calcularNumeroDeRondas($totalCompetidores);

        // Generar los emparejamientos iniciales
        $byes = $this->calcularByes($totalCompetidores);
        $emparejamientos = $this->generarEmparejamientos($competidores, $byes);

        // Crear los combates de la primera ronda
        $orden = 1;
        foreach ($emparejamientos[0] as $partido) {
            $combate = Combate::create([
                'participante1_id' => $partido['participante1']->id ?? null,
                'participante2_id' => $partido['participante2']->id ?? null,
                'torneo_id' => $this->torneoId,
                'categoria_id' => $categoria_id,
                'ronda' => 1,
                'estado' => 'pendiente',
                'orden' => $orden,
            ]);
            $orden++;

            // Si el participante 2 es nulo, el participante 1 gana automáticamente
            if (is_null($partido['participante2'])) {
                $combate->ganador_id = $partido['participante1']->id;
                $combate->estado = 'terminada';
                $combate->save();
            }
        }

        // Generar combates para las rondas siguientes
        for ($rondaActual = 2; $rondaActual <= $totalRondas; $rondaActual++) {
            $numeroCombates = pow(2, $totalRondas - $rondaActual);
        
            for ($i = 0; $i < $numeroCombates; $i++) {
                // Buscar los ganadores de la ronda anterior
                $ganador1 = Combate::where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $categoria_id)
                    ->where('ronda', $rondaActual - 1)
                    ->whereNull('descripcion')
                    ->skip($i * 2) // Tomar los ganadores de cada dos combates
                    ->first()->ganador_id ?? null;
        
                $ganador2 = Combate::where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $categoria_id)
                    ->where('ronda', $rondaActual - 1)
                    ->whereNull('descripcion')
                    ->skip($i * 2 + 1) // Tomar los ganadores de cada dos combates
                    ->first()->ganador_id ?? null;
        
                // Crear el combate con los ganadores de la ronda anterior
                Combate::create([
                    'participante1_id' => $ganador1,
                    'participante2_id' => $ganador2,
                    'torneo_id' => $this->torneoId,
                    'categoria_id' => $categoria_id,
                    'ronda' => $rondaActual,
                    'estado' => 'pendiente',
                    'orden' => $orden,
                ]);
        
                $orden++;
            }
        }
        $totalCompetidores = RegistroTorneo::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('asistencia', 1)
            ->count();

            if($totalCompetidores >= 3){
                // Crear el combate por el tercer lugar
                Combate::create([
                    'participante1_id' => null,
                    'participante2_id' => null,
                    'torneo_id' => $this->torneoId,
                    'categoria_id' => $categoria_id,
                    'ronda' => $totalRondas, // Puede estar en la misma ronda que la final
                    'estado' => 'pendiente',
                    'orden' => $orden,
                    'descripcion' => 'tercer lugar',
                ]);

                $orden++; // Incrementar el orden
            }

        $combateFinal = Combate::where('torneo_id', $this->torneoId)
                ->where('categoria_id', $categoria_id)
                ->where('ronda', $totalRondas)
                ->whereNull('descripcion')
                ->orderBy('id')
                ->first();

        if ($combateFinal) {
            $combateFinal->orden = $orden;
            $combateFinal->descripcion = 'final';
            $combateFinal->save();
            $orden++; // Incrementar el orden después de actualizar la final
        } else {
            // Crear el combate de la final
                Combate::create([
                    'participante1_id' => null,
                    'participante2_id' => null,
                    'torneo_id' => $this->torneoId,
                    'categoria_id' => $categoria_id,
                    'ronda' => $totalRondas,
                    'estado' => 'pendiente',
                    'orden' => $orden,
                    'descripcion' => 'final',
                ]);
                $orden++;
            }
    }

    public function render()
    {
        $infoTorneo = $this->obtenerInfoTorneo($this->torneoId);
        $this->areaSeleccionada = $infoTorneo[$this->fechaId]['areas'][$this->areaId];
        unset($this->areasFecha[$this->areaId]);
        $this->categoriasArea = $this->areaSeleccionada['categorias'];
        $this->filtrarCategorias();
        $this->seleccionArea = TRUE;

        $categoriaId = $this->categoriaId;
        foreach ($this->categoriasArea as $categoria) {
            if ($categoria['categoria_id'] == $categoriaId) {
                $this->categoriaSeleccionada = $categoria;
                break;
            }
        }

        $this->partidos = Combate::with(['participante1', 'participante2'])
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->orderBy('ronda')
            ->get()
            ->groupBy(function ($partidos) {
                return $partidos->ronda;
            })->toArray();

        /* dd($this->partidos); */
        $totalCompetidores = RegistroTorneo::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->count();

        $totalRondas = $this->calcularNumeroDeRondas($totalCompetidores);
        if (!empty($this->partidos)) {
            $this->mostrarRondas = true;
        }

        // Cargar participantes
        $this->participantes = $this->categoriaSeleccionada['inscritos'];
        $combateIds = [];
        $this->teams = [];
        $this->results = [];
        $matches = [];
        $combatePositions = [];

        // Bucle por rondas y partidos
        foreach ($this->partidos as $ronda => $partidosRonda) {
            $resultadosRonda = [];

            foreach ($partidosRonda as $index => $partido) {
                // Obtener participantes
                $participante1 = $partido['participante1'] ? $partido['participante1']['nombre'] . ' ' . $partido['participante1']['apellidos'] : null;
                $participante2 = $partido['participante2'] ? $partido['participante2']['nombre'] . ' ' . $partido['participante2']['apellidos'] : null;

                // Obtener nacionalidad y bandera
                if ($participante1 !== null) {
                    $alumno1 = Alumno::find($partido['participante1']['alumno_id']);
                    if ($alumno1) {
                        $nacionalidad1 = $alumno1->codigo_bandera ?? null;
                    } else {
                        // Si es maestro
                        $maestro1 = Maestro::find($partido['participante1']['maestro_id']);
                        $nacionalidad1 = $maestro1 ? $maestro1->codigo_bandera : null;
                    }
                } else {
                    $nacionalidad1 = null;
                }

                // Obtener nacionalidad y bandera del participante 2
                if ($participante2 !== null) {
                    $alumno2 = Alumno::find($partido['participante2']['alumno_id']);
                    if ($alumno2) {
                        $nacionalidad2 = $alumno2->codigo_bandera ?? null;
                    } else {
                        // Si es maestro
                        $maestro2 = Maestro::find($partido['participante2']['maestro_id']);
                        $nacionalidad2 = $maestro2 ? $maestro2->codigo_bandera : null;
                    }
                } else {
                    $nacionalidad2 = null;
                }

                $puntuacion1 = $partido['puntos_participante1'] ?? null;
                $puntuacion2 = $partido['puntos_participante2'] ?? null;

                // Manejar BYEs
                if ($participante2 === null) {
                    $puntuacion1 = 0;
                    $puntuacion2 = 0;
                }

                if ($participante1 === null) {
                    $puntuacion1 = 0;
                    $puntuacion2 = 0;
                }

                if ($ronda == 1) {
                    $this->teams[] = [
                        $participante1 ? [
                            'name' => ucwords(strtolower($participante1)),
                            'flag' => $nacionalidad1,
                            'combateId' => $partido['id'],
                            'participantId' => $partido['participante1']['id'] ?? null,] : null,
                        $participante2 ? [
                            'name' => ucwords(strtolower($participante2)),
                            'flag' => $nacionalidad2,
                            'combateId' => $partido['id'],
                            'participantId' => $partido['participante2']['id'] ?? null,
                        ] : null
                    ];
                }

                $resultadosRonda[] = [$puntuacion1, $puntuacion2];

                $roundIndex = $ronda - 1; // Índice de ronda basado en cero
                $matchIndex = $index;
                $combatePositions[$roundIndex][$matchIndex] = $partido['id'];
            }

            $this->results[] = $resultadosRonda;
        }

        // Inicializar rondas completadas
        $this->rondasCompletadas = array_fill(0, count($this->partidos), false);

        $hayGanador = DB::table('categoria_torneo')
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->whereNotNull('ganador_id')
            ->exists();

        $resultados = ResultadosTorneo::with(['participante'])
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->orderBy('posicion')
            ->take(10)
            ->get();


        if (!empty($this->teams) && !empty($this->results)) {
            $this->dispatch('bracketUpdated');
        }

        $this->getCompetidoresProperty();

        return view('livewire.areas-categorias-divisiones', [
            'totalRondas' => $totalRondas,
            'hayGanador' => $hayGanador,
            'resultados' => $resultados,
            'teamsJson' => json_encode($this->teams),
            'resultsJson' => json_encode($this->results),
            'combateIds' => $combateIds,
            'matchesJson' => json_encode($matches),
            'combatePositionsJson' => json_encode($combatePositions),
            'competidores' => $this->competidores,
        ]);
    }

    public function abrirModalSplit() {
        $this->showModalSplit = true;
        $this->dividirCategoria();
    }

    public function cerrarModalSplit()
    {
        $this->showModalSplit = false; 
    }

    public function updatedNumeroCategorias()
    {
        $this->dividirCategoria();
    }

    public function dividirCategoria()
    {
        $this->participantes;

        if ($this->criterioDivision == 'automatico') {
            $this->divisiones = $this->dividirAutomaticamente($this->participantes);
        } else {
            $this->divisiones[0] = $this->categoriaSeleccionada['inscritos'];
            for ($i = 0; $i < $this->numeroCategorias; $i++) {
                if ($i === 0) {
                    $this->divisionesClaves[$i] = $this->categoriaSeleccionada['division_categoria'];
                    $this->divisionesNombres[$i] = $this->categoriaSeleccionada['nombre_categoria'];
                    $this->divisionesIds[$i] = $this->categoriaId;
                } else {
                    $this->divisionesClaves[$i] = $this->categoriaSeleccionada['division_categoria'] . ' - ' . $i;
                    $this->divisionesNombres[$i] = $this->categoriaSeleccionada['nombre_categoria'] . ' - ' . $i;
                }
            }
            $this->divisionesIds = [];

            $categoriaExistente = Categoria::where('division', $this->categoriaSeleccionada['division_categoria'])
            ->where('nombre', $this->categoriaSeleccionada['nombre_categoria'])
            ->where('categoria_padre_id', $this->categoriaId)
            ->first();

            if ($categoriaExistente) {
                $this->divisionesIds[$i] = $categoriaExistente->id;
            } else {
                $this->divisionesIds[$i] = null;
            }
            $this->asignaciones = [];
    
            foreach ($this->participantes as $participante) {
                $this->asignaciones[$participante['id']] = 0;
            }
        }
    }

    public function guardarDivisiones()
    {
        for ($i = 1; $i < $this->numeroCategorias; $i++) {
            $participantesAsignados = array_keys(array_filter($this->asignaciones, function($divisionIndex) use ($i) {
                return $divisionIndex == $i;
            }));
    
            if (empty($participantesAsignados)) {
                continue;
            }
    
            $categoriaOriginal = Categoria::find($this->categoriaId);
    
            $categoriaExistente = Categoria::where('division', $this->divisionesClaves[$i])
            ->where('nombre', $this->divisionesNombres[$i])
            ->where('categoria_padre_id', $categoriaOriginal->id)
            ->first();

            if ($categoriaExistente) {
                $nuevaCategoria = $categoriaExistente;
            } else {
                $nuevaCategoria = $categoriaOriginal->replicate();
                $nuevaCategoria->division = $this->divisionesClaves[$i];
                $nuevaCategoria->nombre = $this->divisionesNombres[$i];
                $nuevaCategoria->categoria_padre_id = $categoriaOriginal->id; 
                $nuevaCategoria->save();
            }

            $torneo = $categoriaOriginal->torneos()->where('torneo_id', $this->torneoId)->first();
    
            if ($torneo) {
                CategoriaTorneo::firstOrCreate([
                    'categoria_id' => $nuevaCategoria->id,
                    'torneo_id' => $this->torneoId,
                ], [
                    'area' => $torneo->pivot->area,
                    'horario' => $torneo->pivot->horario,
                    'order_position' => $torneo->pivot->order_position,
                ]);
            }
    
            RegistroTorneo::where('torneo_id', $this->torneoId)
                ->where('categoria_id', $this->categoriaId)
                ->whereIn('id', $participantesAsignados)
                ->update(['categoria_id' => $nuevaCategoria->id]);
        }
    
        $this->cerrarModalSplit();
        $this->filtrarCategorias();
        $this->reiniciarEmparejamientosCategoria($this->categoriaId);
    }

    public function dividirAutomaticamente($competidores)
    {
        $divisiones = [];

        foreach ($competidores as $index => $competidor) {
            $divisiones[$index % $this->numeroCategorias][] = $competidor;
        }

        return $divisiones;
    }

    public function moverParticipante($fromParticipanteId, $toParticipanteId, $originCombateId, $destinationCombateId)
    {
        // Buscar los combates
        $fromCombate = Combate::find($originCombateId);
        $toCombate = Combate::find($destinationCombateId);

        if (!$fromCombate || !$toCombate) {
            return;
        }

        // 1. Actualizar los combates donde esté el participante original (fromParticipanteId)
        $this->actualizarCombatesRelacionados($fromParticipanteId, $toParticipanteId, $fromCombate->categoria_id, $fromCombate->torneo_id);

        // 2. Intercambiar los participantes en los combates de origen y destino
        if ($fromParticipanteId && $toParticipanteId) {
            // Si ambos participantes existen, intercambiarlos
            if ($fromCombate->participante1_id == $fromParticipanteId) {
                $fromCombate->participante1_id = $toParticipanteId;
            } elseif ($fromCombate->participante2_id == $fromParticipanteId) {
                $fromCombate->participante2_id = $toParticipanteId;
            }

            if ($toCombate->participante1_id == $toParticipanteId) {
                $toCombate->participante1_id = $fromParticipanteId;
            } elseif ($toCombate->participante2_id == $toParticipanteId) {
                $toCombate->participante2_id = $fromParticipanteId;
            }
        } elseif ($fromParticipanteId && !$toParticipanteId) {
            // Si solo hay un participante en el combate de origen, moverlo al destino
            if (is_null($toCombate->participante1_id)) {
                $toCombate->participante1_id = $fromParticipanteId;
            } elseif (is_null($toCombate->participante2_id)) {
                $toCombate->participante2_id = $fromParticipanteId;
            }

            // Limpiar el participante del combate de origen
            if ($fromCombate->participante1_id == $fromParticipanteId) {
                $fromCombate->participante1_id = null;
            } elseif ($fromCombate->participante2_id == $fromParticipanteId) {
                $fromCombate->participante2_id = null;
            }
        }

        // 3. Guardar los cambios en los combates de origen y destino
        $fromCombate->save();
        $toCombate->save();

        // 4. Actualizar el bracket y refrescar los datos
        $this->dispatch('bracketUpdated');
    }

    private function actualizarCombatesRelacionados($fromParticipanteId, $toParticipanteId, $categoriaId, $torneoId)
    {
        // Buscar todos los combates de la misma categoría y torneo donde participe el `fromParticipanteId` o el `toParticipanteId`
        $combatesRelacionados = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where(function ($query) use ($fromParticipanteId, $toParticipanteId) {
                $query->where('participante1_id', $fromParticipanteId)
                    ->orWhere('participante2_id', $fromParticipanteId)
                    ->orWhere('participante1_id', $toParticipanteId)
                    ->orWhere('participante2_id', $toParticipanteId)
                    ->orWhere('ganador_id', $fromParticipanteId)
                    ->orWhere('ganador_id', $toParticipanteId); // También verificar si alguno es ganador
            })
            ->get();

        foreach ($combatesRelacionados as $combate) {
            // Intercambiar el ganador si aplica
            if ($combate->ganador_id == $fromParticipanteId) {
                $combate->ganador_id = $toParticipanteId;
            } elseif ($combate->ganador_id == $toParticipanteId) {
                $combate->ganador_id = $fromParticipanteId;
            }

            // Intercambiar los participantes en el combate
            if ($combate->participante1_id == $fromParticipanteId) {
                $combate->participante1_id = $toParticipanteId;
            } elseif ($combate->participante1_id == $toParticipanteId) {
                $combate->participante1_id = $fromParticipanteId;
            }

            if ($combate->participante2_id == $fromParticipanteId) {
                $combate->participante2_id = $toParticipanteId;
            } elseif ($combate->participante2_id == $toParticipanteId) {
                $combate->participante2_id = $fromParticipanteId;
            }

            // Guardar los cambios
            $combate->save();
        }
    }

    public function generarDatosBracket() {
        $this->teams = [];
        $this->results = [];
        $this->combatePositions = [];
    
        $this->partidos = Combate::with(['participante1', 'participante2'])
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->orderBy('ronda')
            ->get()
            ->groupBy(function ($partidos) {
                return $partidos->ronda;
            })->toArray();
    
        foreach ($this->partidos as $ronda => $partidosRonda) {
            $resultadosRonda = [];
            foreach ($partidosRonda as $index => $partido) {
                $participante1 = $partido['participante1'] ? $partido['participante1']['nombre'] . ' ' . $partido['participante1']['apellidos'] : null;
                $participante2 = $partido['participante2'] ? $partido['participante2']['nombre'] . ' ' . $partido['participante2']['apellidos'] : null;
    
                // Verificación de si hay más de dos participantes (limitar a dos)
                if ($participante1 || $participante2) {
                    $this->teams[] = [
                        $participante1 ? [
                            'name' => ucwords(strtolower($participante1)),
                            'flag' => $partido['participante1']->codigo_bandera ?? null,
                            'combateId' => $partido['id'],
                            'participantId' => $partido['participante1']['id'] ?? null
                        ] : null,
                        $participante2 ? [
                            'name' => ucwords(strtolower($participante2)),
                            'flag' => $partido['participante2']->codigo_bandera ?? null,
                            'combateId' => $partido['id'],
                            'participantId' => $partido['participante2']['id'] ?? null
                        ] : null
                    ];
                }
    
                $resultadosRonda[] = [$partido['puntos_participante1'], $partido['puntos_participante2']];
                $this->combatePositions[$ronda][$index] = $partido['id'];
            }
    
            $this->results[] = $resultadosRonda;
        }
    
        $this->dispatch('bracketUpdated', [
            'teams' => $this->teams,
            'results' => $this->results,
            'combatePositions' => $this->combatePositions
        ]);
    }

    public function combate($combateId)
    {

        $combate = Combate::findOrFail($combateId);
        $id = $combate->id;

        return redirect()->route('pantalla-combate-admin', ['id' => $id]);
    }

    public function cargarCombatesPendientes()
    {
        $rondaActual = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('estado', 'pendiente')
            ->orderBy('ronda', 'asc')
            ->value('ronda');

        $todosCompletados = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('ronda', $rondaActual)
            ->where('estado', '<>', 'terminada')
            ->doesntExist();

        if ($todosCompletados) {
            $rondaActual++;
        }

        $this->combatesPendientes = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('estado', 'pendiente')
            ->where('ronda', $rondaActual) 
            ->whereNotNull('participante2_id')
            ->whereNull('ganador_id')
            ->orderBy('orden', 'asc')
            ->get();
    }

    public function mount($torneoId, $fechaId, $areaId = null)
    {
        parent::mount($torneoId, $fechaId, $areaId);
        
        $this->cargarCombatesPendientes();
        $this->combateSeleccionado  = null;
        $this->filtrarCategorias();
    }

    public function filtrarCategorias()
    {
        $this->categoriasArea = collect($this->categoriasArea)
            ->filter(function ($categoria) {
                $estaVacia = isset($categoria['inscritos']) ? count($categoria['inscritos']) == 0 : true;

                $hayGanador = DB::table('resultados_torneo')
                    ->where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $categoria['categoria_id'])
                    ->where('posicion', 1)
                    ->exists();

                    logger()->info("Filtrando categoría: ", [
                        'estaVacia' => $estaVacia,
                        'hayGanador' => $hayGanador,
                        'mostrarFinalizadas' => $this->mostrarFinalizadas,
                        'mostrarVacias' => $this->mostrarVacias
                    ]);

                if (!$this->mostrarFinalizadas && $hayGanador) {
                    return false;
                }
                if (!$this->mostrarVacias && $estaVacia) {
                    return false;
                }
                return true;
            })
            ->sort(function ($a, $b) {
                $result = strcmp($a['horario_categoria'], $b['horario_categoria']);
                if ($result === 0) {
                    return strnatcmp($a['division_categoria'], $b['division_categoria']);
                }
                return $result;
            });
    }

    public function habilitarBotonCombate($value)
    {
        $this->combateSeleccionado = $value;

        $this->mostrarBotonCombates = !is_null($value) && $value !== '';
    }

    public function iniciarCombateSeleccionado()
    {
        $combate = Combate::find($this->combateSeleccionado);

        if ($combate) {
            // Redireccionar a la ruta del combate
            return redirect()->route('pantalla-combate-admin', ['id' => $combate->id]);
        } else {
            flash()->options([
                'position' => 'top-center',
            ])->addError('', 'Selecciona un combate válido.');
        }
    }

    public function iniciarProximoCombate()
    {
        // Buscar el próximo combate pendiente donde participante2 no sea null
        $proximoCombate = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('estado', 'pendiente')
            ->whereNotNull('participante2_id')
            ->whereNull('ganador_id')
            ->orderBy('orden', 'asc')
            ->first();

            while ($proximoCombate && ($proximoCombate->participante1_id === null || $proximoCombate->participante2_id === null)) {
                $proximoCombate = Combate::where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $this->categoriaId)
                    ->where('estado', 'pendiente')
                    ->whereNull('ganador_id')
                    ->where('orden', '>', $proximoCombate->orden)
                    ->orderBy('orden', 'asc')
                    ->first();
            }

        if ($proximoCombate) {
            // Redireccionar a la ruta del combate
            return redirect()->route('pantalla-combate-admin', ['id' => $proximoCombate->id]);
        } else {
            // Si no hay combates pendientes, mostrar un mensaje
            flash()->options([
                'position' => 'top-center',
            ])->addError('', 'No hay más combates pendientes en este momento.');
        }
    }

    public function combatePublico($combateId)
    {

        $combate = Combate::findOrFail($combateId);
        $id = $combate->id;

        return redirect()->route('pantalla-combate', ['id' => $id]);
    }

    public function reiniciarEmparejamientosCategoria($categoria_id)
    {

        $combate_categoria = Combate::where('categoria_id', $categoria_id)
            ->where('torneo_id', $this->torneoId)
            ->get();
        foreach ($combate_categoria as $combate) {
            $combate->delete();
        }

        $resultados = ResultadosTorneo::where('categoria_id', $categoria_id)
            ->where('torneo_id', $this->torneoId)
            ->get();
        foreach ($resultados as $resultado) {
            $resultado->delete();
        }

        $rankings = RankingTorneo::where('categoria_id', $categoria_id)
            ->where('torneo_id', $this->torneoId)
            ->get();
        foreach ($rankings as $ranking) {
            $ranking->delete();
        }

        CategoriaTorneo::where('categoria_id', $categoria_id)->update(['ganador_id' => NULL]);

        $this->mostrarRondas = false;
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Combates reiniciados correctamente.');

        return redirect()->back();
    }

    public function abrirModalCompetidores() {
        $this->showModal = true;
    }

    public function cerrarModalCompetidores()
    {
        $this->showModal = false; 
    }

    public function buscar()
    {
        $this->resetPage();
    }

    public function getCompetidoresProperty()
    {
        // Obtener los IDs de los competidores ya inscritos en la categoría actual y torneo actual
        $competidoresInscritos = RegistroTorneo::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->pluck('alumno_id');

        // Construir la consulta de competidores excluyendo los ya inscritos
        return Alumno::with(['escuelas', 'maestros'])
                ->where(function ($query) use ($competidoresInscritos) {
                    $query->whereNotIn('alumnos.id', $competidoresInscritos)
                        ->orWhereHas('maestros', function ($subquery) use ($competidoresInscritos) {
                            $subquery->whereIn('maestros.id', $competidoresInscritos); 
                        });
                })
            ->where(function ($query) {
                $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
                    ->orWhere('email', 'LIKE', "%{$this->search}%")
                    ->orWhere('telefono', 'LIKE', "%{$this->search}%")
                    ->orWhere('cinta', 'LIKE', "%{$this->search}%")
                    ->orWhereHas('escuelas', function ($query) {
                        $query->where('nombre', 'LIKE', "%{$this->search}%");
                    })
                    ->orWhereHas('maestros', function ($query) {
                        $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"]);
                    });
            })
            ->paginate($this->perPage);
    }

    public function inscribir($competidorId)
    {
        $participanteNuevo = Alumno::where('id', $competidorId)->first();
        RegistroTorneo::create([
            'torneo_id' => $this->torneoId,
            'categoria_id' => $this->categoriaId,
            'alumno_id' => $competidorId,
            'maestro_id' => NULL,
            'cinta' => $participanteNuevo->cinta,
            'peso' => $participanteNuevo->peso,
            'estatura' => 1,
            'genero' => $participanteNuevo->genero,
            'nombre' => $participanteNuevo->nombre,
            'apellidos' => $participanteNuevo->apellidos,
            'email' => $participanteNuevo->email,
            'fec' => $participanteNuevo->fec,
            'telefono' => $participanteNuevo->telefono,
            'puntaje' => NULL,
            'check_pago' => 1,
        ]);

        // Cierra el modal y recarga los participantes
        $this->cerrarModalCompetidores();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('','Competidor inscrito con éxito.');

        $this->reiniciarEmparejamientosCategoria($this->categoriaId);
    }

}
