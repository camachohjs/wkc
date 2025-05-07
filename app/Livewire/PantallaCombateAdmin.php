<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\CategoriaTorneo;
use App\Models\Combate;
use App\Models\RankingTorneo;
use App\Models\RegistroTorneo;
use App\Models\ResultadosTorneo;
use Hamcrest\Core\IsNot;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class PantallaCombateAdmin extends Component
{
    public $combate, $combateId, $torneoId, $ganadorFinal, $perdedorFinal, $siguienteCombate, $categoriaId, $currentPosition, $id;
    public $puntosParticipante1 = 0;
    public $puntosParticipante2 = 0;
    public $controlArbitro = true; // Cambiar según si es la pantalla del árbitro
    public $seconds = 120;
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
    public $isButtonDisabled = false;
    public $puntosCalculadora = '';
    public $participanteActual;
    public $invertirControles = false;
    public $invertirParticipantes = false;
    public $finalRoundCompletado = false;
    public $segundos = 0;

    #[Title('Combate')]
    #[Layout('components.layouts.combates')]

    protected $listeners = [
        'sumarPunto' => 'sumarPunto',
        'startTimer' => 'startTimer',
        'decrementTimer' => 'decrementTimer', 
        'pausarBarraEspacio' => 'pausarBarraEspacio',
        'playsound' => 'playsound',
        'actualizarTiempo' => 'actualizarTiempo',
        'actualizarPuntaje' => 'actualizarPuntaje',
        'combateFinalizado' => 'handleCombateFinalizado'
    ];

    public function toggleTimerSettings() {
        $this->showTimerSettings = true;
    }

    public function openCalculator($participante)
    { 
        $this->participanteActual = $participante;
        $this->puntosCalculadora = '';
        $this->dispatch('openCalculatorModal'); // Abre el modal
    }

    public function addToPoints($number)
    {
        if (strlen($this->puntosCalculadora) < 2) {
            $this->puntosCalculadora .= $number;
        }
        $this->checkScoreDifference();
    }

    public function sumPoints()
    {
        // Si no hay valor en la calculadora, no hacer nada
        if ($this->puntosCalculadora === '') {
            return;
        }

        // Convertir ambos valores a enteros y sumar
        $valorIngresado = intval($this->puntosCalculadora);

        // Actualizar los puntos actuales con la suma
        if ($this->participanteActual === 1) {
            
            $valorExistente = intval($this->puntosParticipante1);
            $this->puntosParticipante1 = $valorExistente + $valorIngresado;;
        } else {
            
            $valorExistente = intval($this->puntosParticipante2);
            $this->puntosParticipante2 = $valorExistente + $valorIngresado;;
        }

        $this->checkScoreDifference();
        // Limpiar la calculadora para la siguiente entrada
        $this->puntosCalculadora = '';
        $this->dispatch('closeCalculatorModal'); // Cierra el modal
    }

    public function updatePoints()
    {
        if ($this->participanteActual === 1) {
            $this->puntosParticipante1 = $this->puntosCalculadora;
        } else {
            $this->puntosParticipante2 = $this->puntosCalculadora;
        }
        
        $this->checkScoreDifference();
        $this->puntosCalculadora = '';
        $this->dispatch('closeCalculatorModal'); // Cierra el modal
    }

    public function deleteLastDigit()
    {
        $this->puntosCalculadora = substr($this->puntosCalculadora, 0, -1);
    }

    public function toggleControles()
    {
        $this->invertirControles = !$this->invertirControles;
    }

    public function toggleParticipantes()
    {
        $this->invertirParticipantes = !$this->invertirParticipantes;
    }

    public function saveTimerSettings()
    {
        if ($this->minutes !== null && $this->minutes != 0) {
            $this->seconds = $this->minutes * 60;
            $this->minutes = 0;
        }
        if ($this->additionalMinutes !== null && $this->additionalMinutes != 0) {
            $this->addTime($this->additionalMinutes);
            $this->additionalMinutes = 0;
        }
        if ($this->additionalSeconds !== null && $this->additionalSeconds != 0) {
            $this->addTimeSeconds($this->additionalSeconds);
            $this->additionalSeconds = 0;
        }
        if ($this->restMinutes !== null && $this->restMinutes != 0) {
            $this->restTime($this->restMinutes);
            $this->restMinutes = 0;
        }
        if ($this->restSeconds !== null && $this->restSeconds != 0) {
            $this->restTimeSeconds($this->restSeconds);
            $this->restSeconds = 0;
        }
        $this->resetTimer();
        $this->dispatch('refreshTimerDisplay', $this->seconds);
        $this->dispatch('closeModal');
    }

    public function addTime($minutes)
    {
        $this->seconds += ($minutes * 60);
    }

    public function addTimeSeconds($seconds){
        $this->seconds += $seconds;
    }

    public function restTime($minutes)
    {
        $this->seconds -= ($minutes * 60);
    }

    public function restTimeSeconds($seconds){
        $this->seconds -= $seconds;
    }

    public function mount($id)
    {
        $this->combate = Combate::find($id);
        $this->id = $id;
        $categoria = Categoria::with('participantes')->find($this->combate->categoria_id);
        $this->categoriaId = $this->combate->categoria_id;
        $this->currentPosition = $this->combate->orden;
        $this->torneoId = $this->combate->torneo_id;
        $this->puntosParticipante1 = $this->combate->puntos_participante1 ?? 0;
        $this->puntosParticipante2 = $this->combate->puntos_participante2 ?? 0;

        $participantesTorneo = \App\Models\RegistroTorneo::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('check_pago', 1)
            ->whereNull('deleted_at')
            ->get();

        if ($participantesTorneo->count() == 3){
            $combateTercerLugar = Combate::where('torneo_id', $this->combate->torneo_id)
            ->where('categoria_id', $this->combate->categoria_id)
            ->where('descripcion', 'tercer lugar')
            ->first();

            $combateTercerLugar->estado = 'terminada';
            $combateTercerLugar->save();
        }
        
        $this->loadsiguienteCombate();
    }

    public function loadsiguienteCombate()
    {
        $combateAnterior = Combate::where('torneo_id', $this->torneoId)
        ->where('categoria_id', $this->categoriaId)
        ->where('estado', '<>', 'terminada')
        ->whereNull('ganador_id')
        ->where('id', '<>', $this->id)
        ->orderBy('orden', 'asc')
        ->first();
        
        if ($combateAnterior) {
            $this->siguienteCombate = $combateAnterior;
            return;
        }

        $siguienteCombate = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $this->categoriaId)
            ->where('estado', 'pendiente')
            ->where('orden', '>', $this->currentPosition)
            ->orderBy('orden', 'asc')
            ->first();

            while ($siguienteCombate && ($siguienteCombate->participante1_id === null && $siguienteCombate->descripcion == 'tercer lugar' && $siguienteCombate->estado == 'terminada' || $siguienteCombate->participante2_id === null && $siguienteCombate->descripcion == 'tercer lugar' && $siguienteCombate->estado == 'terminada')) {
                $siguienteCombate = Combate::where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $this->categoriaId)
                    ->where('estado', 'pendiente')
                    ->whereNull('ganador_id')
                    ->where('orden', '>', $siguienteCombate->orden)
                    ->orderBy('orden', 'asc')
                    ->first();
            }

        if ($siguienteCombate) {
            $this->siguienteCombate = $siguienteCombate;
        }  else {
            $this->siguienteCombate = null;
        }
    }

    public function sumarPunto($participante)
    {
        if ($participante == 1) {
            $this->puntosParticipante1++;
        } else {
            $this->puntosParticipante2++;
        }

        $this->dispatch('puntoActualizado', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->dispatch('actualizarPuntaje', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->checkScoreDifference();
    }

    public function restarPunto($participante)
    {
        if ($participante == 1 && $this->puntosParticipante1 > 0) {
            $this->puntosParticipante1--;
        } elseif ($this->puntosParticipante2 > 0) {
            $this->puntosParticipante2--;
        }

        $this->dispatch('puntoActualizado', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->dispatch('actualizarPuntaje', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->checkScoreDifference();
    }

    public function descalificar($participante)
    {
        if ($participante == 1) {
            $this->puntosParticipante1 = -1;
        } else {
            $this->puntosParticipante2 = -1;
        }

        $this->dispatch('puntoActualizado', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->dispatch('actualizarPuntaje', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->checkScoreDifference();
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
        }
    } 
    
    public function resetTimer()
    {
        $this->timerIsRunning = false;
        $this->dispatch('resetTimer'); 
        $this->startBlinking();
        $this->dispatch('refreshTimerDisplay', $this->seconds);
    }

    public function decrementTimer() 
    {
        if ($this->timerIsRunning && $this->seconds > 0) {
            $this->seconds -= 1;

            if ($this->seconds == 10 && !$this->warningsonido) {
                $this->warningsonido = true;
                $this->dispatch('playSound', 1);
            }

            if ($this->seconds == 0) {
                $this->pauseTimer();
                $this->dispatch('playSound', 2);
                $this->warningsonido = false;

                if ($this->combate->descripcion === 'final' && !$this->finalRoundCompletado) {
                    $this->finalizarRound();
                } else {
                    $this->finalizarCombate();
                }
            }

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

    private function checkScoreDifference()
    {
        $difference = abs($this->puntosParticipante1 - $this->puntosParticipante2);

        if ($difference >= 10) {
            $this->showWarning = true;
            $this->warningMessage = 'Advertencia: La diferencia de puntos es de ' . $difference . ' puntos.';
        } else {
            $this->showWarning = false;
            $this->warningMessage = '';
        }
    }

    public function CombateFinalizado($combateId)
    {
        $combate = Combate::find($combateId);

        $categoria = Categoria::with('participantes')->find($combate->categoria_id);
        
        $participantesTorneo = \App\Models\RegistroTorneo::where('torneo_id', $combate->torneo_id)
            ->where('categoria_id', $combate->categoria_id)
            ->where('check_pago', 1)
            ->whereNull('deleted_at')
            ->get();

        $rondaMaxima = $this->calcularNumeroDeRondas($participantesTorneo->count());
        $rondaSemifinal = $rondaMaxima - 1;

        if ($combate->ronda == $rondaSemifinal){

            if ($combate->participante1_id == $combate->ganador_id){
                $perdedor = $combate->participante2_id;
            } else {
                $perdedor = $combate->participante1_id;
            }

            // Asignar participante al combate de la final
            $combateFinal = Combate::where('torneo_id', $combate->torneo_id)
            ->where('categoria_id', $combate->categoria_id)
            ->where('descripcion', 'final')
            ->first();

            if ($combateFinal) {
                $combateFinal->participante1_id = $combate->ganador_id ?? null;
                $combateFinal->save();
            }

            // Asignar participante al combate por el tercer lugar
            $combateTercerLugar = Combate::where('torneo_id', $combate->torneo_id)
                ->where('categoria_id', $combate->categoria_id)
                ->where('descripcion', 'tercer lugar')
                ->first();

            if ($combateTercerLugar) {
                $combateTercerLugar->participante2_id = $perdedor ?? null;
                $combateTercerLugar->save();
            }
        }

        $todosFinalizados = Combate::where('torneo_id', $combate->torneo_id)
            ->where('categoria_id', $combate->categoria_id)
            ->where('ronda', $combate->ronda)
            ->where('estado', '!=', 'terminada')
            ->count() == 0;

        if ($todosFinalizados) {
            $this->generarRondaSiguiente($combate->torneo_id, $combate->categoria_id, $combate->ronda);
        }
    }

    public function finalizarCombate()
    {
        if ($this->combate->estado === 'terminada') {
            
            flash()->options(['position' => 'top-center'])
            ->addWarning('','Este combate ya fue finalizado.');
            return;
        }
        
        $this->isButtonDisabled = true;

        $ganadorId = null;
        $perdedorId = null;

        if ($this->puntosParticipante1 > $this->puntosParticipante2) {
            $ganadorId = $this->combate->participante1_id;
            $perdedorId = $this->combate->participante2_id;
        } elseif ($this->puntosParticipante2 > $this->puntosParticipante1) {
            $ganadorId = $this->combate->participante2_id;
            $perdedorId = $this->combate->participante1_id;
        }

        if ($ganadorId !== null) {
            $this->ganadorFinal = $ganadorId;
            $this->perdedorFinal = $perdedorId;

            // Actualizar los detalles del combate
            $this->combate->update([
                'puntos_participante1' => $this->puntosParticipante1,
                'puntos_participante2' => $this->puntosParticipante2,
                'ganador_id' => $ganadorId,
                'estado' => 'terminada',
            ]);

            if ($this->combate->descripcion == 'final') {
                // Actualizar el 'ganador_id' en 'categoria_torneo'
                DB::table('categoria_torneo')
                    ->where('torneo_id', $this->combate->torneo_id)
                    ->where('categoria_id', $this->combate->categoria_id)
                    ->update(['ganador_id' => $this->combate->ganador_id]);

                    $this->determinarPosicionesFinales($this->combate->categoria_id,  $this->combate->torneo_id);
            }

            $this->CombateFinalizado($this->combate->id);

            return redirect()->route('ganador', ['id' => $this->combate->id]);
        } else {
            $this->isButtonDisabled = false;
            flash()->options([
                'position' => 'top-center',
            ])->addWarning('','El combate ha terminado en empate.');
        }
    }

    public function generarRondaSiguiente($torneoId, $categoriaId, $rondaActual)
    {
        $combates = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('ronda', $rondaActual)
            ->where('estado', 'terminada')
            ->orderBy('orden')
            ->get();

        $categoria = Categoria::with('participantes')->find($categoriaId);
        $participantesTorneo = \App\Models\RegistroTorneo::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('check_pago', 1)
            ->whereNull('deleted_at')
            ->get();

        $rondaMaxima = $this->calcularNumeroDeRondas($participantesTorneo->count());

        $ganadores = $combates->pluck('ganador_id')->toArray();

        // Asignar participantes a los combates de la siguiente ronda
        $siguienteRonda = $rondaActual + 1;

        $combatesSiguienteRonda = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('ronda', $siguienteRonda)
            ->orderBy('orden')
            ->get();

        $indexCombate = 0;
        for ($i = 0; $i < count($ganadores); $i += 2) {
            $ganador1 = $ganadores[$i] ?? null;
            $ganador2 = $ganadores[$i + 1] ?? null;

            if (isset($combatesSiguienteRonda[$indexCombate])) {
                $combate = $combatesSiguienteRonda[$indexCombate];
                $combate->participante1_id = $ganador1;
                $combate->participante2_id = $ganador2;
                $combate->save();

                // Si el participante 2 es nulo, el participante 1 avanza automáticamente
                if (is_null($ganador2)) {
                    $combate->ganador_id = $ganador1;
                    $combate->estado = 'terminada';
                    $combate->save();
                }

                $indexCombate++;
            }
        }

        // Manejar el combate por el tercer lugar
        if ($siguienteRonda == $rondaMaxima) {
            $this->asignarParticipantesFinalYTercerLugar($torneoId, $categoriaId, $rondaActual);
        }
    }

    private function asignarParticipantesFinalYTercerLugar($torneoId, $categoriaId, $rondaActual)
    {
        // Obtener combates de la ronda actual que han terminado
        $combatesTerminados = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('ronda', $rondaActual)
            ->where('estado', 'terminada')
            ->orderBy('orden')
            ->get();

        // Obtener ganadores y perdedores
        $ganadores = $combatesTerminados->pluck('ganador_id')->toArray();
        $perdedores = $combatesTerminados->map(function ($combate) {
            return $combate->participante1_id === $combate->ganador_id ? $combate->participante2_id : $combate->participante1_id;
        })->toArray();

        // Asignar participantes al combate de la final
        $combateFinal = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('descripcion', 'final')
            ->first();

        if ($combateFinal) {
            $combateFinal->participante1_id = $ganadores[0] ?? null;
            $combateFinal->participante2_id = $ganadores[1] ?? null;
            $combateFinal->save();
        }

        // Asignar participantes al combate por el tercer lugar
        $combateTercerLugar = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('descripcion', 'tercer lugar')
            ->first();

        if ($combateTercerLugar) {
            $combateTercerLugar->participante1_id = $perdedores[0] ?? null;
            $combateTercerLugar->participante2_id = $perdedores[1] ?? null;
            $combateTercerLugar->save();
        }
    }

    public function determinarPosicionesFinales($categoriaId, $torneoId)
    {
        // Obtener todos los combates finalizados
        $combatesFinalizados = Combate::where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('estado', 'terminada')
            ->get();

        // Identificar el combate de la final y el tercer lugar
        $final = $combatesFinalizados->filter(function($combate) {
            return $combate->descripcion == 'final';
        })->first();

        $tercerLugar = $combatesFinalizados->filter(function($combate) {
            return $combate->descripcion == 'tercer lugar';
        })->first();

        // Obtener los ganadores y perdedores
        $ganadorFinal = $final ? $final->ganador_id : null;
        $perdedorFinal = $final ? ($final->participante1_id == $final->ganador_id ? $final->participante2_id : $final->participante1_id) : null;

        $ganadorTercerLugar = $tercerLugar ? $tercerLugar->ganador_id : null;
        $perdedorTercerLugar = $tercerLugar ? ($tercerLugar->participante1_id == $tercerLugar->ganador_id ? $tercerLugar->participante2_id : $tercerLugar->participante1_id) : null;

        // Obtener los demás participantes y calcular las posiciones
        $posiciones = [];
        foreach ($combatesFinalizados as $combate) {
            $participantes = [$combate->participante1_id, $combate->participante2_id];
            foreach ($participantes as $participanteId) {
                if ($participanteId) {
                    if (!isset($posiciones[$participanteId])) {
                        $posiciones[$participanteId] = [
                            'victorias' => 0,
                            'puntos' => 0
                        ];
                    }
                    if ($combate->ganador_id == $participanteId) {
                        $posiciones[$participanteId]['victorias'] += 1;
                    }
                    if ($participanteId == $combate->participante1_id) {
                        $posiciones[$participanteId]['puntos'] += $combate->puntos_participante1;
                    } else {
                        $posiciones[$participanteId]['puntos'] += $combate->puntos_participante2;
                    }
                }
            }
        }

        // Ordenar por número de victorias y puntos obtenidos (descendente)
        uasort($posiciones, function ($a, $b) {
            if ($a['victorias'] === $b['victorias']) {
                return $b['puntos'] - $a['puntos'];
            }
            return $b['victorias'] - $a['victorias'];
        });

        // Guardar los resultados en la tabla de resultados_torneo
        $posicion = 1;
        if ($ganadorFinal) {
            DB::table('resultados_torneo')->insert([
                'torneo_id' => $torneoId,
                'categoria_id' => $categoriaId,
                'participante_id' => $ganadorFinal,
                'posicion' => $posicion
            ]);
            $posicion++;
        }

        if ($perdedorFinal) {
            DB::table('resultados_torneo')->insert([
                'torneo_id' => $torneoId,
                'categoria_id' => $categoriaId,
                'participante_id' => $perdedorFinal,
                'posicion' => $posicion
            ]);
            $posicion++;
        }

        if ($ganadorTercerLugar) {
            DB::table('resultados_torneo')->insert([
                'torneo_id' => $torneoId,
                'categoria_id' => $categoriaId,
                'participante_id' => $ganadorTercerLugar,
                'posicion' => $posicion
            ]);
            $posicion++;
        }

        if ($perdedorTercerLugar) {
            DB::table('resultados_torneo')->insert([
                'torneo_id' => $torneoId,
                'categoria_id' => $categoriaId,
                'participante_id' => $perdedorTercerLugar,
                'posicion' => $posicion
            ]);
            $posicion++;
        }

        // Guardar los demás resultados
        foreach ($posiciones as $participanteId => $datos) {
            if (!in_array($participanteId, [$ganadorFinal, $perdedorFinal, $ganadorTercerLugar, $perdedorTercerLugar])) {
                DB::table('resultados_torneo')->insert([
                    'torneo_id' => $torneoId,
                    'categoria_id' => $categoriaId,
                    'participante_id' => $participanteId,
                    'posicion' => $posicion
                ]);
                $posicion++;
            }
        }

        // Obtener los resultados para actualizar el ranking
        $resultados = ResultadosTorneo::where('categoria_id', $categoriaId)
            ->where('torneo_id', $torneoId)
            ->orderBy('posicion')->get();

        // Definir los puntos por posición
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

        // Guardar o actualizar el ranking
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

        // Obtener el ganador del torneo
        $ganador = $resultados->where('posicion', 1)->first();
    }

    public function finalizarRound()
    {
        if ($this->combate->estado === 'terminada') {
            flash()->options(['position' => 'top-center'])
            ->addWarning('','Este combate ya fue finalizado.');
            return;
        }
        $this->finalRoundCompletado = true;
        $this->seconds = 120;
        $this->resetTimer();
        $this->dispatch('refreshTimerDisplay', $this->seconds);
        $this->dispatch('actualizarPuntaje', $this->puntosParticipante1, $this->puntosParticipante2);
        $this->dispatch('actualizarTiempo', $this->seconds);

        flash()->options(['position' => 'top-center'])
        ->addSuccess('', 'Round 1 finalizado. Timer reiniciado para el round final.');
    }

    private function determinarAno()
    {
        $fechaActual = now();
        $anoInicio = $fechaActual->month >= 8 ? $fechaActual->year : $fechaActual->year - 1;
        $anoFin = $anoInicio + 1;
        return "{$anoInicio}-{$anoFin}";
    }

    public function generarEmparejamientos($competidores, $byes, $totalRondas)
    {
        /* dd($competidores); */
        shuffle($competidores);
        $partidos = [];
        $total = count($competidores);

        // Distribuir los "Byes"
        for ($i = 0; $i < $byes; $i++) {
            array_splice($competidores, rand(0, count($competidores)), 0, null);
        }

        // Crear todas las rondas de emparejamientos
        for ($ronda = 0; $ronda < $totalRondas; $ronda++) {
            $partidos[$ronda] = [];

            for ($i = 0; $i < $total; $i += 2) {
                if ($i + 1 < $total) {
                    $participante1 = $competidores[$i];
                    $participante2 = $competidores[$i + 1];
                    /* dd($participante1, $participante2); */

                    $partidos[$ronda][] = ['participante1' => $participante1, 'participante2' => $participante2, 'id' => $i];
                } else {
                    
                }
            }
        }

        return $partidos;
    }

    public function calcularByes($numeroDeCompetidores)
    {
        $byes = [
            3 => 1, 5 => 3, 6 => 2, 7 => 1, 8 => 0, 9 => 7, 10 => 6, 11 => 5,
            12 => 4, 13 => 3, 14 => 2, 15 => 1, 16 => 0, 17 => 15,
            18 => 14, 19 => 13, 20 => 12, 21 => 11, 22 => 10, 23 => 9,
            24 => 8, 25 => 7, 26 => 6, 27 => 5, 28 => 4, 29 => 3,
            30 => 2, 31 => 1, 32 => 0
        ];

        return $byes[$numeroDeCompetidores] ?? 0;
    }

    public function calcularNumeroDeRondas($numeroDeCompetidores)
    {
        return ceil(log($numeroDeCompetidores, 2));
    }

    public function render()
    {
        return view('livewire.pantalla-combate-admin');
    }

    public function regresar()
    {
        $combate = Combate::findOrFail($this->combate->id);
        /* dd($combate); */
        $torneoId = $combate->torneo_id;
        $categoriaId = $combate->categoria_id;

        $fechasTorneo = CategoriaTorneo::where('torneo_id', $torneoId)
        ->pluck('horario')
        ->map(function ($fecha) {
            return substr($fecha, 0, 10);
        })
        ->unique()
        ->toArray();

        sort($fechasTorneo);
        $fechasTorneoReIndex = array_values($fechasTorneo);

        $fecha = CategoriaTorneo::where('torneo_id', $torneoId)
                                ->where('categoria_id', $categoriaId)
                                ->first();
        $horario = substr($fecha->horario, 0, 10);
        $area = $fecha->area;
        
        // Encontrar el índice de la fecha
        $fechaId = array_search(substr($horario, 0, 10), $fechasTorneoReIndex);

        // Obtener las áreas del torneo
        $areasTorneo = CategoriaTorneo::where('torneo_id', $torneoId)
            ->whereDate('horario', substr($horario, 0, 10))
            ->pluck('area')
            ->unique()
            ->toArray();

        sort($areasTorneo);
        // Encontrar el índice del área
        $areaId = array_search($area, $areasTorneo);
        /* dd($torneoId, $fechaId, $areaId, $categoriaId); */

        return redirect()->route('areas-categorias-divisiones', [
            'torneoId' => $torneoId,
            'fechaId' => $fechaId,
            'areaId' => $areaId,
            'categoriaId' => $categoriaId
        ]);
    }
}
