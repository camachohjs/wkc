<?php

namespace App\Livewire;

use App\Models\Torneo;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Eventos extends Component
{
    use WithPagination;
    public $torneoId;
    public $fechaInicio, $fechaFin, $buscar;

    public function showTournamentDetails($torneoId)
    {
        $this->torneoId = $torneoId;
        $torneo = Torneo::find($torneoId);
        return redirect()->route('torneo-detalle', ['id' => $torneo->id]);
    }

    public function render()
    {
        $query = Torneo::query();

        if ($this->buscar) {
            $query->where(function ($subquery) {
                $subquery->where('nombre', 'like', '%' . $this->buscar . '%')
                            ->orWhere('descripcion', 'like', '%' . $this->buscar . '%');
            });
        }

        if ($this->fechaInicio) {
            $fechaInicio = Carbon::createFromFormat('Y-m-d', $this->fechaInicio)->startOfDay();
            $query->where('fecha_evento', '>=', $fechaInicio);
        }
    
        if ($this->fechaFin) {
            $fechaFin = Carbon::createFromFormat('Y-m-d', $this->fechaFin)->endOfDay();
            $query->where('fecha_evento', '<=', $fechaFin);
        }

        if (!$this->fechaInicio && !$this->fechaFin) {
            $query->where('fecha_evento', '>=', Carbon::today());
        }

        $torneos = $query->with(['categorias','prems', 'ranks'])
                ->orderBy('fecha_evento', 'asc')
                ->take(4)
                ->get();

        return view('livewire.eventos', [
            'torneos' => $torneos,
        ]);
    }

    public function updateFechas()
    {
        if ($this->fechaInicio && $this->fechaFin && $this->fechaInicio > $this->fechaFin) {
            $this->addError('fechaInicio', 'La fecha de inicio no puede ser posterior a la fecha de fin.');

            $this->fechaInicio = null;
        }

        $this->dispatch('refreshComponent');
    }
}