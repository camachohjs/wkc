<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Escuela;
use App\Models\Maestro;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class EscuelasEdit extends Component
{
    public $escuela_id, $nombre, $profesores = [], $profesores_adicionales = [], $profesor1, $profesor1Id, $sugerenciaEscuela = null;
    #[Title('Escuelas')]
    #[Layout('components.layouts.layout')]

    public function resetInputFields()
    {
        $this->escuela_id = null;
        $this->nombre = '';
        $this->profesores = [];
    }

    public function mount()
    {
        $this->escuela_id = request()->route('id');
    
        if ($this->escuela_id) {
            $escuela = Escuela::findOrFail($this->escuela_id);
    
            $this->nombre = $escuela->nombre;
            $maestros = $escuela->maestros;
    
            $this->profesor1 = $maestros->first() ? $maestros->first()->nombre.' '.$maestros->first()->apellidos : null;
    
            $this->profesores = $maestros->skip(1)->map(function($maestro) {
                return $maestro->nombre . ' ' . $maestro->apellidos;
            })->toArray();
        }
    }

    public function updated($propertyName, $value)
    {
        if ($propertyName === 'nombre') {
            $this->sugerenciaEscuela = null; 
    
            $escuelasSimilares = Escuela::where('nombre', 'LIKE', "%{$value}%")->get();
            /* dd($escuelasSimilares); */
    
            if ($escuelasSimilares->count() > 0) {
                $this->sugerenciaEscuela = $escuelasSimilares->first();
                $this->dispatch('sugerirEscuela', ['escuela' => ['nombre' => $this->sugerenciaEscuela->nombre, 'id' => $this->sugerenciaEscuela->id]]);
            }
        }

        if ($propertyName === 'profesor1') {
            $idsProfesor1 = $this->buscarProfesores($value);
            /* dd($idsProfesor1); */

            $this->profesor1Id = $idsProfesor1[0] ?? null;
        } elseif (Str::startsWith($propertyName, 'profesores.')) {

            $index = Str::after($propertyName, 'profesores.');
            $this->actualizarProfesorAdicional($index, $value);
        }
    }
    
    protected function actualizarProfesorAdicional($index, $nombreCompleto)
    {
        /* dd($nombreCompleto); */
        $ids = $this->buscarProfesores($nombreCompleto);
        /* dd($ids); */
        $this->profesores_adicionales[$index] = $ids[0] ?? null;
        $this->profesores_adicionales = array_values($this->profesores_adicionales);
        /* dd($this->profesores); */
    }
    
    private function buscarProfesores($nombresProfesores)
    {
        if (is_string($nombresProfesores)) {
            $nombresProfesores = [$nombresProfesores];
        }
    
        $ids = [];
        
        foreach ($nombresProfesores as $nombreCompleto) {
            if (empty($nombreCompleto)) continue;
            
            $partes = explode(' ', $nombreCompleto);
            $nombre = $partes[0];
            $apellido = count($partes) > 1 ? implode(' ', array_slice($partes, 1)) : '';
    
            $query = Maestro::query();
    
            if (!empty($nombre)) {
                $query->where('nombre', 'LIKE', "%{$nombre}%");
            }
            
            if (!empty($apellido)) {
                $query->where('apellidos', 'LIKE', "%{$apellido}%");
            }
            
            $profesor = $query->first();
            
            if ($profesor) {
                $ids[] = $profesor->id;
            }
        }
        
        return $ids;
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required',
            'profesor1' => 'required',
        ]);
    
        $escuela = Escuela::updateOrCreate(['id' => $this->escuela_id], [
            'nombre' => $this->nombre,
        ]);
    
        $idsProfesor1 = is_numeric($this->profesor1) 
                        ? [$this->profesor1] 
                        : [$this->guardarOBuscarProfesor($this->profesor1)];
    
        $idsProfesoresAdicionales = [];
        foreach ($this->profesores as $profesor) {
            /* dd($this->profesores); */
            if (is_numeric($profesor)) {
                $idsProfesoresAdicionales[] = $profesor;
            } else {
                /* dd($profesor); */
                $idsEncontrados = $this->guardarOBuscarProfesor($profesor);
                /* dd($idsEncontrados); */
                if (is_array($idsEncontrados)) {
                    $idsProfesoresAdicionales[] = $idsEncontrados[0];
                } else {
                    $idsProfesoresAdicionales[] = $idsEncontrados;
                }
            }
        }
    
        $profesoresIds = array_unique(array_filter(array_merge($idsProfesor1, $idsProfesoresAdicionales)));
        /* dd($profesoresIds); */
    
        $escuela->maestros()->sync($profesoresIds);
    
        flash()->options([
                'position' => 'top-center',
            ])->addSuccess('Escuela actualizada correctamente.');
    
        return redirect()->to('/escuelas');
    }

    private function guardarOBuscarProfesor($nombreCompleto)
    {
        $partes = explode(' ', $nombreCompleto);
        $nombre = $partes[0];
        $apellido = count($partes) > 1 ? implode(' ', array_slice($partes, 1)) : '';
    
        $profesor = Maestro::where('nombre', 'like', '%' . $nombre . '%')
                            ->where('apellidos', 'like', '%' . $apellido . '%')
                            ->first();
    
        if (!$profesor) {
            $profesor = Maestro::create([
                'nombre' => $nombre,
                'apellidos' => $apellido,
                'user_id' => null
            ]);
        }
    
        return $profesor->id;
    }

    public function render()
    {
        return view('livewire.escuelas-edit');
    }

    public function agregarProfesor()
    {
        $this->profesores[] = '';
    }

    public function eliminarProfesor($index)
    {
        unset($this->profesores[$index]);
        $this->profesores = array_values($this->profesores); 
    }
}
