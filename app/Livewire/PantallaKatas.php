<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\CategoriaTorneo;
use App\Models\Combate;
use App\Models\Kata;
use App\Models\RankingTorneo;
use App\Models\RegistroTorneo;
use App\Models\ResultadosTorneo;
use Hamcrest\Core\IsNot;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class PantallaKatas extends Component
{
    public $kata, $combateId, $torneoId, $ganadorFinal, $perdedorFinal;
    public $puntosParticipante1 = 0;
    public $puntosParticipante2 = 0;
    public $controlArbitro = true; // Cambiar según si es la pantalla del árbitro
    public $seconds = 0;
    public $minutes = 0;
    public $timerIsRunning = false;
    public $showTimerSettings = false;
    public $additionalMinutes = 0;
    public $additionalSeconds = 0;
    public $restMinutes = 0;
    public $restSeconds = 0;
    public $showWarning = false;
    public $warningMessage = '';
    public $warningsonido = false;
    public $calificacionInicial = 9.90;
    public $calificaciones = [];
    public $total = 29.00;
    public $total_nuevo  = 20;
    public $siguienteParticipante;
    public $categoriaId;
    public $currentPosition;
    public $mostrarCalificacion = false;
    public $redireccionUrl, $selectedKataId;

    #[Title('Kata')]
    #[Layout('components.layouts.combates')]

    protected $listeners = [
        'sumarPunto' => 'sumarPunto',
        'startTimer' => 'startTimer',
        'incrementTimer' => 'incrementTimer', 
        'pausarBarraEspacio' => 'pausarBarraEspacio',
        'playsound' => 'playsound',
        'actualizarTiempo' => 'actualizarTiempo',
        'actualizarPuntaje' => 'actualizarPuntaje',
        'combateFinalizado' => 'handleCombateFinalizado',
        'redirigir' => 'redirigir'
    ];

    public function toggleTimerSettings() {
        $this->showTimerSettings = true;
    }

    public function redirectToKata()
    {
        if ($this->selectedKataId) {
            return redirect()->to('/pantalla-katas/' . $this->selectedKataId);
        }
    }

    public function mount($id)
    {
        $this->selectedKataId = $id;
        $this->kata = Kata::with(['categoria', 'categoria.forma'])->find($id);
        $this->categoriaId = $this->kata->categoria_id;
        $this->currentPosition = $this->kata->order_position;
        $this->torneoId = $this->kata->torneo_id;
        $this->loadsiguienteParticipante();

        $calificacionPorDefecto = [
            'Juez 1' => $this->kata->calificacion_1 ?? 9.90,
            'Juez 2' => $this->kata->calificacion_2 ?? 9.90,
            'Juez 3' => $this->kata->calificacion_3 ?? 9.90,
        ];

        if ($this->kata->categoria->forma->seccion_id == 1) { // 1 = cintas negras
            $this->calificaciones = [
                'Juez 1' => number_format($this->kata->calificacion_1 ?? 9.90, 2),
                'Juez 2' => number_format($this->kata->calificacion_2 ?? 9.90, 2),
                'Juez 3' => number_format($this->kata->calificacion_3 ?? 9.90, 2),
            ];
        } elseif ($this->kata->categoria->forma->seccion_id == 2) { // grados menores
            $this->calificaciones = [
                'Juez 1' => number_format($this->kata->calificacion_1 ?? 9.85, 2),
                'Juez 2' => number_format($this->kata->calificacion_2 ?? 9.85, 2),
                'Juez 3' => number_format($this->kata->calificacion_3 ?? 9.85, 2),
            ];
        } else {
            $this->calificaciones = $calificacionPorDefecto;
        }
        $this->total = $this->kata->calificacion_1 + $this->kata->calificacion_2 + $this->kata->calificacion_3;
        $this->total = number_format($this->total, 2);
    }

    public function actualizarCalificacion($juez, $valor)
    {
        $this->calificaciones[$juez] = number_format($valor, 2);
        $this->guardarCalificacion($juez, $valor);
        $this->total = $this->kata->calificacion_1 + $this->kata->calificacion_2 + $this->kata->calificacion_3;
        $this->total = number_format($this->total, 2);
    }

    public function loadsiguienteParticipante()
    {
        $siguienteParticipante = Kata::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('estado', 'pendiente')
            ->where('order_position', '>', $this->currentPosition)
            ->where('asistencia', 1)
            ->orderBy('order_position', 'asc')
            ->first();

        if ($siguienteParticipante) {
            $this->siguienteParticipante = $siguienteParticipante;
        }
    }

    public function guardarCalificacion($juez, $valor)
    {
        $columna = null;
        switch ($juez) {
            case 'Juez 1':
                $columna = 'calificacion_1';
                break;
            case 'Juez 2':
                $columna = 'calificacion_2';
                break;
            case 'Juez 3':
                $columna = 'calificacion_3';
                break;
        }

        if ($columna) {
            $this->kata->update([$columna => number_format($valor ?? '9.90', 2)]);
        }

        if (is_null($this->kata->calificacion_1)) {
            $this->kata->update(['calificacion_1' => '9.90']);
        }
    
        if (is_null($this->kata->calificacion_2)) {
            $this->kata->update(['calificacion_2' => '9.90']);
        }
    
        if (is_null($this->kata->calificacion_3)) {
            $this->kata->update(['calificacion_3' => '9.90']);
        }
        
        $this->total = $this->kata->calificacion_1 + $this->kata->calificacion_2 + $this->kata->calificacion_3;
        $this->total = number_format($this->total, 2);
        $this->kata->update(['total' => $this->total]);
    }

    public function startTimer()
    {
        if (!$this->timerIsRunning) {
            $this->timerIsRunning = true;
            $this->dispatch('startTimer');
            $this->resetInterval();
            $this->resetBlinking();
        }
    }

    private function resetInterval()
    {
        $this->dispatch('pauseTimer');
    }

    public function pauseTimer()
    {
        if ($this->timerIsRunning) {
            $this->timerIsRunning = false;
            $this->dispatch('pauseTimer'); 
            $this->startBlinking();
            $this->kata->update(['tiempo' => $this->seconds]);
        }
    } 
    
    public function resetTimer()
    {
        $this->timerIsRunning = false;
        $this->dispatch('resetTimer'); 
        $this->seconds = 0;
        $this->startBlinking();
        $this->dispatch('refreshTimerDisplay', $this->seconds);
    }

    public function incrementTimer() 
    {
        if ($this->timerIsRunning) {
            // Sonido de advertencia cuando quedan 10 segundos para algún evento
            if ($this->seconds == 0) {
                $this->warningsonido = true;
                $this->dispatch('playSound', 1);
            }
            $this->seconds += 1; // Incrementa los segundos
    
            // Actualiza el tiempo visualmente
            $this->dispatch('actualizarTiempo', $this->seconds);
        }
    }

    public function pausarBarraEspacio()
    {
        if ($this->timerIsRunning) {
            $this->pauseTimer();
        } else {
            $this->startTimer();
        }
    }

    private function resetBlinking() {
        $this->dispatch('resetBlink');
    }
    
    private function startBlinking() {
        $this->dispatch('startBlink');
    }

    public function finalizarCombate()
    {
        $this->ajustarCalificaciones();
        $this->guardarResultados($this->kata->categoria_id);
        $this->kata->refresh();

        $this->total_nuevo = $this->kata->calificacion_nueva_1 + $this->kata->calificacion_nueva_2 + $this->kata->calificacion_nueva_3;
        $this->total_nuevo = number_format($this->total_nuevo, 2);
        $this->kata->update([
            'total_nuevo' => $this->total_nuevo,
            'estado' => 'terminado',
        ]);

        $this->mostrarCalificacion = true;

        $this->loadsiguienteParticipante();

        if ($this->siguienteParticipante) {
            $this->redireccionUrl = route('pantalla-katas', ['id' => $this->siguienteParticipante->id]);
        } else {
            $resultados = ResultadosTorneo::where('categoria_id', $this->kata->categoria_id)
                                            ->where('torneo_id', $this->torneoId)
                                            ->orderBy('posicion')->get();

            $puntosPorPosicion = [
                1 => 16,
                2 => 14,
                3 => 12,
                4 => 10,
                5 => 8,
                6 => 6,
                7 => 4,
                8 => 2
            ];

            foreach ($resultados as $resultado) {
                $puntos = $puntosPorPosicion[$resultado->posicion] ?? 0;

                if ($puntos > 0) {
                    $categoriaId = $resultado->categoria->categoria_padre_id ?? $resultado->categoria_id;
                    RankingTorneo::updateOrCreate(
                        [
                            'alumno_id' => $resultado->participante->alumno_id,
                            'categoria_id' => $categoriaId,
                            'torneo_id' => $resultado->torneo_id,
                        ],
                        [
                            'nombre_torneo' => $resultado->torneo->nombre,
                            'puntos' => $puntos,
                            'maestro_id' => $resultado->participante->maestro_id,
                            'año' => $this->determinarAno(),
                            'position' => $resultado->posicion,
                        ]
                    );
                }
            }

            $ganador = $resultados->where('posicion', 1)->first();
            

            if ($ganador) {
                $this->redireccionUrl = route('resultado-katas', ['id' => $ganador->id]);
            } else {
                $this->regresar();
            }
        }

        $this->dispatch('mostrarCalificacion', $this->redireccionUrl);
    }

    private function determinarAno()
    {
        $fechaActual = now();
        $anoInicio = $fechaActual->month >= 8 ? $fechaActual->year : $fechaActual->year - 1;
        $anoFin = $anoInicio + 1;
        return "{$anoInicio}-{$anoFin}";
    }

    public function redirigir($url)
    {
        return redirect()->to($url);
    }

    public function handleMostrarCalificacion($data)
    {
        $this->mostrarCalificacion = true;
        $this->dispatch('redirigir', $data['url']);
    }

    private function ajustarCalificaciones()
    {
        // Guardar el orden original en la sesión
        session(['calificaciones_originales' => $this->calificaciones]);

        // Convertir y redondear las calificaciones originales
        $cal1 = round($this->calificaciones['Juez 1'], 2);
        $cal2 = round($this->calificaciones['Juez 2'], 2);
        $cal3 = round($this->calificaciones['Juez 3'], 2);

        // Calificaciones en un array para comparar diferencias
        $calificaciones = [$cal1, $cal2, $cal3];
        $min = min($calificaciones);
        $max = max($calificaciones);
        $medio = array_sum($calificaciones) - $min - $max; // Calcular la calificación intermedia
        
        // Condición: Si la diferencia entre las tres calificaciones es de 1 o 2 décimas, no hacer ajustes.
        if (($max - $min <= 0.02)) {
            // No hacer ajustes, guardar las calificaciones originales
            Kata::where('id', $this->kata->id)->update([
                'calificacion_nueva_1' => $cal1,
                'calificacion_nueva_2' => $cal2,
                'calificacion_nueva_3' => $cal3,
            ]);
            return;
        }

        // Ajustar el máximo y el medio si la diferencia no cumple con el criterio de 0.02
        if (($max - $medio) > 0.02) {
            $max = $medio + 0.02;
        }

        if (($medio - $min) > 0.02) {
            $min = $medio - 0.02;
        }

        // Reasignar las calificaciones ajustadas en el orden original
        $ajustadas = ['Juez 1' => $min, 'Juez 2' => $medio, 'Juez 3' => $max];
        $originales = session('calificaciones_originales');
        $calificacionesReasignadas = [];

        foreach ($originales as $juezOriginal => $calOriginal) {
            foreach ($ajustadas as $juezAjustado => $calAjustada) {
                if (abs($calOriginal - $calAjustada) < 0.01) { // Usamos diferencia pequeña en lugar de igualdad exacta
                    $calificacionesReasignadas[$juezOriginal] = $calAjustada;
                    unset($ajustadas[$juezAjustado]); // Quitamos el valor ya asignado
                    break;
                }
            }
        }

        foreach ($ajustadas as $juezAjustado => $calAjustada) {
            foreach ($originales as $juezOriginal => $calOriginal) {
                if (!isset($calificacionesReasignadas[$juezOriginal])) {
                    $calificacionesReasignadas[$juezOriginal] = $calAjustada;
                    break;
                }
            }
        }

        $this->calificaciones = array_map(function($cal) {
            return number_format($cal, 2);
        }, $calificacionesReasignadas);

        // Guardar las calificaciones ajustadas en la base de datos
        Kata::where('id', $this->kata->id)->update([
            'calificacion_nueva_1' => $this->calificaciones['Juez 1'],
            'calificacion_nueva_2' => $this->calificaciones['Juez 2'],
            'calificacion_nueva_3' => $this->calificaciones['Juez 3']
        ]);

        // Limpiar la sesión
        session()->forget('calificaciones_originales');
    }

    private function guardarResultados($categoriaId)
    {
        $participantes = Kata::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('asistencia', 1)
            ->get();

        $posiciones = $participantes->sortByDesc(function ($participante) {
            return $participante->calificacion_nueva_1 + $participante->calificacion_nueva_2 + $participante->calificacion_nueva_3;
        });

        $posicion = 1;
        foreach ($posiciones as $participante) {
            DB::table('resultados_torneo')->updateOrInsert(
                [
                    'torneo_id' => $this->torneoId,
                    'categoria_id' => $categoriaId,
                    'participante_id' => $participante->participante_id,
                ],
                [
                    'posicion' => $posicion,
                ]
            );
            $posicion++;
        }
    }

    public function render()
    {
        $resultados = Kata::with(['participante'])
            ->where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('asistencia', 1)
            ->orderBy('order_position')
            ->take(10)
            ->get();

        foreach ($resultados as $resultado) {
            $kata = Kata::where('torneo_id', $this->torneoId)
                        ->where('categoria_id', $this->categoriaId)
                        ->where('participante_id', $resultado->participante_id)
                        ->first();

            if ($kata) {
                $resultado->kata = $kata;
            }
        }

        return view('livewire.pantalla-katas', [
            'resultados' => $resultados,
        ]);
    }

    public function regresar()
    {
        $combate = Kata::findOrFail($this->kata->id);
        /* dd($combate); */
        $this->torneoId = $combate->torneo_id;
        $categoriaId = $combate->categoria_id;

        $fechasTorneo = CategoriaTorneo::where('torneo_id', $this->torneoId)
        ->pluck('horario')
        ->map(function ($fecha) {
            return substr($fecha, 0, 10);
        })
        ->unique()
        ->toArray();

        sort($fechasTorneo);
        $fechasTorneoReIndex = array_values($fechasTorneo);

        $fecha = CategoriaTorneo::where('torneo_id', $this->torneoId)
                                ->where('categoria_id', $categoriaId)
                                ->first();
        $horario = substr($fecha->horario, 0, 10);
        $area = $fecha->area;
        
        // Encontrar el índice de la fecha
        $fechaId = array_search(substr($horario, 0, 10), $fechasTorneoReIndex);

        // Obtener las áreas del torneo
        $areasTorneo = CategoriaTorneo::where('torneo_id', $this->torneoId)
            ->whereDate('horario', substr($horario, 0, 10))
            ->pluck('area')
            ->unique()
            ->toArray();

        sort($areasTorneo);
        // Encontrar el índice del área
        $areaId = array_search($area, $areasTorneo);
        /* dd($torneoId, $fechaId, $areaId, $categoriaId); */

        return redirect()->route('areas-categorias-katas', [
            'torneoId' => $this->torneoId,
            'fechaId' => $fechaId,
            'areaId' => $areaId,
            'categoriaId' => $categoriaId
        ]);
    }
}
