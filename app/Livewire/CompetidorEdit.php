<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\Alumno;
use App\Models\Escuela;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use ZxcvbnPhp\Zxcvbn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CompetidorEdit extends Component
{
    use WithFileUploads;

    public $nombre, $apellidos, $competidor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $search, $password = '', $nacionalidad;
    public $userType = 'alumnos';
    public $selectedButton = '';
    public $passwordStrength = 0;
    public $password_confirmation = '';
    public $showPassword = false;
    public $isEditing = false;
    public $perPage = 25;
    public $escuelaSeleccionada;
    public $maestroSeleccionado;
    public $escuelaOriginal;
    public $escuelas = [];
    public $maestros = [];
    public $profesoresEncontrados = [];
    public $foto_actual;
    public $nacionalidades = [];
    public $mayor_de_edad = false;
    public $cinta_negra = false;
    public $tipoIngreso = 'fecha';
    public $edad;

    #[Title('Competidores')]
    #[Layout('components.layouts.layout')]

    public $isVisible = true;
    public $esVisible = true;
    public $esVisibleCinta = true;

    public function updatedFec($value)
    {
        $this->updateVisibility();
        $this->updateVisibilidad();
    }

    public function updatedCinta($value)
    {
        $this->updateVisibilidadCinta();
    }

    public function cargarNacionalidades()
    {
        return [
                ['codigo' => 'mx', 'nombre' => 'Mexico', 'emoji' => '🇲🇽'],
                ['codigo' => 'ar', 'nombre' => 'Argentina', 'emoji' => '🇦🇷'],
                ['codigo' => 'bo', 'nombre' => 'Bolivia', 'emoji' => '🇧🇴'],
                ['codigo' => 'cl', 'nombre' => 'Chile', 'emoji' => '🇨🇱'],
                ['codigo' => 'co', 'nombre' => 'Colombia', 'emoji' => '🇨🇴'],
                ['codigo' => 'cr', 'nombre' => 'Costa Rica', 'emoji' => '🇨🇷'],
                ['codigo' => 'cu', 'nombre' => 'Cuba', 'emoji' => '🇨🇺'],
                ['codigo' => 'do', 'nombre' => 'Republica Dominicana', 'emoji' => '🇩🇴'],
                ['codigo' => 'ec', 'nombre' => 'Ecuador', 'emoji' => '🇪🇨'],
                ['codigo' => 'sv', 'nombre' => 'El Salvador', 'emoji' => '🇸🇻'],
                ['codigo' => 'gt', 'nombre' => 'Guatemala', 'emoji' => '🇬🇹'],
                ['codigo' => 'hn', 'nombre' => 'Honduras', 'emoji' => '🇭🇳'],
                ['codigo' => 'ni', 'nombre' => 'Nicaragua', 'emoji' => '🇳🇮'],
                ['codigo' => 'pa', 'nombre' => 'Panama', 'emoji' => '🇵🇦'],
                ['codigo' => 'py', 'nombre' => 'Paraguay', 'emoji' => '🇵🇾'],
                ['codigo' => 'pe', 'nombre' => 'Peru', 'emoji' => '🇵🇪'],
                ['codigo' => 'es', 'nombre' => 'Espana', 'emoji' => '🇪🇸'],
                ['codigo' => 'uy', 'nombre' => 'Uruguay', 'emoji' => '🇺🇾'],
                ['codigo' => 've', 'nombre' => 'Venezuela', 'emoji' => '🇻🇪'],
                ['codigo' => 'us', 'nombre' => 'Estados Unidos', 'emoji' => '🇺🇸'],
                ['codigo' => 'ca', 'nombre' => 'Canada', 'emoji' => '🇨🇦'],
                ['codigo' => 'br', 'nombre' => 'Brasil', 'emoji' => '🇧🇷'],
            ];
    }

    private function updateVisibility()
    {
        if ($this->fec) {
            $fechaNacimiento = new DateTime($this->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $edad = $edad-1;
            }

            if ( $edad <= 10){
                $this->isVisible = false;
            } else {
                
                $this->isVisible = true;
            }
        } 
    }

    public function updatedTipoIngreso($value)
    {
        if ($this->competidor_id) {
            $competidor = Alumno::with('escuelas', 'maestros')->findOrFail($this->competidor_id);
            $this->fec = $competidor->fec;

            $fechaNacimiento = new DateTime($this->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $this->edad = $edad-1;
            }
        } else {
            $this->fec = null;
            $this->edad = null;
        }
    }

    public function resetInputFields()
    {
        $this->competidor_id = null;
        $this->nombre = '';
        $this->apellidos = '';
        $this->email = '';
        $this->fec = '';
        $this->cinta = '';
        $this->telefono = '';
        $this->peso = '';
        $this->estatura = '';
        $this->genero = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->foto = null;
        $this->foto_actual = null;
        $this->nacionalidad = '';
        $this->mayor_de_edad = false;
        $this->cinta_negra = false;
    }

    public function mount()
    {
        $this->nacionalidades = $this->cargarNacionalidades();
        $this->competidor_id = request()->route('id');

        if ($this->competidor_id) {
            $competidor = Alumno::with('escuelas', 'maestros')->findOrFail($this->competidor_id);

        $this->nombre = $competidor->nombre;
        $this->apellidos = $competidor->apellidos;
        $this->email = $competidor->email;
        $this->fec = $competidor->fec;
        $this->cinta = $competidor->cinta;
        $this->telefono = $competidor->telefono;
        $this->peso = $competidor->peso;
        $this->estatura = number_format($competidor->estatura, 2);
        $this->genero = $competidor->genero;
        $this->foto_actual = $competidor->foto;
        $this->escuelaSeleccionada = $competidor->escuelas->first()->id ?? null;
        $this->maestroSeleccionado = $competidor->maestros->first()->id ?? null;
        $this->escuelaOriginal = $this->escuelaSeleccionada;
        $this->nacionalidad = $competidor->nacionalidad;
        $this->mayor_de_edad = (bool) $competidor->mayor_de_edad;
        $this->cinta_negra = (bool) $competidor->cinta_negra;
        $this->updateVisibility();
        $this->updateVisibilidad();
        $this->updateVisibilidadCinta();
        }

        $this->cargarEscuelas();
        if ($this->escuelaSeleccionada) {
            $this->cargarMaestros();
        }
    }

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    private function updateVisibilidad()
    {
        if ($this->fec) {
            $fechaNacimiento = Carbon::parse($this->fec);
            $currentYear = Carbon::now()->year;
            $birthYear = $fechaNacimiento->year;
            $añoSiguiente = Carbon::now()->addYear();
    
            // Comprobar si aún no hemos llegado al 1 de enero del próximo año
            if (Carbon::now()->lt(Carbon::createFromDate($añoSiguiente, 1, 1))) {
                $edad = $currentYear - $birthYear - 1;
            } else {
                $edad = $currentYear - $birthYear;
            }

            // Si la edad es mayor o igual a 18 años, desactivar "mayor_de_edad"
            if ($edad >= 18) {
                $this->mayor_de_edad = false; 
                $this->esVisible = false;
            } else {
                $this->esVisible = true;
            }
        } 
    }

    private function updateVisibilidadCinta()
    {
        if ($this->cinta == "Negra") {
            $this->esVisibleCinta = false;
        } else {
            $this->esVisibleCinta = true;
        }
    }

    public function store()
    {
        try {
            $this->validate([
                'nombre' => 'required|string',
                'apellidos' => 'required|string',
                'cinta' => 'required|string',
                'telefono' => 'nullable|string',
                'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                
                'genero' => 'required|string',
                'foto' => 'mimes:jpeg,png,jpg|max:5120|nullable',
                'nacionalidad' => 'required|',
                'mayor_de_edad' => 'required|boolean',
                'cinta_negra' => 'required|boolean',
                'tipoIngreso' => 'required|in:fecha,edad',
                'fec' => $this->tipoIngreso === 'fecha' ? 'required|date' : 'nullable|date',
                'edad' => $this->tipoIngreso === 'edad' ? 'required|integer|min:0' : 'nullable|integer|min:0',
            ]);

            $urlImagen = $this->handlePhotoUpload();

            $user = null;
            if ($this->isVisible && $this->email) {
                $this->validate(['email' => 'nullable|email']);
                if ($this->competidor_id) {
                    $alumnoExistente = Alumno::find($this->competidor_id);
                    if ($alumnoExistente && $alumnoExistente->user) {
                        $alumnoExistente->user->update([
                            'email' => $this->email,
                            'password' => Hash::make($this->password)
                        ]);
                        $user = $alumnoExistente->user;
                    }
                }
        
                if (!$user) {
                    $user = User::create([
                        'name' => $this->nombre,
                        'email' => $this->email,
                        'password' => Hash::make($this->password),
                    ]);
                }
            }

            if ($this->tipoIngreso === 'edad') {
                $edad = $this->edad + 1;
                $currentYear = date('Y');
                $birthYear = $currentYear - $edad;
                $this->fec = date('Y-m-d', strtotime("$birthYear-02-02"));
            }

            $alumno = Alumno::updateOrCreate(['id' => $this->competidor_id], [
                'user_id' => $user ? $user->id : null,
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'email' => $this->isVisible ? $this->email : null,
                'fec' => $this->fec,
                'cinta' => $this->cinta,
                'telefono' => $this->telefono,
                'peso' => $this->peso,
                'estatura' => 1,
                'genero' => $this->genero,
                'foto' => $urlImagen ?: $this->foto_actual,
                'nacionalidad' => $this->nacionalidad,
                'mayor_de_edad' => $this->esVisible ? $this->mayor_de_edad : 0,
                'cinta_negra' => $this->esVisibleCinta ? $this->cinta_negra : 0,
            ]);
            /* dd($this->maestroSeleccionado); */

            $alumno->escuelas()->sync([$this->escuelaSeleccionada => ['maestro_id' => $this->maestroSeleccionado]]);

            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('', $this->competidor_id ? 'Competidor actualizado correctamente.' : 'Competidor creado correctamente.');

            if (auth()->user()->hasRole('supervisor')) {
                return redirect()->to('/mis-competidores');
            } elseif (auth()->user()->hasRole('admin')) {
                return redirect()->to('/competidores');
            }

        } catch (QueryException $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    private function handlePhotoUpload()
    {
        if ($this->foto) {
            $nombre_imagen = Str::uuid() . '.' . $this->foto->getClientOriginalExtension();
            $ruta_storage = 'Img/users/' . $nombre_imagen;
            Storage::disk('public')->put($ruta_storage, file_get_contents($this->foto->getRealPath()));
            return asset(Storage::url($ruta_storage));
        }

        return null;
    }

    public function cargarEscuelas()
    {
        $this->escuelas = Escuela::all();
    }

    public function cargarMaestros()
    {
        $escuela = Escuela::with(['maestros' => function ($query) {
            $query->whereNull('deleted_at');
        }])->find($this->escuelaSeleccionada);
    
        if ($escuela) {
            $this->maestros = $escuela->maestros; 
        } else {
            $this->maestros = collect([]); 
        }

        /* dd($this->escuelaSeleccionada, $this->escuelaOriginal); */

        if ($this->competidor_id && empty($this->maestroSeleccionado) || $this->escuelaOriginal != $this->escuelaSeleccionada) {
            $this->seleccionarMaestro();
        }
    }

    public function seleccionarMaestro(){
        $this->maestroSeleccionado = ''; 
    }

    public function render()
    {
        return view('livewire.competidor-edit');
    }
}
