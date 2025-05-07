<?php

namespace App\Livewire;

use App\Models\Combate;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;

class PantallaCombate extends Component
{
    public $combate;
    public $seconds;
    public $puntosParticipante1;
    public $puntosParticipante2;

    #[Title('Combate Público')]
    #[Layout('components.layouts.combates')]

    protected $listeners = [
        'actualizarTiempo' => 'actualizarTiempo',
        'actualizarPuntaje' => 'actualizarPuntaje',
    ];

    public function mount($id)
    {
        $this->combate = Combate::find($id);
        $this->seconds = $this->seconds;
        $this->puntosParticipante1 = $this->combate->puntos_participante1 ?? 0;
        $this->puntosParticipante2 = $this->combate->puntos_participante2 ?? 0;
    }

    #[On('actualizarTiempo')] 
    public function actualizarTiempo($seconds)
    {
        $this->seconds = $seconds;
    }

    #[On('actualizarPuntaje')] 
    public function actualizarPuntaje($puntosParticipante1, $puntosParticipante2)
    {
        $this->puntosParticipante1 = $puntosParticipante1;
        $this->puntosParticipante2 = $puntosParticipante2;
    }

    public function render()
    {
        return view('livewire.pantalla-combate', [
            'combate' => $this->combate
        ]);
    }
}
