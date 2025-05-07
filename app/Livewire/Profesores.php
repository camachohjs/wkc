<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alumno;
use App\Models\Maestro;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Pagination\Paginator;
class Profesores extends Component
{
    use WithPagination;

    public $nombre, $apellidos, $profesor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $search, $password = '';
    public $userType = 'alumnos';
    public $selectedButton = '';
    public $passwordStrength = 0;
    public $password_confirmation = '';
    public $modalFormVisible = false;
    public $showPassword = false;
    public $isEditing = false;
    public $perPage = 25;
    use WithFileUploads;
    public $escuelaSeleccionada, $maestroSeleccionado, $foto_actual;
    public $escuelas = [];
    public $maestros = [];

    #[Title('Sensei')]
    #[Layout('components.layouts.layout')]

    public function create()
    {
        return redirect('profesor-edit');
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
        $profesor = Maestro::findOrFail($id);
        $this->profesor_id = $id;
        $this->nombre = $profesor->nombre;
        $this->apellidos = $profesor->apellidos;
        $this->email = $profesor->email;
        $this->fec = $profesor->fec;
        $this->cinta = $profesor->cinta;
        $this->telefono = $profesor->telefono;
        $this->peso = $profesor->peso;
        $this->estatura = 1;
        $this->genero = $profesor->genero;
        $this->foto_actual = $profesor->foto;
        $this->isEditing = true;
        return redirect()->route('profesor-edit', ['id' => $id]);
    }

    public function delete($id)
    {
        $maestro = Maestro::find($id);

        if ($maestro?->user) {
            $maestro?->user?->maestro()?->delete();
            $maestro?->user->delete();
        }
        $maestro->registros()->delete();

        $maestro->delete();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'sensei eliminado exitosamente.');
    }

    public function showCreateButton()
    {
        return Auth::user() && Auth::user()->maestro;
    }

    public function historico()
    {
        return redirect('sensei-historico');
    }

    public function render()
    {
        $profesores = Maestro::with(['escuelas'])
        ->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
        ->orWhere('email', 'like', "%{$this->search}%")
        ->orWhere('fec', 'like', "%{$this->search}%")
        ->orWhere('cinta', 'like', "%{$this->search}%")
        ->orWhere('telefono', 'like', "%{$this->search}%")
        ->orWhere('peso', 'like', "%{$this->search}%")
        ->orWhereHas('escuelas', function ($query) {
            $query->where('nombre', 'LIKE', "%{$this->search}%");
        })
        ->paginate($this->perPage);

        return view('livewire.profesores', ['profesores' => $profesores]);
    }
}
