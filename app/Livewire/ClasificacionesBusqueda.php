<?php

namespace App\Livewire;

use App\Models\RankingTorneo;
use App\Models\Categoria;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ClasificacionesBusqueda extends Component
{
    #[Title('Ranking')] 

    public $competidor;
    public $competenciaSelected;
    public $competenciasBusqueda;
    public $torneoSeleccionado;
    public $categoriasLista;
    public $mostrar = false;
    public $participanteSeleccionado, $selectedCycle;
    public $rankings = [];
    public $agrupamiento = 'category';
    public $categoriasAbiertas = [];
    public $personasAbiertas = [];
    public $torneosLista = [];
    public $cicloSeleccionado;

    public function mount()
    {
        $this->cicloSeleccionado = $this->obtenerCicloActual();
        $this->competenciasBusqueda = collect();
        $this->categoriasLista = Categoria::all();
        $this->torneoSeleccionado  = '';
        $this->competidor = '';
        $this->obtenerTorneosDelCiclo();
    }

    public function obtenerTorneosDelCiclo()
    {
        $this->torneosLista = RankingTorneo::select('nombre_torneo')
            ->where('año', $this->cicloSeleccionado)
            ->whereHas('categoria', function ($query) { 
                $query->whereNull('deleted_at');
            })
            ->distinct()
            ->orderBy('nombre_torneo')
            ->get();
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

    public function seleccionarTorneo($nombreTorneo)
    {
        $this->torneoSeleccionado = $this->torneoSeleccionado === $nombreTorneo ? '' : $nombreTorneo;
        $this->obtenerTorneosDelCiclo();
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
    }

    public function eliminarCompetenciaBusqueda($id)
    {
        $this->competenciasBusqueda = $this->competenciasBusqueda->reject(fn($categoria) => $categoria->id === $id);
        $this->mostrar = false;
    }

    public function cambiarAgrupamiento($tipo)
    {
        $this->agrupamiento = $tipo;
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
                        $persona = $personaItems->first()->alumno ?? $personaItems->first()->maestro;
                        if ($personaItems->first()->alumno && is_null($personaItems->first()->alumno->deleted_at)) {
                            $persona = $personaItems->first()->alumno;
                        } else {
                            $persona = $personaItems->first()->maestro;
                        }
                        if ($personaItems->first()->alumno && is_null($personaItems->first()->alumno->deleted_at)) {
                            $persona = $personaItems->first()->alumno;
                        } else {
                            $persona = $personaItems->first()->maestro;
                        }
                        $totalPuntos = $personaItems->sum('puntos');
                        $categoria = $personaItems->first()->categoria;

                        if (!$categoria) {
                            return null; // Evitar errores si la categoría es null
                        }

                        return [
                            'persona_id' => $persona->id ?? null,
                            'nombre' => ($persona->nombre ?? 'null') . ' ' . ($persona->apellidos ?? 'null'),
                            'puntos' => $totalPuntos,
                            'categoria' => $categoria->division,
                            'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                            'division' => $categoria->division,
                            'torneos' => $personaItems->pluck('nombre_torneo')->unique()->implode(', '),
                            'persona' => $persona,
                            'ranking_id' => $personaItems->first()->id,
                        ];
                    })->sortByDesc('puntos')->values();
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
                        // Validar si el alumno existe y no está eliminado
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
                            'categoria' => $categoria->division,
                            'categoria_id' => $categoria->id,
                            'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                            'division' => $categoria->division,
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
                        // Validar si el alumno existe y no está eliminado
                        if (isset($alumno->id) && is_null($alumno->deleted_at)) {
                            $persona = $alumno;
                        } elseif (isset($maestro->id)) {
                            $persona = $maestro;
                        } else {
                            return null;
                        }
                        $totalPuntos = $personaItems->sum('puntos');
                        $categoria = $personaItems->first()->categoria;

                        return [
                            'nombre' => ($persona->nombre ?? 'null') . ' ' . ($persona->apellidos ?? 'null'),
                            'puntos' => $totalPuntos,
                            'categoria' => $categoria->division,
                            'categoria_id' => $categoria->id,
                            'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                            'division' => $categoria->division,
                            'torneos' => $personaItems->pluck('nombre_torneo')->unique()->implode(', '),
                            'persona' => $persona,
                            'ranking_id' => $personaItems->first()->id,
                        ];
                    })->filter()->sortByDesc('puntos')->values();
            });

            $agrupados = $agrupados->map(function ($participantes) {
                return $participantes->values()->map(function ($item, $index) {
                    $item['posicion'] = RankingTorneo::where('categoria_id', $item['categoria_id'])
                        ->where(function($query) use ($item) {
                            if (!is_null($item['persona'])) {
                                $query->where('alumno_id', $item['persona']->id)
                                    ->orWhere('maestro_id', $item['persona']->id);
                            }
                        })
                        ->where('nombre_torneo', $this->torneoSeleccionado)
                        ->value('position');
                    return $item;
                });
            });

        } else {
            $agrupados = $rankings->groupBy(fn($item) => $item->alumno_id ?? $item->maestro_id)
                ->map(function ($personaItems) {
                    if ($personaItems->first()->alumno && is_null($personaItems->first()->alumno->deleted_at)) {
                        $persona = $personaItems->first()->alumno;
                    } else {
                        $persona = $personaItems->first()->maestro;
                    }
                    $totalPuntos = $personaItems->sum('puntos');

                    return [
                        'nombre' => ($persona->nombre ?? 'null') . ' ' . ($persona->apellidos ?? 'null'),
                        'nacionalidad' => $persona->codigo_bandera ?? 'null',
                        'nacionalidad_nombre' => $persona->nacionalidad ?? 'null',
                        'puntos' => $totalPuntos,
                        'foto' => $persona->foto ?? "libs/images/profile/user-1.png",
                        'torneos' => $personaItems->map(function ($item) {
                            return [
                                'categoria' => $item->categoria->division,
                                'puntos' => $item->puntos,
                                'lugar' => $item->position,
                                'nombre_torneo' => $item->nombre_torneo,
                            ];
                        })->toArray(),
                        'persona' => $persona,
                    ];
                })->sortBy('nombre')->values();
        }

        $this->rankings = $agrupados;

        $ciclos = RankingTorneo::select(DB::raw("DISTINCT año"))
                ->orderBy('año', 'desc')
                ->get()
                ->pluck('año');

        return view('livewire.clasificaciones-busqueda', [
            'rankings' => $this->rankings,
            'torneosLista' => RankingTorneo::select('nombre_torneo')->distinct()->orderBy('nombre_torneo')->get(),
            'categoriasLista' => $this->categoriasLista,
            'ciclos' => $ciclos,
            'participanteSeleccionado' => $this->participanteSeleccionado,
        ]);
    }

    public function exportarExcel($ciclo)
    {
        $spreadsheet = new Spreadsheet();
        $todosSheet = $spreadsheet->getActiveSheet();
        $todosSheet->setTitle('Todos los torneos');

        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('Img/KARATE.png')); 
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($todosSheet);

        $todosSheet->getRowDimension(1)->setRowHeight(75);
        $todosSheet->setAutoFilter('A2:E2');
        $coloresPastel = [
            'FBE7C6', 'FFB5E8', 'C7CEEA', 'FFDAC1', 'B5EAD7', 'E2F0CB',
            'FFD1DC', 'F5C7F7', 'D4A5A5', 'F0C987', 'AFCBFF', 'E8D4A2',
        ];
        
        $colorIndex = 0;

        $todosSheet->getStyle('A2:E2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '000000'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'EBC010',
                ],
            ]
        ]);
    
        $todosSheet->getColumnDimension('A')->setWidth(15);
        $todosSheet->getColumnDimension('B')->setWidth(40);
        $todosSheet->getColumnDimension('C')->setWidth(40);
        $todosSheet->getColumnDimension('D')->setWidth(15);
        $todosSheet->getColumnDimension('E')->setWidth(15);

        // Configuración para la hoja principal "Todos los torneos"
        $todosSheet->setCellValue('A2', 'Posición');
        $todosSheet->setCellValue('B2', 'Nombre');
        $todosSheet->setCellValue('C2', 'Nombre de la Escuela');
        $todosSheet->setCellValue('D2', 'Division');
        $todosSheet->setCellValue('E2', 'Puntos');

        // Obtener todos los torneos del ciclo seleccionado
        $rankings = RankingTorneo::with([
            'categoria' => function ($query) { 
                $query->withTrashed(); 
            },
            'alumno' => function ($query) { 
                $query->withTrashed(); 
            },
            'maestro' => function ($query) { 
                $query->withTrashed(); 
            },
        ])
        ->where('año', $ciclo)
        ->get();

        $rankingsAgrupados = $rankings->groupBy('categoria_id')->sortBy(function ($items, $key) {
            return intval(preg_replace('/[^0-9]/', '', $items->first()->categoria->division));
        })->map(function ($items) {
            return $items->groupBy(function($item) {
                return $item->alumno_id ?? $item->maestro_id;
            })->map(function($personaItems) {
                $persona = $personaItems->first()->alumno ?? $personaItems->first()->maestro;
                $categoria = $personaItems->first()->categoria;
                $totalPuntos = $personaItems->sum('puntos');
                return [
                    'nombre' => $persona->nombre . ' ' . $persona->apellidos,
                    'escuela' => $persona->escuelas[0]->nombre ?? '',
                    'division' => $categoria ? $categoria->division : 'Sin división (eliminada)',
                    'puntos' => $totalPuntos,
                ];
            });
        });
        
        $fila = 3;
        foreach ($rankingsAgrupados as $categoriaId => $participantes) {
            $posicion = 1;
            foreach ($participantes->sortByDesc('puntos') as $participante) {
                $color = $coloresPastel[$colorIndex % count($coloresPastel)];
                $todosSheet->getStyle('A' . $fila . ':E' . $fila)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => $color]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                ]);
        
                $todosSheet->setCellValue('A' . $fila, $posicion);
                $todosSheet->setCellValue('B' . $fila, $participante['nombre']);
                $todosSheet->setCellValue('C' . $fila, $participante['escuela']);
                $todosSheet->setCellValue('D' . $fila, $participante['division']);
                $todosSheet->setCellValue('E' . $fila, $participante['puntos']);
                $fila++;
        
                $posicion++;
            }
            $colorIndex++;
        }
        

        // Crear hojas individuales para cada torneo
        $torneos = $rankings->groupBy('nombre_torneo');

        foreach ($torneos as $nombreTorneo => $resultados) {
            $sheet = $spreadsheet->createSheet();
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath(public_path('Img/KARATE.png')); 
            $drawing->setHeight(90);
            $drawing->setCoordinates('B1');
            $drawing->setWorksheet($sheet);

            $sheet->getRowDimension(1)->setRowHeight(75);
            $sheet->setAutoFilter('A2:E2');
            $coloresPastel = [
                'FBE7C6', 'FFB5E8', 'FFDAC1', 'C7CEEA', 'B5EAD7', 'E2F0CB',
                'FFD1DC', 'F5C7F7', 'D4A5A5', 'F0C987', 'AFCBFF', 'E8D4A2',
            ];
            
            $colorIndex = 0;

            $sheet->getStyle('A2:E2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => '000000'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'rgb' => 'EBC010',
                    ],
                ]
            ]);

            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(40);
            $sheet->getColumnDimension('C')->setWidth(40);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);

            $sheet->setTitle($nombreTorneo);
            $sheet->setCellValue('A2', 'Posición');
            $sheet->setCellValue('B2', 'Nombre');
            $sheet->setCellValue('C2', 'Nombre de la Escuela');
            $sheet->setCellValue('D2', 'Division');
            $sheet->setCellValue('E2', 'Puntos');

            $fila = 3;
            $posicion = 1;

            // Agrupar por categoría y ordenar
            $resultadosPorCategoria = $resultados->groupBy('categoria_id')->sortBy(function ($items, $key) {
                return intval(preg_replace('/[^0-9]/', '', $items->first()->categoria->division));
            });

            foreach ($resultadosPorCategoria as $categoriaId => $resultadosCategoria) {
                foreach ($resultadosCategoria->sortByDesc('puntos') as $resultado) {
                    $color = $coloresPastel[$colorIndex % count($coloresPastel)];
                    $sheet->getStyle('A' . $fila . ':E' . $fila)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $color],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    $totalPuntos = $resultado->puntos;
                    $sheet->setCellValue('A' . $fila, $posicion);
                    $sheet->setCellValue('B' . $fila, ($resultado->alumno ? $resultado->alumno->nombre . ' ' . $resultado->alumno->apellidos :
                        ($resultado->maestro ? $resultado->maestro->nombre . ' ' . $resultado->maestro->apellidos : '')));
                    $sheet->setCellValue('C' . $fila, $resultado->alumno->escuelas[0]->nombre ?? '');
                    $sheet->setCellValue('D' . $fila, $resultado->categoria->division ?? '');
                    $sheet->setCellValue('E' . $fila, $totalPuntos);
                    $fila++;
                    $posicion++;
                }
                $colorIndex++;
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        // Descargar el archivo Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response()->streamDownload(function() use ($content) {
            echo $content;
        }, "Rankings-Ciclo-{$ciclo}.xlsx", [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

}