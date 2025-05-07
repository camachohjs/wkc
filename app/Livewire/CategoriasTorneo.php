<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\Fusion;
use App\Models\Torneo;
use App\Models\Seccion;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class CategoriasTorneo extends Component
{
    public $id, $categoriasFiltradas, $torneo;

    #[Title('Categorias del torneo')]
    #[Layout('components.layouts.layout')]

    public function mount()
    {
        $this->torneo = Torneo::with(['categorias' => function ($query) {
            $query->withPivot('area', 'horario')->with(['forma.seccion']);
        }, 'fusiones'])->findOrFail($this->id);
        
        $categoriasConForma = $this->torneo->categorias->map(function ($categoria) {
        $horario = isset($categoria->pivot->horario) ? Carbon::parse($categoria->pivot->horario)->format('H:i \h\r\s / d-m-Y') : 'N/A';
        
            return [
                'tipo' => 'Categoría',
                'nombre' => $categoria->nombre,
                'division' => $categoria->division,
                'edad_minima' => $categoria->edad_minima,
                'edad_maxima' => $categoria->edad_maxima,
                'genero' => $categoria->genero,
                'area' => $categoria->pivot->area ?? 'N/A',
                'horario' => $horario,
                'forma_id' => $categoria->forma_id,
                'peso_maximo' => $categoria->peso_maximo,
                'peso_minimo' => $categoria->peso_minimo,
            ];
        });
    
        $fusionesSinForma = $this->torneo->fusiones->map(function ($fusion) {
            return [
                'tipo' => 'Fusión',
                'nombre' => $fusion->nombre,
                'division' => $fusion->division,
                'edad_minima' => $fusion->edad_minima,
                'edad_maxima' => $fusion->edad_maxima,
                'genero' => $fusion->genero,
                'area' => $fusion->area ?? 'N/A',
                'horario' => Carbon::parse($fusion->horario)->format('H:i \h\r\s / d-m-Y') ?? 'N/A',
                'forma_id' => $fusion->forma_id,
                'peso_maximo' => $fusion->peso_maximo,
                'peso_minimo' => $fusion->peso_minimo,
            ];
        });
    
        $this->categoriasFiltradas = $categoriasConForma->merge($fusionesSinForma);
    }

    public function render()
    {
        return view('livewire.categorias-torneo', [
            'torneo' => $this->torneo,
            'categoriasFiltradas' => $this->categoriasFiltradas,
        ]);
    }
}