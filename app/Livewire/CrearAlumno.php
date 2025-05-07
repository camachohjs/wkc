<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Escuela;
use App\Models\Maestro;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class CrearAlumno extends Component
{
    public $nombre, $apellidos, $competidor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $search;
    public $escuelaSeleccionada;
    public $maestroSeleccionado;
    public $escuelas = [];
    public $maestros = [];
    #[Title('Crear competidor')]
    #[Layout('components.layouts.layout')]

    public function render()
    {
        return view('livewire.crear-alumno', [
            'escuelaDelMaestro' => $this->cargarEscuelaDelMaestro(),
            'nombreMaestro' => $this->cargarNombreMaestro()
        ]);
    }

    public function mount()
    {
        $this->cargarEscuelas();
        $this->cargarMaestros();
    }

    public function cargarEscuelas()
    {
        $this->escuelas = Escuela::all();
    }

    public function cargarMaestros()
    {
        $escuela = Escuela::with('maestros')->find($this->escuelaSeleccionada);
    
        if ($escuela) {
            $this->maestros = [];
    
            foreach ($escuela->maestros as $maestro) {
                $this->maestros[] = $maestro;
            }
        } else {
            $this->maestros = [];
        }
    }

    public function cargarEscuelaDelMaestro()
    {
        $usuario_id = auth()->user()->id;
        $maestro = Maestro::where('user_id', $usuario_id)->firstOrFail();
        $escuelaSeleccionada = $maestro->escuelas->first();
        return $escuelaSeleccionada;
    }

    public function cargarNombreMaestro()
    {
        $usuario_id = auth()->user()->id;
        $maestro = Maestro::where('user_id', $usuario_id)->firstOrFail();
        return $maestro->nombre;
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'email|nullable',
            'fec' => 'required|date',
            'cinta' => 'required|string',
            'telefono' => 'nullable|string',
            'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            
            'genero' => 'required|string',
            'foto' => 'mimes:jpeg,png,jpg|max:5120|nullable',
        ]);

        $nombre_imagen = '';

        if ($this->foto) {
            $nombre_imagen = $this->foto ? Str::uuid() . '.' . $this->foto->getClientOriginalExtension() : '';
            $ruta_storage = 'Img/users/' . $nombre_imagen;
            Storage::disk('public')->put($ruta_storage, file_get_contents($this->foto->getRealPath()));
            $urlImagen = asset(Storage::url($ruta_storage));
        } else {
            $urlImagen = null;
        }

        $usuario_id = auth()->user()->id;
        $maestro = Maestro::where('user_id', $usuario_id)->firstOrFail();
        $escuelaSeleccionada = $maestro->escuelas->first();
        /* dd($escuelaSeleccionada, $maestro); */

        if (!$escuelaSeleccionada) {
            flash()->options([
                'position' => 'top-center',
            ])->addError('', 'No se ha encontrado una escuela asociada al maestro.');
            return;
        }

        $alumno = Alumno::firstOrCreate(['id' => $this->competidor_id], [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => null,
            'fec' => $this->fec,
            'cinta' => $this->cinta,
            'telefono' => $this->telefono,
            'peso' => $this->peso,
            'estatura' => 1,
            'genero' => $this->genero,
            'user_id' => null,
            'foto' => $urlImagen,
            'escuela_id' => $this->escuelaSeleccionada,
            'maestro_id' => $this->maestroSeleccionado,
        ]);

        $alumno->escuelas()->sync([$escuelaSeleccionada->id => ['maestro_id' => $maestro->id]]);

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Competidor creado correctamente.');

        return redirect()->to('/mis-competidores');
    }
}
