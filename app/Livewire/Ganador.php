<?php

namespace App\Livewire;

use App\Models\CategoriaTorneo;
use App\Models\Combate;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Ganador extends Component
{
    public $combateId, $torneoId, $categoriaId, $areaId, $fecha, $fechasTorneo;
    public $ganador;
    public $areas = [];

    #[Title('Ganador del Combate')]
    #[Layout('components.layouts.combates')]

    public function mount($id)
    {
        $combate = Combate::with(['participante1', 'participante2'])->find($id);
        $this->combateId = $combate->id;
        if ($combate->ganador_id) {
            $this->ganador = $combate->ganador_id == $combate->participante1_id ? $combate->participante1 : $combate->participante2;
        } else {
            $this->ganador = null;
        }
    }

    public function render()
    {
        return view('livewire.ganador', ['ganador' => $this->ganador]);
    }

    public function regresar()
    {
        $combate = Combate::findOrFail($this->combateId);
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
