<?php

namespace App\Livewire;

use App\Models\Torneo;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class InscribirAdmin extends Component
{
    
    #[Title('Inscribir')]
    #[Layout('components.layouts.layout')]
    public $torneoId;
    public $fechaInicio, $fechaFin, $buscar;

    use WithPagination;

    public function showTournamentDetails($torneoId)
    {
        $this->torneoId = $torneoId;
        $torneo = Torneo::find($torneoId);
        return redirect()->route('torneo-detalle', ['id' => $torneo->id]);
    }

    public function render()
    {
        $query = Torneo::query()
            ->where('torneo_configurado', 1);

        if ($this->buscar) {
            $query->where(function ($subquery) {
                $subquery->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->buscar . '%');
            });
        }

        if ($this->fechaInicio || $this->fechaFin) {
            if ($this->fechaInicio) {
                $query->where('fecha_evento', '>=', Carbon::parse($this->fechaInicio)->startOfDay());
            }

            if ($this->fechaFin) {
                $query->where('fecha_evento', '<=', Carbon::parse($this->fechaFin)->endOfDay());
            }
        } else {
            $query->where('fecha_evento', '>=', Carbon::today())
            ->where('fecha_registro', '>', Carbon::now());
        }

        $torneos = $query->with(['prems', 'ranks'])
            ->orderBy('fecha_evento', 'asc')
            ->take(4)
            ->get();

        return view('livewire.inscribir-admin', [
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
