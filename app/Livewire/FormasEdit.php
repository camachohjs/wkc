<?php

namespace App\Livewire;

use App\Models\Forma;
use App\Models\Seccion;
use App\Models\TiposFormas;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class FormasEdit extends Component
{
    #[Title('Formas')]
    #[Layout('components.layouts.layout')]

    public $id, $seccion_id, $nombre, $secciones, $seccionSeleccionada, $tipoFormaSeleccionada, $tiposFormas;

    public function resetInputFields()
    {
        $this->id = null;
        $this->nombre = '';
        $this->seccionSeleccionada = null;
        $this->tipoFormaSeleccionada = null;
    }

    public function mount()
    {
        $this->id = request()->route('id');

        if ($this->id) {
            $forma = Forma::findOrFail($this->id);

            $this->nombre = $forma->nombre;
            $this->seccionSeleccionada = $forma->seccion_id;
            $this->tipoFormaSeleccionada = $forma->tipos_formas_id;
        }

        $this->secciones = Seccion::all();
        $this->tiposFormas = TiposFormas::whereIn('nombre', ['BÁSICA (KATA Y/O COMBATE)', 'C. ESPECIAL O CUARTETAS', 'EQUIPOS COMBATE'])->get();
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required',
            'seccionSeleccionada' => 'nullable|numeric',
            'tipoFormaSeleccionada' => 'nullable|numeric',
        ]);

        $data = [
            'nombre' => $this->nombre,
            'seccion_id' => $this->seccionSeleccionada,
            'tipos_formas_id' => $this->tipoFormaSeleccionada,
        ];

        Forma::updateOrCreate(['id' => $this->id], $data);

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', $this->id ? 'Forma actualizada correctamente.' : 'Forma creada correctamente.');

        $this->resetInputFields();
        return redirect('/formas');
    }

    public function render()
    {
        return view('livewire.formas-edit');
    }
}