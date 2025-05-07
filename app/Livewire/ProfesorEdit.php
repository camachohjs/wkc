<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\Maestro;
use App\Models\Escuela;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use ZxcvbnPhp\Zxcvbn;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Spatie\Permission\Models\Role;

class ProfesorEdit extends Component
{
    use WithFileUploads;

    public $nombre, $apellidos, $profesor_id, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $search, $password = '', $nacionalidad;
    public $userType = 'maestros';
    public $selectedButton = '';
    public $passwordStrength = 0;
    public $password_confirmation = '';
    public $showPassword = false;
    public $isEditing = false;
    public $perPage = 25;
    public $escuelaSeleccionada;
    public $maestroSeleccionado;
    public $escuelas = [];
    public $maestros = [];
    public $profesoresEncontrados = [];
    public $foto_actual;
    public $nacionalidades = [];

    #[Title('Profesores')]
    #[Layout('components.layouts.layout')]

    public function resetInputFields()
    {
        $this->profesor_id = null;
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

    public function mount()
    {
        $this->nacionalidades = $this->cargarNacionalidades();
        $this->profesor_id = request()->route('id');

        if ($this->profesor_id) {
            $profesor = Maestro::with('escuelas')->findOrFail($this->profesor_id);
            /* dd($profesor->escuelas->first()->id); */

        $this->nombre = $profesor->nombre;
        $this->apellidos = $profesor->apellidos;
        $this->email = $profesor->email;
        $this->fec = $profesor->fec;
        $this->cinta = $profesor->cinta;
        $this->telefono = $profesor->telefono;
        $this->peso = $profesor->peso;
        $this->estatura = number_format($profesor->estatura, 2);
        $this->genero = $profesor->genero;
        $this->foto_actual = $profesor->foto;
        $this->escuelaSeleccionada = $profesor->escuelas->first()->id ?? null;
        $this->nacionalidad = $profesor->nacionalidad;
        }

        $this->cargarEscuelas();
    }

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function store()
    {
        try {
            $this->validate([
                'nombre' => 'required|string',
                'apellidos' => 'required|string',
                'email' => 'required|email',
                'fec' => 'required|date',
                'cinta' => 'required|string',
                'telefono' => 'nullable|string',
                'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                
                'genero' => 'required|string',
                'foto' => 'mimes:jpeg,png,jpg|max:5120|nullable',
                'nacionalidad' => 'required|',
            ]);
            

            $urlImagen = $this->handlePhotoUpload();

            if ($this->profesor_id) {
                $maestroExistente = Maestro::find($this->profesor_id);
                if ($maestroExistente) {
                    $user = User::find($maestroExistente->user_id);
                    if ($user && $user->email != $this->email) {
                        $user->update(['email' => $this->email]);
                    }
                }
            }
    
            if (!isset($user)) {
                $user = User::create([
                    'name' => $this->nombre,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);
            }

            $maestro = Maestro::updateOrCreate(['id' => $this->profesor_id], [
                'user_id' => $user->id,
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'email' => $this->email,
                'fec' => $this->fec,
                'cinta' => $this->cinta,
                'telefono' => $this->telefono,
                'peso' => $this->peso,
                'estatura' => 1,
                'genero' => $this->genero,
                'foto' => $urlImagen ?: $this->foto_actual,
                'nacionalidad' => $this->nacionalidad,
            ]);

            /* dd($this->maestroSeleccionado); */

            $maestro->escuelas()->sync([$this->escuelaSeleccionada]);

            if (!$user->hasAnyRole(Role::all())) {
                $user->assignRole('supervisor');
            }

            flash()->options([
                'position' => 'top-center',
            ])->addSuccess($this->profesor_id ? 'profesor actualizado correctamente.' : 'profesor creado correctamente.');

            return redirect()->to('/profesores');

        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                flash()->options([
                'position' => 'top-center',
            ])->addError('El correo electrónico ya está en uso. Por favor, use un correo electrónico diferente.');
            } else {
                throw $e;
            }
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

    public function render()
    {
        return view('livewire.profesor-edit');
    }
}
