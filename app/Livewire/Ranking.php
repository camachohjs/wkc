<?php

namespace App\Livewire;

use App\Models\RankingTorneo;
use App\Models\Categoria;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class Ranking extends Component
{
    #[Title('Ranking')]
    #[Layout('components.layouts.layout')]

    public $competidor;
    public $competenciaSelected;
    public $competenciasBusqueda;
    public $torneoSeleccionado = '';
    public $categoriasLista;
    public $mostrar = false;
    public $participanteSeleccionado;
    public $rankings = [];
    public $agrupamiento = 'category';
    public $categoriasAbiertas = [];
    public $personasAbiertas = [];
    public $cicloSeleccionado;
    public $ciclos = [];
    public $torneosLista = [];

    public function mount()
    {
        $this->competenciasBusqueda = collect();
        $this->categoriasLista = Categoria::all();
        $this->torneoSeleccionado  = '';
        $this->competidor = '';
        $this->cicloSeleccionado = $this->obtenerCicloActual();
        $this->ciclos = RankingTorneo::select('año')->distinct()->pluck('año');
        $this->obtenerTorneosDelCiclo();
    }

    public function seleccionarTorneo($nombreTorneo)
    {
        $this->torneoSeleccionado = $this->torneoSeleccionado === $nombreTorneo ? '' : $nombreTorneo;
        $this->obtenerTorneosDelCiclo();
        $this->calcularPosiciones();
    }

    public function obtenerCicloActual()
    {
        $añoActual = now()->year;
        $mesActual = now()->month;

        if ($mesActual >= 8) {
            return "$añoActual-" . ($añoActual + 1);
        } else {
            return ($añoActual - 1) . "-$añoActual";
        }
    }

    public function agregarCompetenciaBusqueda()
    {
        if ($this->competenciaSelected) {
            $categoria = Categoria::find($this->competenciaSelected);
            if (!$this->competenciasBusqueda->contains('id', $this->competenciaSelected)) {
                $this->competenciasBusqueda->push($categoria);
                $this->competenciaSelected = '';
                $this->mostrar = true;
            }
        }
    }

    public function mostrarDetalles($personaId)
    {
        if ($this->participanteSeleccionado && $this->participanteSeleccionado->id == $personaId) {
            $this->participanteSeleccionado = null;
        } else {
            foreach ($this->rankings as $categoria) {
                foreach ($categoria as $participante) {
                    if ($participante['persona']->id == $personaId) {
                        $this->participanteSeleccionado = $participante['persona'];
                        break 2;
                    }
                }
            }
        }
        $this->obtenerTorneosDelCiclo();
        $this->calcularPosiciones();
    }

    public function eliminarCompetenciaBusqueda($id)
    {
        $this->competenciasBusqueda = $this->competenciasBusqueda->reject(fn($categoria) => $categoria->id === $id);
        $this->mostrar = false;
    }

    public function cambiarAgrupamiento($tipo)
    {
        $this->agrupamiento = $tipo;
        $this->obtenerTorneosDelCiclo();
        $this->calcularPosiciones();
    }

    public function toggleCategoria($categoriaId)
    {
        if (($key = array_search($categoriaId, $this->categoriasAbiertas)) !== false) {
            unset($this->categoriasAbiertas[$key]);
        } else {
            $this->categoriasAbiertas[] = $categoriaId;
        }
        $this->obtenerTorneosDelCiclo();
    }

    public function togglePersona($personaId)
    {
        if (($key = array_search($personaId, $this->personasAbiertas)) !== false) {
            unset($this->personasAbiertas[$key]);
        } else {
            $this->personasAbiertas[] = $personaId;
        }
    }

    public function calcularPosiciones($global = false)
    {
        $query = RankingTorneo::with(['categoria' => function ($query) {
                $query->withTrashed();
            }, 'alumno', 'maestro'])
            ->where('año', $this->cicloSeleccionado)
            ->whereHas('categoria', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->orderBy('categoria_id')
            ->orderByDesc('puntos');

        $rankings = $query->get();

        if ($global) {
            $agrupados = $rankings->groupBy('categoria_id')->map(function ($categoriaItems) {
                return $categoriaItems->groupBy(fn($item) => $item->alumno_id ?? $item->maestro_id)
                    ->map(function ($personaItems) {
                        $alumno = $personaItems->first()->alumno;
                        $maestro = $personaItems->first()->maestro;
                        $categoria = $personaItems->first()->categoria;

                        if (!$categoria) {
                            return null; // Evitar errores si la categoría es null
                        }

                        if ($alumno && is_null($alumno->deleted_at)) {
                            $persona = $alumno;
                        } elseif ($maestro) {
                            $persona = $maestro;
                        } else {
                            return null;
                        }

                        $totalPuntos = $personaItems->sum('puntos');

                        return [
                            'persona_id' => $persona->id ?? null,
                            'nombre' => trim(($persona->nombre ?? 'null') . ' ' . ($persona->apellidos ?? '')),
                            'puntos' => $totalPuntos,
                            'categoria' => $categoria->division ?? 'Sin categoría',
                            'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                            'division' => $categoria->division ?? 'Sin división',
                            'torneos' => $personaItems->pluck('nombre_torneo')->unique()->implode(', '),
                            'persona' => $persona,
                            'ranking_id' => $personaItems->first()->id,
                        ];
                    })->filter()->sortByDesc('puntos')->values(); 
            });

            foreach ($agrupados as $categoriaId => $participantes) {
                foreach ($participantes as $index => $participante) {
                    if (empty($participante['persona']) || empty($participante['persona_id'])) {
                        continue; //  Evitar errores si la persona es null
                    }

                    RankingTorneo::where('categoria_id', $categoriaId)
                        ->whereHas('categoria', function ($query) {
                            $query->whereNull('deleted_at');
                        })
                        ->where(function ($query) use ($participante) {
                            $query->where('alumno_id', $participante['persona']->id)
                                ->orWhere('maestro_id', $participante['persona']->id);
                        })
                        ->update(['position' => $index + 1]);

                        Position::updateOrCreate(
                            [
                                'categoria_id' => $categoriaId,
                                'persona_id' => $participante['ranking_id']
                            ],
                            [
                                'position' => $index + 1,
                                'puntos' => $participante['puntos']
                            ]
                        );
                }
            }
        }
    }

    public function actualizarPuntos($categoriaId, $personaId, $puntos)
    {
        $ranking = RankingTorneo::where('categoria_id', $categoriaId)
        ->where('año', $this->cicloSeleccionado)
                                ->where(function($query) use ($personaId) {
                                    $query->where('alumno_id', $personaId)
                                        ->orWhere('maestro_id', $personaId);
                                })
                                ->first();

        if ($ranking) {
            $ranking->puntos = $puntos;
            $ranking->save();

            $this->calcularPosiciones();
        }
    }

    public function updatedCicloSeleccionado($value)
    {
        $this->obtenerTorneosDelCiclo();
    }

    public function obtenerTorneosDelCiclo()
    {
        $this->torneosLista = RankingTorneo::select('nombre_torneo')
            ->where('año', $this->cicloSeleccionado)
            ->distinct()
            ->whereHas('categoria', function ($query) { 
                $query->whereNull('deleted_at');
            })
            ->orderBy('nombre_torneo')
            ->get();
    }

    public function eliminarParticipanteDelRanking($rankingId)
    {
        $ranking = RankingTorneo::find($rankingId);

        if ($ranking) {
            $ranking->delete();

            Position::where('persona_id', $rankingId)
                ->where('categoria_id', $ranking->categoria_id)
                ->delete();

            $this->participanteSeleccionado = null;
            $this->calcularPosiciones(true);

            flash()->options([
            'position' => 'top-center',
        ])->addSuccess('','Participante eliminado del ranking correctamente.');
        } else {
            flash()->options([
            'position' => 'top-center',
        ])->addError('','No se pudo encontrar el participante.');
        }
        
        $this->obtenerTorneosDelCiclo();
    }

    public function render()
    {
        $this->calcularPosiciones(true); 

        $query = RankingTorneo::with(['categoria', 'alumno', 'maestro'])
        ->where('año', $this->cicloSeleccionado)
        ->whereHas('categoria', function ($query) {
            $query->whereNull('deleted_at'); // Solo categorías activas
        })
        ->whereHas('alumno', function ($query) {
            $query->whereNull('deleted_at'); // Solo alumnos activos
        })
        ->orWhereHas('maestro', function ($query) {
            $query->whereNull('deleted_at'); // Solo maestros activos
        })    
        ->when($this->torneoSeleccionado, fn($query) => 
            $query->where('nombre_torneo', 'like', "%{$this->torneoSeleccionado}%"))
        ->when($this->competenciasBusqueda->isNotEmpty(), fn($query) => 
            $query->whereIn('categoria_id', $this->competenciasBusqueda->pluck('id')))
        ->when($this->competidor, fn($query) => 
            $query->whereHas('alumno', fn($query) => 
                $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->competidor}%"]))
            ->orWhereHas('maestro', fn($query) => 
                $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->competidor}%"])))
        ->whereHas('categoria', function ($query) { 
            $query->whereNull('deleted_at'); // Excluir categorías eliminadas
        });

        $rankings = $query->orderBy('categoria_id')->orderByDesc('puntos')->get();

        if ($this->agrupamiento === 'category' && $this->torneoSeleccionado === '') {
            $agrupados = $rankings->groupBy('categoria_id')->map(function ($categoriaItems) {
                return $categoriaItems->filter(function ($item) {
                    return !is_null($item->categoria); // Evitar registros sin categoría
                })->groupBy(fn($item) => $item->alumno_id ?? $item->maestro_id)
                ->map(function ($personaItems) {
                    $alumno = $personaItems->first()->alumno;
                    $maestro = $personaItems->first()->maestro;
                    $categoria = $personaItems->first()->categoria;
            
                    if (isset($alumno->id) && is_null($alumno->deleted_at)) {
                        $persona = $alumno;
                    } elseif (isset($maestro->id)) {
                        $persona = $maestro;
                    } else {
                        return null;
                    }
            
                    $totalPuntos = $personaItems->sum('puntos');
            
                    return [
                        'nombre' => ($persona->nombre ?? 'null') . ' ' . ($persona->apellidos ?? 'null'),
                        'puntos' => $totalPuntos,
                        'categoria' => $categoria->division ?? 'Sin división',
                        'categoria_id' => $categoria->id ?? null,
                        'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                        'division' => $categoria->division ?? 'Sin división',
                        'torneos' => $personaItems->pluck('nombre_torneo')->unique()->implode(', '),
                        'persona' => $persona,
                        'ranking_id' => $personaItems->first()->id,
                    ];
                })->filter()->sortByDesc('puntos')->values();
            });
            
            $agrupados = $agrupados->map(function ($participantes) {
                return $participantes->values()->map(function ($item, $index) {
                    if (!empty($item['categoria_id']) && !empty($item['ranking_id'])) {
                        $item['posicion'] = Position::where('categoria_id', $item['categoria_id'])
                            ->where('persona_id', $item['ranking_id'])
                            ->value('position');
                    } else {
                        RankingTorneo::where('categoria_id', $item['categoria_id'])
                            ->where(function ($query) use ($item) {
                                $query->where('alumno_id', $item['persona']->id)
                                    ->orWhere('maestro_id', $item['persona']->id);
                            })
                            ->where('nombre_torneo', $this->torneoSeleccionado)
                            ->value('position');
                    } 
                    return $item;
                });
            });

        } elseif ($this->agrupamiento === 'category' && $this->torneoSeleccionado !== '') {
            $agrupados = $rankings->groupBy('categoria_id')->map(function ($categoriaItems) {
                return $categoriaItems->filter(function ($item) {
                    return !is_null($item->categoria); // Evitar registros sin categoría
                })->groupBy(fn($item) => $item->alumno_id ?? $item->maestro_id)
                    ->map(function ($personaItems) {
                        $alumno = $personaItems->first()->alumno;
                        $maestro = $personaItems->first()->maestro;
                        $categoria = $personaItems->first()->categoria;

                        if (!$categoria) {
                            return null;
                        }

                        if ($alumno && is_null($alumno->deleted_at)) {
                            $persona = $alumno;
                        } elseif ($maestro && is_null($maestro->deleted_at)) {
                            $persona = $maestro;
                        } else {
                            return null;
                        }

                        $totalPuntos = $personaItems->sum('puntos');

                        return [
                            'nombre' => trim(($persona->nombre ?? 'null') . ' ' . ($persona->apellidos ?? '')),
                            'puntos' => $totalPuntos,
                            'categoria' => $categoria->division ?? 'Sin categoría',
                            'categoria_id' => $categoria->id ?? null,
                            'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                            'division' => $categoria->division ?? 'Sin división',
                            'torneos' => $personaItems->pluck('nombre_torneo')->unique()->implode(', '),
                            'persona' => $persona,
                            'ranking_id' => $personaItems->first()->id,
                        ];
                    })->filter()->sortByDesc('puntos')->values();
            });

            $agrupados = $agrupados->map(function ($participantes) {
                return $participantes->values()->map(function ($item, $index) {
                    if (!is_null($item['persona']) && !empty($item['categoria_id']) && !empty($item['ranking_id'])) {
                        $item['posicion'] = RankingTorneo::where('categoria_id', $item['categoria_id'])
                            ->where(function ($query) use ($item) {
                                $query->where('alumno_id', $item['persona']->id)
                                    ->orWhere('maestro_id', $item['persona']->id);
                            })
                            ->where('nombre_torneo', $this->torneoSeleccionado)
                            ->value('position');
                    } else {
                        $item['posicion'] = 1;
                    }
                    return $item;
                });
            });

        }

        $this->rankings = $agrupados;

        return view('livewire.ranking', [
            'rankings' => $this->rankings,
            'torneosLista' => $this->torneosLista,
            'categoriasLista' => $this->categoriasLista,
            'participanteSeleccionado' => $this->participanteSeleccionado,
        ]);
    }

    public function añadir()
    {
        return redirect()->route('añadir-ranking');
    }
}