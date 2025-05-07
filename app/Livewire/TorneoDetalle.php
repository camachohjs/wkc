<?php

namespace App\Livewire;

use App\Models\Clasificacion;
use App\Models\Torneo;
use Livewire\Component;
use Livewire\Livewire;

class TorneoDetalle extends Component
{
    public $id;

    public function render()
    {
        $torneo = Torneo::with(['prems', 'ranks'])->find($this->id);

        return view('livewire.torneo-detalle', ['torneo' => $torneo]);
    }

    public function showTournamentDetails(){
        return view('livewire.clasificaciones');
    }
}

