<?php
namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Escuela;
use App\Models\Maestro;
use Livewire\Attributes\Title;
use ZxcvbnPhp\Zxcvbn;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class Register extends Component
{
    public $nombre, $apellidos, $email, $fec, $cinta, $telefono, $peso, $estatura, $genero, $foto, $nacionalidad;
    public $password = '';
    public $userType = '';
    public $selectedButton = '';
    public $passwordStrength = 0;
    public $password_confirmation = '';
    public $showPassword = false;
    use WithFileUploads;
    public $escuelaSeleccionada;
    public $maestroSeleccionado;
    public $escuelas = [];
    public $maestros = [];
    public $nacionalidades = [];

    protected $rules = [
        'password' => 'required|min:8',
        'password_confirmation' => 'required|same:password',
    ];

    /* public function updated($propertyName, $value)
    {
        if ($propertyName === 'escuelaSeleccionada' && $value === 'nueva-escuela') {
            session()->put('datosFormularioTemporal', [
                'userType' => $this->userType,
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'email' => $this->email,
                'fec' => $this->fec,
                'cinta' => $this->cinta,
                'telefono' => $this->telefono,
                'peso' => $this->peso,
                'estatura' => $this->estatura,
                'genero' => $this->genero,
            ]);
            $this->reset('escuelaSeleccionada');
            return redirect()->to('/agregar-escuela');
        } elseif ($propertyName === 'maestroSeleccionado' && $value === 'nuevo-maestro') {
            $this->reset('maestroSeleccionado');
        }
    } */

    public function updatedPassword($value)
    {
        // Verifica que haya al menos 2 números
        /* $DosNumeros = preg_match_all('/\d/', $value) >= 2; */

        // Verifica que haya al menos 2 letras
        /* $DosLetras = preg_match_all('/[a-zA-Z]/', $value) >= 2; */

        // Verifica que haya al menos 1 caracter especial
        /* $CaracterEspecial = preg_match('/[!@#$%^&*()]/', $value); */

        /* $lengthStrength = ($DosNumeros ? 1 : 0) + ($DosLetras ? 1 : 0) + ($CaracterEspecial ? 1 : 0); */
        $lengthStrength = 6;
        $zxcvbn = new Zxcvbn();
        $result = $zxcvbn->passwordStrength($value);
        $this->passwordStrength = max(min($result['score'] + 2 * $lengthStrength, 10), 0);

        $this->dispatch('passwordStrengthUpdated', ['passwordStrength' => $this->passwordStrength]);
    }

    public function toggleShowPassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function selectUserType($type)
    {
        $this->userType = $type;
        $this->selectedButton = $type;
    }

    public function register()
    {
        $dynamicRules = [
            'userType' => 'required',
            'nombre' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'fec' => 'required|date',
            'cinta' => 'required|string',
            'telefono' => 'nullable|string',
            'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            
            'password' => 'required|min:4',
            'password_confirmation' => 'required|same:password',
            'foto' => 'mimes:jpeg,png,jpg|max:5120|nullable',
            'nacionalidad' => 'required',
        ];
    
        if ($this->userType == 'alumno') {
            $dynamicRules['escuelaSeleccionada'] = 'required';
            $dynamicRules['maestroSeleccionado'] = 'required_if:userType,alumno';
        }

        if ($this->userType == 'maestro') {
            $dynamicRules['escuelaSeleccionada'] = 'required';
        }

        $this->validate($dynamicRules);

        $nombre_imagen = '';

        if ($this->foto) {
            $nombre_imagen = $this->foto ? Str::uuid() . '.' . $this->foto->getClientOriginalExtension() : '';
            $ruta_storage = 'Img/users/' . $nombre_imagen;
            Storage::disk('public')->put($ruta_storage, file_get_contents($this->foto->getRealPath()));
            $urlImagen = asset(Storage::url($ruta_storage));
        } else {
            $urlImagen = null;
        }

        // Crear usuario (ya sea alumno o maestro)
        $user = User::create($this->getFormData());

        if ($this->userType == 'alumno') {
            $alumno = Alumno::where('nombre', $this->nombre)
                                ->where('apellidos', $this->apellidos)
                                ->where('fec', $this->fec)
                                ->whereNull('user_id')
                                ->first();

            if ($alumno) {
                $alumno->update([
                    'user_id' => $user->id,
                    'email' => $this->email,
                    'fec' => $this->fec,
                    'cinta' => $this->cinta,
                    'telefono' => $this->telefono,
                    'peso' => $this->peso,
                    'estatura' => 1,
                    'genero' => $this->genero,
                    'foto' => $urlImagen,
                    'escuela_id' => $this->escuelaSeleccionada,
                    'nacionalidad' => $this->nacionalidad,
                ]);
            } else {
            $alumnoData = ([
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
                'escuela_id' => $this->escuelaSeleccionada,
                'maestro_id' => $this->maestroSeleccionado,
                'nacionalidad' => $this->nacionalidad,
            ]);

            $alumno = $user->alumno()->create($alumnoData);
            /* dd($this->maestroSeleccionado); */
            $alumno->escuelas()->attach($this->escuelaSeleccionada, ['maestro_id' => $this->maestroSeleccionado]);
            }

        } elseif ($this->userType == 'maestro') {
            $maestro = Maestro::where('nombre', $this->nombre)
                                ->where('apellidos', $this->apellidos)
                                ->whereNull('user_id')
                                ->whereNull('email')
                                ->first();

            if ($maestro) {
                $maestro->update([
                    'user_id' => $user->id,
                    'email' => $this->email,
                    'fec' => $this->fec,
                    'cinta' => $this->cinta,
                    'telefono' => $this->telefono,
                    'peso' => $this->peso,
                    'estatura' => 1,
                    'genero' => $this->genero,
                    'foto' => $urlImagen,
                    'nacionalidad' => $this->nacionalidad,
                ]);
            } else {
                $maestroData = ([
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
                    'nacionalidad' => $this->nacionalidad,
                ]);

                // Crear el maestro
                $maestro = $user->maestro()->create($maestroData);
                
                $maestro->escuelas()->sync([$this->escuelaSeleccionada]);
            }
            /* dd($this->escuelaSeleccionada, $user); */
            
            $role = Role::findByName('supervisor');
            $user->assignRole($role);

        }
        flash()->options([
                'position' => 'top-center',
            ])->addSuccess('','Registro exitoso.');

        $this->reset();
    }

    private function getFormData()
    {
        return [
            'name' => $this->nombre,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];
    }

    #[Title('Registro')]

    public function mount()
    {
        $this->cargarEscuelas();
        $this->cargarMaestros();
        $this->nacionalidades = $this->cargarNacionalidades();
        /* $datosFormularioTemporal = session()->get('datosFormularioTemporal', []);
        if (!empty($datosFormularioTemporal)) {
            $this->userType = $datosFormularioTemporal['userType'];
            $this->selectedButton = $this->userType;
            $this->nombre = $datosFormularioTemporal['nombre'];
            $this->apellidos = $datosFormularioTemporal['apellidos'];
            $this->email = $datosFormularioTemporal['email'];
            $this->fec = $datosFormularioTemporal['fec'];
            $this->cinta = $datosFormularioTemporal['cinta'];
            $this->telefono = $datosFormularioTemporal['telefono'];
            $this->peso = $datosFormularioTemporal['peso'];
            $this->estatura = $datosFormularioTemporal['estatura'];
            $this->genero = $datosFormularioTemporal['genero'];

            session()->forget('datosFormularioTemporal');
        } */
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

    public function render()
    {
        return view('livewire.register');
    }
}
