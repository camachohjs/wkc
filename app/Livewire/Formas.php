<?php

namespace App\Livewire;

use App\Models\Forma;
use App\Models\Seccion;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class Formas extends Component
{
    use WithPagination;
    #[Title('Formas')]
    #[Layout('components.layouts.layout')]

    public $id, $seccion_id, $nombre, $search;
    public $perPage =  25;

    public function create()
    {
        return redirect('formas-edit');
    }

    public function edit($id)
    {
        $forma = Forma::findOrFail($id);
        $this->id = $id;
        $this->nombre = $forma->nombre;

        return redirect()->route('formas-edit', ['id' => $id]);
    }

    public function render()
    {
        $query = Forma::query();

        if ($this->search) {
            $query->where(function ($subquery) {
                $subquery->where('nombre', 'LIKE', "%{$this->search}%");
            });
        }

        $formas = $query->paginate($this->perPage);

        return view('livewire.formas', [
            'formas' => $formas,
        ]);
    }
}
