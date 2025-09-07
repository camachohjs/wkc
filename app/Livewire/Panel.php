<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use App\Models\Maestro;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Panel extends Component
{
    use WithFileUploads;

    #[Title('Panel')]
    #[Layout('components.layouts.layout')]
    public $section = 'default';
    public $nombre, $apellidos, $competidor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero;
    public $foto, $foto_actual, $search, $password = '';
    public $userId;

    public function mount($section = 'default')
    {
        $this->section = $section;
        $this->userId = auth()->id();

        // Buscar en Alumno o Maestro
        $usuario = Alumno::where('user_id', $this->userId)->first()
                 ?? Maestro::where('user_id', $this->userId)->first();

        if ($usuario) {
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
        } else {
            // Si no existe, usar datos básicos de User
            $user = auth()->user();
            $this->nombre = $user->name ?? '';
            $this->apellidos = '';
            $this->email = $user->email ?? '';
            $this->fec = null;
            $this->cinta = '';
            $this->telefono = '';
            $this->peso = null;
            $this->estatura = null;
            $this->genero = '';
            $this->foto_actual = null;
        }
    }

    public function render()
    {
        return view('livewire.panel');
    }

    public function store()
    {
        $this->validate([
            'nombre' => 'required|string',
            'apellidos' => 'nullable|string',
            'email' => 'required|email',
            'fec' => 'nullable|date',
            'cinta' => 'nullable|string',
            'telefono' => 'nullable|string',
            'peso' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'estatura' => 'nullable|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'genero' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
        ]);

        // Manejo de imagen
        $urlImagen = $this->foto_actual;
        if ($this->foto) {
            $nombre_imagen = Str::uuid() . '.' . $this->foto->getClientOriginalExtension();
            $ruta_storage = 'Img/users/' . $nombre_imagen;
            Storage::disk('public')->put($ruta_storage, file_get_contents($this->foto->getRealPath()));
            $urlImagen = Storage::url($ruta_storage);
        }

        $datos = [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'fec' => $this->fec,
            'cinta' => $this->cinta,
            'telefono' => $this->telefono,
            'peso' => $this->peso,
            'estatura' => $this->estatura ?? 1,
            'genero' => $this->genero,
            'foto' => $urlImagen,
        ];

        // Actualizar Alumno o Maestro si existe
        if (Alumno::where('user_id', $this->userId)->exists()) {
            Alumno::where('user_id', $this->userId)->update($datos);
        } elseif (Maestro::where('user_id', $this->userId)->exists()) {
            Maestro::where('user_id', $this->userId)->update($datos);
        }

        // Actualizar tabla users
        $user = User::find($this->userId);
        if ($user) {
            $user->update([
                'name' => $this->nombre,
                'email' => $this->email,
            ]);
        }

        flash()->addSuccess('Perfil actualizado correctamente.');

        return redirect()->route('panel');
    }
}
