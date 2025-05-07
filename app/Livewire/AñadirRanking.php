<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\RankingTorneo;
use App\Models\Torneo;
use App\Models\Categoria;
use App\Models\Alumno;
use App\Models\Maestro;

class AñadirRanking extends Component
{
    public $torneos, $categorias, $alumnos, $maestros;
    public $torneoId, $categoriaId, $alumnoId, $maestroId, $puntos;
    
    #[Title('Añadir Ranking')]
    #[Layout('components.layouts.layout')]
    
    public function mount()
    {
        $this->torneos = Torneo::all();
        $this->categorias = Categoria::whereNull('deleted_at')->get();
        $this->alumnos = Alumno::whereNull('deleted_at')->get();
        $this->maestros = Maestro::whereNull('deleted_at')->get();
    }

    public function limpiarAlumno()
    {
        $this->alumnoId = null;
    }

    public function limpiarMaestro()
    {
        $this->maestroId = null;
    }
    
    public function guardarRanking()
    {
        $this->validate([
            'torneoId' => 'required',
            'categoriaId' => 'required',
            'puntos' => 'required|numeric',
        ]);
        $torneo = Torneo::find($this->torneoId);

        RankingTorneo::create([
            'torneo_id' => $this->torneoId,
            'categoria_id' => $this->categoriaId,
            'alumno_id' => $this->alumnoId ?: null,
            'maestro_id' => $this->maestroId ?: null,
            'puntos' => $this->puntos,
            'nombre_torneo' => $torneo->nombre,
            'año' => $this->determinarAno(),
        ]); 

        $this->reset(['torneoId', 'categoriaId', 'alumnoId', 'maestroId', 'puntos']);
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Ranking guardado exitosamente.');
        redirect()->route('ranking');
    }

    private function determinarAno()
    {
        $fechaActual = now();
        $anoInicio = $fechaActual->month >= 8 ? $fechaActual->year : $fechaActual->year - 1;
        $anoFin = $anoInicio + 1;
        return "{$anoInicio}-{$anoFin}";
    }

    public function render()
    {
        return view('livewire.añadir-ranking');
    }
}