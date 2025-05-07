<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Categoria;
use App\Models\CategoriaTorneo;
use App\Models\Combate;
use App\Models\Kata;
use App\Models\RankingTorneo;
use App\Models\RegistroTorneo;
use App\Models\ResultadosTorneo;
use App\Models\Torneo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

class AreasCategoriasKatas extends Areas
{
    public $torneoId;
    public $fechaId;
    public $areaId;
    public $areaSeleccionada;
    public $categoriaSeleccionada;
    public $categoriaId;
    public $mostrarboton = false;
    public $inscritosKata;
    public $shuffleCounter = 0;
    public $participantesShuffle = [];
    public $showModal = false; 
    public $nombre;
    public $search = '';
    public $perPage = 10;
    public $mostrarFinalizadas = true;
    public $mostrarVacias = true;
    public $criterioDivision = 'manual'; 
    public $numeroCategorias = 2;
    public $divisiones = [];
    public $participantes = [];
    public $showModalSplit = false;
    public $seleccionados = [];
    public $divisionesClaves = [];
    public $asignaciones = []; 
    public $divisionesNombres = [];
    public $divisionesIds = [];
    use WithPagination;

    #[Layout('components.layouts.combates')]

    protected $listeners = [
        'startShuffle' => 'startShuffle',
    ];

    public function mount($torneoId, $fechaId, $areaId = null)
    {
        parent::mount($torneoId, $fechaId, $areaId);
        $this->filtrarCategorias();
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
        $this->loadParticipantes();

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
                $this->asignaciones[$participante->participante['id']] = 0;
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
        $this->reiniciarPosicionamiento($this->categoriaId);
    }

    public function dividirAutomaticamente($participantes)
    {
        $divisiones = [];

        foreach ($participantes as $index => $competidor) {
            $divisiones[$index % $this->numeroCategorias][] = $competidor;
        }

        return $divisiones;
    }

    public function moveParticipante($orderIds)
    {
        foreach ($orderIds as $order) {
            /* dd($order['value'], $this->torneoId, $this->categoriaId, $order['order']); */
            Kata::where('id', $order['value'])
                ->where('torneo_id', $this->torneoId)
                ->where('categoria_id', $this->categoriaId)
                ->update(['order_position' => $order['order']]);
        }

        $this->loadParticipantes();
    }

