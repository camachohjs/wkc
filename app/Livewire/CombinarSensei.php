<?php

namespace App\Livewire;

use App\Models\Escuela;
use App\Models\Maestro;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class CombinarSensei extends Component
{
    public $escuela_id, $nombre, $maestros = [], $profesores_adicionales = [], $profesor1, $profesor1Id, $idMantener = null, $idEliminar = null;
    #[Title('Combinar Sensei')]
    #[Layout('components.layouts.layout')]

    public function mount(){
        $this->escuela_id = request()->route('id');
    
        if ($this->escuela_id) {
            $escuela = Escuela::with('maestros')->find($this->escuela_id);
    
            $this->nombre = $escuela->nombre;
            if ($escuela) {
                $this->maestros = [];
        
                foreach ($escuela->maestros as $maestro) {
                    $this->maestros[] = $maestro;
                }
            } else {
                $this->maestros = [];
            }
        }
    }

    public function render()
    {
        return view('livewire.combinar-sensei');
    }

    public function setIdEliminar($id)
    {
        $this->idEliminar = $id;
    }

    public function setIdMantener($id)
    {
        $this->idMantener = $id;
    }

    public function combinarMaestros($idEliminar, $idMantener)
    {
        /* dd($idMantener, $idEliminar); */
        if ($idEliminar === $idMantener) {
            flash()->options([
                'position' => 'top-center',
            ])->addError('','No puedes combinar el mismo Sensei.');
            return;
        }

        DB::transaction(function () use ($idEliminar, $idMantener) {
            $maestroEliminar = Maestro::findOrFail($idEliminar);
            $maestroMantener = Maestro::findOrFail($idMantener);
    
            if ($maestroEliminar->email && Maestro::where('email', $maestroEliminar->email)->where('id', '!=', $maestroMantener->id)->exists()) {
                $maestroMantener->email = $maestroEliminar->email . '+merged';
            } else {
                $maestroMantener->email = $maestroEliminar->email;
            }
    
            $camposATransferir = ['fec', 'cinta', 'telefono', 'peso', 'estatura', 'genero', 'user_id', 'foto'];
            foreach ($camposATransferir as $campo) {
                if (!empty($maestroEliminar->$campo)) {
                    $maestroMantener->$campo = $maestroEliminar->$campo;
                }
            }
            $maestroMantener->save();
        
            foreach ($maestroEliminar->alumnos as $alumno) {
                $escuelaId = $alumno->pivot->escuela_id;
                $maestroEliminar->alumnos()->detach($alumno->id);
                $maestroMantener->alumnos()->attach($alumno->id, ['escuela_id' => $escuelaId]);
            }
    
            foreach ($maestroEliminar->escuelas as $escuela) {
                $maestroEliminar->escuelas()->detach($escuela->id);
                if (!$maestroMantener->escuelas->contains($escuela->id)) {
                    $maestroMantener->escuelas()->attach($escuela->id);
                }
            }

            foreach ($maestroEliminar->registros as $registro) {
                $atributos = [
                    'torneo_id' => $registro->torneo_id,
                    'categoria_id' => $registro->categoria_id
                ];
            
                $valores = [
                    'email' => $registro->email, 
                    'cinta' => $registro->cinta,
                    'peso' => $registro->peso,
                    'fec' => $registro->fec,
                    'estatura' => 1,
                    'genero' => $registro->genero,
                    'nombre' => $registro->nombre,
                    'apellidos' => $registro->apellidos,
                    'telefono' => $registro->telefono,
                ];
            
                $registroExistente = $maestroMantener->registros()->updateOrCreate($atributos, $valores);
            
                $registroExistente->nombre = $maestroMantener->nombre;
                $registroExistente->apellidos = $maestroMantener->apellidos;
                $registroExistente->save(); 
            }
        
            $maestroEliminar->forceDelete();
            if (strpos($maestroMantener->email, '+merged') !== false) {
                $maestroMantener->email = str_replace('+merged', '', $maestroMantener->email);
                $maestroMantener->save();
            }
        });
    
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('','Sensei combinados correctamente.');
        return redirect()->to('/escuelas');
    }

}
