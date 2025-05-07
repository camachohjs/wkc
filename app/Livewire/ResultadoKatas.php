<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\CategoriaTorneo;
use App\Models\Combate;
use App\Models\Kata;
use App\Models\RegistroTorneo;
use App\Models\ResultadosTorneo;
use Hamcrest\Core\IsNot;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ResultadoKatas extends Component
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
    public $calificacionInicial = 9.80;
    public $calificaciones = [];
    public $total = 29.00;
    public $total_nuevo  = 20;

    #[Title('Katas')]
    #[Layout('components.layouts.combates')]

    public function mount($id)
    {
        $resultado = ResultadosTorneo::find($id);

        if ($resultado) {
            $this->kata = Kata::with(['participante'])
                ->where('torneo_id', $resultado->torneo_id)
                ->where('participante_id', $resultado->participante_id)
                ->where('categoria_id', $resultado->categoria_id)
                ->first();
        }
    }

    public function render()
    {
        return view('livewire.resultado-katas');
    }

    public function regresar()
    {
        $combate = Kata::findOrFail($this->kata->id);
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

        return redirect()->route('areas-categorias-katas', [
            'torneoId' => $torneoId,
            'fechaId' => $fechaId,
            'areaId' => $areaId,
            'categoriaId' => $categoriaId
        ]);
    }
}
