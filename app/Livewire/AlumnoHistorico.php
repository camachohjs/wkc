<?php

namespace App\Livewire;

use App\Models\Alumno;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class AlumnoHistorico extends Component
{
    use WithPagination;

    public $alumnos, $search = '';
    public $perPage = 25;
    #[Title('Historico competidores')]
    #[Layout('components.layouts.layout')]

    public function buscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = '%' . $this->search . '%';
    
        $this->alumnos = Alumno::onlyTrashed()
            ->where(function ($query) use ($search) {
                $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
                        ->orWhere('email', 'like', $search);
            })
            ->get();
    
        return view('livewire.alumno-historico', ['alumnos' => $this->alumnos]);
    }

    public function reactivar($id)
    {
        $alumno = Alumno::onlyTrashed()->findOrFail($id);
        /* dd($alumno->user_id); */
        $alumno->restore();
        if ($alumno->user_id) {
            $alumno->user()->restore();
        }
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Competidor reactivado con éxito.');
        $this->alumnos = Alumno::onlyTrashed()->get();
    }

    public function eliminarDefinitivamente($id)
    {
        $alumno = Alumno::onlyTrashed()->findOrFail($id);
        $alumno->registros()->forceDelete();
        $alumno->escuelas()->detach();

        
        $alumno->forceDelete();
        if ($alumno->user_id) {
            $alumno->user()->forceDelete();
        }
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Competidor eliminado exitosamente.');
        $this->alumnos = Alumno::onlyTrashed()->get();
    }

    public function competidores()
    {
        return redirect()->route('competidores');
    }

}
