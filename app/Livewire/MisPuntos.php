<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Categoria;
use App\Models\Maestro;
use App\Models\RegistroTorneo;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class MisPuntos extends Component
{
    public $competidor;

    public $competenciaSelected;

    public $competenciasBusqueda;

    
    #[Title('Mis Puntos')]
    #[Layout('components.layouts.layout')]

    public function mount()
    {
        $this->competenciasBusqueda = collect();
    }

    public function agregarCompetenciaBusqueda()
    {
        if(!$this->competenciaSelected) return;

        $categoria = Categoria::find($this->competenciaSelected);
        if($this->competenciasBusqueda->contains('id', $this->competenciaSelected)){
            return;
        }

        $this->competenciasBusqueda->push($categoria);
    }

    public function eliminarCompetenciaBusqueda($id)
    {
        if(!$this->competenciasBusqueda->contains('id', $id)) return;

        $categorias = $this->competenciasBusqueda->reject(function ($categoria) use ($id) {
            return $categoria->id === $id;
        });

        $this->competenciasBusqueda = $categorias;
    }

    public function render()
    {
        $competidor = auth()->user()->id;
        /* dd($competidor); */
        $idsCompetenciasBusqueda = $this->competenciasBusqueda->pluck('id')->toArray();
    
        $puntajesPorCompetidor = RegistroTorneo::query()
            ->with(['categoria', 'alumno', 'maestro'])
            ->select(
                'categoria_id',
                'alumno_id',
                'maestro_id',
                DB::raw('SUM(puntaje) as total_puntos')
            )
            ->with('categoria')
            ->where(function($query) use ($competidor) {
                $query->whereHas('alumno', function($subQuery) use ($competidor) {
                    $subQuery->where('user_id', $competidor);
                })
                ->orWhereHas('maestro', function($subQuery) use ($competidor) {
                    $subQuery->where('user_id', $competidor);
                });
            })
            ->when($idsCompetenciasBusqueda, function($query) use ($idsCompetenciasBusqueda) {
                $query->whereIn('categoria_id', $idsCompetenciasBusqueda);
            })
            ->groupBy('categoria_id', 'alumno_id', 'maestro_id')
            ->orderByDesc('total_puntos')
            ->get();
    
            $resultados = [];

            foreach ($puntajesPorCompetidor as $puntaje) {
                $competidorId = $puntaje->alumno_id ?? $puntaje->maestro_id;
                $categoriaId = $puntaje->categoria_id;
                $competidor = $puntaje->alumno ?? $puntaje->maestro;
            
                if (!isset($resultados[$categoriaId])) {
                    $resultados[$categoriaId] = [
                        'nombre_categoria' => $puntaje->categoria->nombre,
                        'division' => $puntaje->categoria->division,
                        'competidores' => []
                    ];
                }

                $resultados[$categoriaId]['competidores'][$competidorId] = [
                    'total_puntos' => $puntaje->total_puntos,
                    'nombre_competidor' => $competidor->nombre,
                    'apellido_competidor' => $competidor->apellidos,
                    'foto' => $competidor->foto ?? asset("libs/images/profile/user-1.png"),
                ];
        }

        foreach ($resultados as &$categoria) {
            usort($categoria['competidores'], function ($a, $b) {
                return $b['total_puntos'] <=> $a['total_puntos'];
            });
        }

        $categoriasLista = Categoria::get();

        return view('livewire.mis-puntos', [
            'resultados' => $resultados,
            'categoriasLista' => $categoriasLista
        ]);
    }
}
