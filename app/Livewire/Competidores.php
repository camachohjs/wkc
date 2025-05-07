<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Pagination\Paginator;
class Competidores extends Component
{
    use WithPagination, WithFileUploads;

    public $nombre, $apellidos, $competidor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $search, $password = '';
    public $userType = 'alumnos';
    public $selectedButton = '';
    public $passwordStrength = 0;
    public $password_confirmation = '';
    public $modalFormVisible = false;
    public $showPassword = false;
    public $isEditing = false;
    public $perPage = 25;
    public $escuelaSeleccionada, $maestroSeleccionado, $foto_actual;
    public $escuelas = [];
    public $maestros = [];

    #[Title('Competidores')]
    #[Layout('components.layouts.layout')]

    public function create()
    {
        return redirect('competidor-edit');
    }

    public  function  update()
    {
        $currentPage = 1;
        Paginator::currentPageResolver(function () use ($currentPage) {
        return  $currentPage;
        });
	}

    public function buscar()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $competidor = Alumno::findOrFail($id);
        $this->competidor_id = $id;
        $this->nombre = $competidor->nombre;
        $this->apellidos = $competidor->apellidos;
        $this->email = $competidor->email;
        $this->fec = $competidor->fec;
        $this->cinta = $competidor->cinta;
        $this->telefono = $competidor->telefono;
        $this->peso = $competidor->peso;
        $this->estatura = 1;
        $this->genero = $competidor->genero;
        $this->foto_actual = $competidor->foto;
        $this->isEditing = true;
        return redirect()->route('competidor-edit', ['id' => $id]);
    }

    public function delete($id)
    {
        $alumno = Alumno::find($id);

        if ($alumno?->user) {
            $alumno?->user?->alumno()?->delete();
            $alumno?->user->delete();
        }
        $alumno->registros()->delete();

        $alumno->delete();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Competidor eliminado exitosamente.');
    }

    public function showCreateButton()
    {
        return Auth::user() && Auth::user()->maestro;
    }

    public function historico()
    {
        return redirect('alumno-historico');
    }

    public function inscribir($id)
    {
        return redirect()->route('inscribir-admin', ['id' => $id]);
    }

    public function render()
    {
        $competidores = Alumno::with(['escuelas', 'maestros'])
        ->where(function ($query) {
            $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
                    ->orWhere('email', 'like', "%{$this->search}%")
                    ->orWhere('fec', 'like', "%{$this->search}%")
                    ->orWhere('cinta', 'like', "%{$this->search}%")
                    ->orWhere('telefono', 'like', "%{$this->search}%")
                    ->orWhere('peso', 'like', "%{$this->search}%");
        })
        ->orWhereHas('escuelas', function ($query) {
            $query->where('nombre', 'LIKE', "%{$this->search}%");
        })
        ->orWhereHas('maestros', function ($query) {
            $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"]);
        })
        ->paginate($this->perPage);

        return view('livewire.competidores', ['competidores' => $competidores]);
    }
}
