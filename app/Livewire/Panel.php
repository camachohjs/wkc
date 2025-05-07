<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\Maestro;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Panel extends Component
{
    use WithFileUploads;
    #[Title('Panel')]
    #[Layout('components.layouts.layout')]
    public $section = 'default';
    public $nombre, $apellidos, $competidor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $foto_actual, $search, $password = '';
    public $userId;

    public function mount()
    {
        $this->section = request()->segment(2, 'default');
        $usuario_id = auth()->user()->id;

        $usuario = Alumno::where('user_id', $usuario_id)->first();

        // Si no se encuentra como Alumno, intenta como Maestro
        if (!$usuario) {
            $usuario = Maestro::where('user_id', $usuario_id)->first();
        }
        $this->nombre = $usuario->nombre;
        $this->apellidos = $usuario->apellidos;
        $this->email = $usuario->email;
        $this->fec = $usuario->fec;
        $this->cinta = $usuario->cinta;
        $this->telefono = $usuario->telefono;
        $this->peso = $usuario->peso;
        $this->estatura = number_format($usuario->estatura, 2);
        $this->genero = $usuario->genero;
        $this->foto_actual = $usuario->foto;
    
        return $usuario;
    }

    public function render()
    {
        return view('livewire.panel');
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'fec' => 'required|date',
            'cinta' => 'required|string',
            'telefono' => 'nullable|string',
            'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            
            'genero' => 'required|string',
            'foto' => 'image|max:5120|nullable',
        ]);

        $nombre_imagen = '';

            if ($this->foto) {
                $nombre_imagen = Str::uuid() . '.' . $this->foto->getClientOriginalExtension();
                $ruta_storage = 'Img/users/' . $nombre_imagen;
                Storage::disk('public')->put($ruta_storage, file_get_contents($this->foto->getRealPath()));
                $urlImagen = asset(Storage::url($ruta_storage));
            } elseif ($this->foto_actual) {
                $urlImagen = $this->foto_actual;
            } else {
                $urlImagen = null;
            }
            
        $usuario_id = auth()->user()->id;

        $datos = [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'fec' => $this->fec,
            'cinta' => $this->cinta,
            'telefono' => $this->telefono,
            'peso' => $this->peso,
            'estatura' => 1,
            'genero' => $this->genero,
            'foto' => $urlImagen,
        ];
    
        $alumnoActualizado = Alumno::where('user_id', $usuario_id)->update($datos);
        if ($alumnoActualizado){
            $usuario = Alumno::where('user_id', $usuario_id)->first();
            $user = User::find($usuario->user_id);
                if ($user && $user->email != $this->email) {
                    $user->update([
                        'email' => $this->email,
                        'name' => $this->nombre,
                    ]);
                }
        }
        
        if (!$alumnoActualizado) {
            Maestro::where('user_id', $usuario_id)->update($datos);
            $maestro = Maestro::where('user_id', $usuario_id)->first();
            $user = User::find($maestro->user_id);
            if ($user && $user->email != $this->email) {
                $user->update([
                    'email' => $this->email,
                    'name' => $this->nombre,
                ]);
            }
        }

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Perfil actualizado correctamente.');

        return redirect()->to('/panel');
    }
}