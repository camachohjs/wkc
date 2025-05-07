<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Maestro;
use App\Models\RegistroTorneo;
use Carbon\Carbon;
use DateTime;
use Illuminate\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class MisCompetidores extends Component
{
    use WithPagination;
    #[Title('Mis competidores')]
    #[Layout('components.layouts.layout')]

    public $perPage = 28;
    public $search;
    public $mostrarRegistrosPorAlumno = [];

    public function mount()
    {
        $alumnos = Alumno::has('registros')->get();
        foreach ($alumnos as $alumno) {
            $this->mostrarRegistrosPorAlumno[$alumno->id] = 3;
        }
    }

    public function cargarMasRegistros($alumnoId)
    {
        if (isset($this->mostrarRegistrosPorAlumno[$alumnoId])) {
            $this->mostrarRegistrosPorAlumno[$alumnoId] += 5;
        } else {
            $this->mostrarRegistrosPorAlumno[$alumnoId] = 6;
        }
    }

    public function paginacion()
    {
        $currentPage = 0;
        Paginator::currentPageResolver(function () use ($currentPage) {
        return  $currentPage;
        });
	}

    public function editar($id) {
        return redirect()->route('editar-registro', ['id' => $id]);
    }

    public function edit($id)
    {
        Alumno::findOrFail($id);
        return redirect()->route('competidor-edit', ['id' => $id]);
    }

    public function registrar($id)
    {
        $usuario = Alumno::find($id);
        if (!$usuario) {
            $usuario = Maestro::find($id);
            if (!$usuario) {
                abort(404, 'Usuario no encontrado.');
            }
        }
        $id = $usuario->id;

        return redirect()->route('proximos-eventos-maestros', ['id' => $id]);
    }

    public function delete($id)
    {
        $registro = RegistroTorneo::find($id);

        $registro->delete();

        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Registro eliminado exitosamente.');
    }

    public function render()
    {
        $user = auth()->user();
        $maestroId = $user->maestro->id;
    
        $maestro = Maestro::with(['alumnos.registros.torneo', 'alumnos.registros.categoria'])
                            ->where('id', $maestroId)
                            ->with(['registros' => function($query) {
                                $query->with('torneo', 'categoria')
                                ->whereHas('torneo', function ($query) {
                                    $query->where('fecha_evento', '>=', Carbon::now());
                                })
                                ->whereHas('categoria'); 
                            }])
                            ->first();
    
        $atletas = Alumno::whereHas('escuelas', function ($query) use ($maestroId) {
                                $query->where('maestro_id', $maestroId);
                            })
                            ->with(['registros' => function($query) {
                                $query->with('torneo', 'categoria')
                                ->whereHas('torneo', function ($query) {
                                    $query->where('fecha_evento', '>=', Carbon::now());
                                })
                                ->whereHas('categoria'); 
                            }])
                            ->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
                            ->paginate($this->perPage);
    
            $maestroComoAtleta = new Alumno([
                'nombre' => $maestro->nombre,
                'apellidos' => $maestro->apellidos,
                'fec' => $maestro->fec,
                'peso' => $maestro->peso,
                'user_id' => $maestro->user_id,
                'foto' => $maestro->foto,
            ]);

            $fechaNacimiento = new DateTime($maestro->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $edad = $edad-1;
            }
            $maestroComoAtleta->edad = $edad;

            $maestroComoAtleta = Maestro::with(['registros' => function($query) use ($maestro) { 
                $query->with('torneo', 'categoria')
                    ->where('maestro_id', $maestro->id)
                    ->whereHas('torneo', function ($query) {
                        $query->where('fecha_evento', '>=', Carbon::now());
                    });
            }])->find($maestro->id);
            $atletas->prepend($maestroComoAtleta);
    
        return view('livewire.mis-competidores', ['atletas' => $atletas, 'maestro' => $maestro]);
    }

    public function buscar()
    {
        $this->resetPage();
    }

    public function deleteAlumno($id)
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

    public function create()
    {
        return redirect('crear-competidor');
    }
}
