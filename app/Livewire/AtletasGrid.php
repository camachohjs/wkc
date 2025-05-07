<?php

namespace App\Livewire;

use App\Models\Alumno;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;

class AtletasGrid extends Component
{
    use WithPagination;

    public $perPage = 28;

    public function paginacion()
    {
        $currentPage = 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
        return  $currentPage;
        });
	}
    
    public function render()
    {
        $atletas = Alumno::paginate($this->perPage);
        return view('livewire.atletas-grid', ['atletas' => $atletas]);
    }
}

