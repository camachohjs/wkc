<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Forma;
use App\Models\Seccion;
use Livewire\Component;
use App\Models\Torneo;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Categorias extends Component
{
    #[Title('Categorias')]
    #[Layout('components.layouts.layout')]

    public $id, $formas, $secciones, $nombre, $descripcion, $edad_minima, $edad_maxima, $peso_minimo, $peso_maximo, $cinta, $genero, $division;
    public $categoria, $search;

    public function create()
    {
        return redirect('categorias-edit');
    }

    public function vistaformas()
    {
        return redirect('formas');
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        $this->id = $id;
        $this->nombre = $categoria->nombre;
        $this->descripcion = $categoria->descripcion;
        $this->edad_minima = $categoria->edad_minima;
        $this->edad_maxima = $categoria->edad_maxima;
        $this->peso_minimo = $categoria->peso_minimo;
        $this->peso_maximo = $categoria->peso_maximo;
        $this->cinta = $categoria->cinta;
        $this->genero = $categoria->genero;
        $this->division = $categoria->division;

        return redirect()->route('categorias-edit', ['id' => $id]);
    }

    public function delete($id)
    {
        $categoria = Categoria::find($id);

        if ($categoria) {
            $categoria?->delete();
        }
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Categoria eliminada exitosamente.');
    }

    public function render()
    {
        $categoriasQuery = Categoria::query();

        if (!empty($this->search)) {
            $categoriasQuery->where(function ($query) {
                $query->where('nombre', 'LIKE', "%{$this->search}%")
                    ->orWhere('division', 'LIKE', "%{$this->search}%")
                    ->orWhereHas('forma', function ($query) {
                        $query->where('nombre', 'LIKE', "%{$this->search}%");
                    })
                    ->orWhereHas('forma.seccion', function ($query) {
                        $query->where('nombre', 'LIKE', "%{$this->search}%");
                    });
            });
        }
        $categoriasQuery->whereNull('categoria_padre_id');
    
        $categorias = $categoriasQuery->get();
    
        $formasIds = $categorias->pluck('forma_id')->unique();
        $seccionesIds = Forma::whereIn('id', $formasIds)->pluck('seccion_id')->unique();
    
        $formas = Forma::whereIn('id', $formasIds)->get();
        $secciones = Seccion::whereIn('id', $seccionesIds)->get();
    
        return view('livewire.categorias', [
            'categorias' => $categorias,
            'formas' => $formas,
            'secciones' => $secciones
        ]);
    }

    public function mount(){
        $this->secciones = Seccion::with('formas')->get();
        $this->formas = Forma::with('categorias')->get();
    }

}
