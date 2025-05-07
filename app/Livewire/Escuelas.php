<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Escuela;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Pagination\Paginator;

class Escuelas extends Component
{
    use WithPagination;
    
    public $escuela_id, $nombre, $direccion, $profesor1, $search;
    public $isOpen = 0;
    public $perPage =  25;
    public $profesores = [];

    #[Title('Escuelas')]
    #[Layout('components.layouts.layout')]

    public function update()
    {
        $currentPage = 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
        return  $currentPage;
        });
	}

    public function buscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $escuelas = Escuela::with('maestros')
        ->where('nombre', 'LIKE', "%{$this->search}%")
        ->orWhereHas('maestros', function($query) {
            $query->where('nombre', 'like', "%{$this->search}%");
        })
        ->paginate($this->perPage);

        return view('livewire.escuelas', ['escuelas' => $escuelas]);
    }

    public function create()
    {
        return redirect('escuelas-edit');
    }

    public function showCreateSchoolButton()
    {
        return Auth::user() && Auth::user()->maestro;
    }

    public function edit($id)
    {
        $escuela = Escuela::findOrFail($id);
        $this->escuela_id = $id;
        $this->nombre = $escuela->nombre;
        $this->profesor1 = $escuela->profesor1;
        $this->profesores = $escuela->profesores ?: [];

        return redirect()->route('escuelas-edit', ['id' => $id]);
    }

    public function delete($id)
    {
        Escuela::find($id)->delete();
        flash()->options([
                'position' => 'top-center',
            ])->addSuccess('Escuela eliminada correctamente.');
    }

    public function fusionarMaestros($id)
    {
        $escuela = Escuela::findOrFail($id);
        $this->escuela_id = $id;
        $this->nombre = $escuela->nombre;

        return redirect()->route('combinar-sensei', ['id' => $id]);
    }

}
