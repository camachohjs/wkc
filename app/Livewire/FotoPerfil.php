<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Maestro;
use App\Models\Alumno;
use App\Models\User;

class FotoPerfil extends Component
{
    use WithFileUploads;
    #[Layout('components.layouts.layout')]
    public $section = 'default';
    public $foto;
    public $userId;

    public function mount()
    {
        $this->section = request()->segment(2, 'default');
    }

    public function render()
    {
        $usuario = $this->obtenerUsuario();
        return view('livewire.foto-perfil', ['usuario' => $usuario]);
    }

    public function obtenerUsuario()
    {
        $usuario_id = auth()->user()->id;

        // Primero, intenta encontrar el usuario como Alumno
        $usuario = Alumno::where('user_id', $usuario_id)->first();

        // Si no se encuentra como Alumno, intenta como Maestro
        if (!$usuario) {
            $usuario = Maestro::where('user_id', $usuario_id)->first();
        }

        return $usuario;
    }
}