    public function toggleAsistencia($kataId)
    {
        $kata = Kata::find($kataId);
        if ($kata) {
            $kata->asistencia = $kata->asistencia == 1 ? 0 : 1;
            $kata->save();
            $this->reordenarParticipantes($kata);
        }
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

    public function reordenarParticipantes($kata)
    {
        if ($kata->asistencia == 0) {
            // Mover el participante al final
            $maxOrder = Kata::where('torneo_id', $this->torneoId)
                ->where('categoria_id', $this->categoriaId)
                ->max('order_position');

            $kata->order_position = $maxOrder + 1;
            $kata->save();
        } else {
            // Mover el participante al principio
            $minOrder = Kata::where('torneo_id', $this->torneoId)
                ->where('categoria_id', $this->categoriaId)
                ->min('order_position');

            $kata->order_position = $minOrder - 1;
            $kata->save();

            // Reordenar todos los participantes
            $participantes = Kata::where('torneo_id', $this->torneoId)
                ->where('categoria_id', $this->categoriaId)
                ->orderBy('order_position')
                ->get();

            $index = 1;
            foreach ($participantes as $participante) {
                $participante->order_position = $index++;
                $participante->save();
            }
        }

        $this->loadParticipantes();
    }

    public function loadParticipantes()
    {
        $this->inscritosKata = Kata::with('participante')
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->orderBy('order_position')
            ->get();
            
            if ($this->inscritosKata->isEmpty()) {
                $this->mostrarboton = false;
            } else {
                $this->mostrarboton = true;
            }
            $this->participantes = $this->inscritosKata;
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

        $this->loadParticipantes();

        $hayGanador = DB::table('resultados_torneo')
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('posicion', 1)
            ->exists();

        $todosPasaron = DB::table('katas')
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where(function($query) {
                $query->whereNull('total_nuevo')
                      ->Where('asistencia', '!=', 0);
            })
            ->count();

        $resultados = ResultadosTorneo::with(['participante', 'participante.katas' => function($query) {
                $query->where('torneo_id', $this->torneoId)->where('categoria_id', $this->categoriaId);
            }])
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->orderBy('posicion')
            ->take(10)
            ->get();

            $this->getCompetidoresProperty();
    
        return view('livewire.areas-categorias-katas', [
            'hayGanador' => $hayGanador,
            'resultados' => $resultados,
            'todosPasaron' => $todosPasaron,
            'inscritosKata' => $this->inscritosKata,
            'shuffleCounter' => $this->shuffleCounter,
            'competidores' => $this->competidores,
        ]);
    }

    public function cambiarCategoria($categoriaId)
    {
        $this->categoriaSeleccionada = collect($this->categoriasArea)
            ->firstWhere('categoria_id', $categoriaId);
    }

    public function generarPosicionamiento($categoriaId)
    {
        $this->participantesShuffle = RegistroTorneo::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $categoriaId)
            ->get()
            ->toArray();

        foreach ($this->participantesShuffle as $index => $participante) {
            DB::table('katas')->insert([
                'torneo_id' => $this->torneoId,
                'categoria_id' => $this->categoriaId,
                'participante_id' => $participante['id'],
                'created_at' => now(),
                'updated_at' => now(),
                'estado' => 'pendiente',
                'order_position' => $index + 1,
            ]);
        }

        $this->shuffleCounter = 0;
        $this->startShuffle();
    }

    public function startShuffle()
    {
        if ($this->shuffleCounter < 3) {
            $this->shuffleCounter++;
            foreach ($this->participantesShuffle as $index => $participante) {
                shuffle($this->participantesShuffle);
                DB::table('katas')
                    ->where('participante_id', $participante['id'])
                    ->where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $this->categoriaId)
                    ->update(['order_position' => $index + 1]);
            }
            $this->dispatch('shuffleCompleted');
        } else {
            $this->finalizeShuffle();
        }
    }

    private function finalizeShuffle()
    {
        foreach ($this->participantesShuffle as $index => $participante) {
            DB::table('katas')
                ->where('participante_id', $participante['id'])
                ->where('torneo_id', $this->torneoId)
                ->where('categoria_id', $this->categoriaId)
                ->update(['order_position' => $index + 1]);
        }

        $this->loadParticipantes();
        $this->dispatch('hideShuffleMessage');
    }

    public function reiniciarPosicionamiento($categoriaId)
    {

        $combate_kata = Kata::where('categoria_id', $categoriaId)
        ->where('torneo_id', $this->torneoId)
        ->get();
        foreach ($combate_kata as $combate) {
            $combate->delete();
        }

        $resultados = ResultadosTorneo::where('categoria_id', $categoriaId)
        ->where('torneo_id', $this->torneoId)
        ->get();
        foreach ($resultados as $resultado) {
            $resultado->delete();
        }

        $rankings = RankingTorneo::where('categoria_id', $categoriaId)
        ->where('torneo_id', $this->torneoId)
        ->get();
        foreach ($rankings as $ranking) {
            $ranking->delete();
        }

        $this->loadParticipantes();

        $this->mostrarboton = false;
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Posiciones reiniciadas correctamente.');
        
        return redirect()->back();
    }

    public function play($combateId){

        $combate = Kata::findOrFail($combateId);
        $id = $combate->id;

        return redirect()->route('pantalla-katas', ['id' => $id]);
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
            ->whereNotNull('alumno_id')
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
        $this->loadParticipantes();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('','Competidor inscrito con éxito.');
        $this->reiniciarPosicionamiento($this->categoriaId);
    }

}
