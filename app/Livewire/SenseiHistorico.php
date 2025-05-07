<?php

namespace App\Livewire;

use App\Models\Maestro;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class SenseiHistorico extends Component
{
    use WithPagination;

    public $maestros, $search = '';
    public $perPage = 25;
    #[Title('Historico sensei')]
    #[Layout('components.layouts.layout')]

    public function buscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
    
        $this->maestros = Maestro::onlyTrashed()
            ->where(function ($query) use ($search) {
                $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
                        ->orWhere('email', 'like', $search);
            })
            ->get();
    
        return view('livewire.sensei-historico', ['maestros' => $this->maestros]);
    }

    public function reactivar($id)
    {
        $maestro = Maestro::onlyTrashed()->findOrFail($id);
        /* dd($maestro->user_id); */
        $maestro->restore();
        if ($maestro->user_id) {
            $maestro->user()->restore();
        }
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Sensei reactivado con éxito.');
        $this->maestros = Maestro::onlyTrashed()->get();
    }

    public function eliminarDefinitivamente($id)
    {
        $maestro = Maestro::onlyTrashed()->findOrFail($id);
        $maestro->registros()->forceDelete();
        $maestro->escuelas()->detach();

        
        $maestro->forceDelete();
        if ($maestro->user_id) {
            $maestro->user()->forceDelete();
        }
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Sensei eliminado exitosamente.');
        $this->maestros = Maestro::onlyTrashed()->get();
    }

    public function profesores()
    {
        return redirect()->route('profesores');
    }

}
