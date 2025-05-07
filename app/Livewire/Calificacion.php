<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Categoria;
use App\Models\Forma;
use App\Models\Fusion;
use App\Models\Maestro;
use Livewire\Component;
use App\Models\RegistroTorneo;
use App\Models\Torneo;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Calificacion extends Component
{
    public $registroId;
    public $registro;
    public $torneo_id, $registro_id, $alumno_id, $categoria_id;
    public $step = 1;
    public $nombre, $apellidos, $email, $cinta, $peso, $estatura, $genero, $fec, $telefono, $usuario, $torneo, $torneo_datos, $tipoUsuario, $edad;
    public $categoriasFiltradas, $categoriasFil;
    public $selectedCategories = [];
    public $mayor_de_edad = false;
    public $cinta_negra = false;
    public $categoriasInscritas;
    public $categoriasValidas;
    public $categoriasInscritasIds;
    public $categoriasInvalidasGlobal = [];
    #[Title('Check-In peso')]
    #[Layout('components.layouts.layout')]

    public function mount($id)
    {
        $this->registroId = $id;
        $this->registro = RegistroTorneo::with(['alumno', 'maestro', 'categoria', 'torneo'])->find($this->registroId);
        /* dd($this->registro); */

        if ($this->registro->alumno_id) {
            $tipoUsuario = 'alumno';
            $this->email = $this->registro->alumno->email;
            $this->fec = $this->registro->alumno->fec;
            $fechaNacimiento = new DateTime($this->registro->alumno->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $edad = $edad-1;
            }
            $user_id = $this->registro->alumno_id;
        }else{
            $tipoUsuario = 'maestro';
            $this->email = $this->registro->maestro->email;
            $this->fec = $this->registro->maestro->fec;
            $fechaNacimiento = new DateTime($this->registro->maestro->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $edad = $edad-1;
            }
            $user_id = $this->registro->maestro_id;
        }

        if ($this->registro) {
            $this->nombre = $this->registro->nombre;
            $this->apellidos = $this->registro->apellidos;
            $this->cinta = $this->registro->cinta;
            $this->peso = $this->registro->peso;
            $this->estatura = number_format($this->registro->estatura, 2);
            $this->genero = $this->registro->genero;
            $this->telefono = $this->registro->telefono;
            $this->torneo_id = $this->registro->torneo_id;
            $this->tipoUsuario = $tipoUsuario;
            $this->categoria_id = $this->registro->categoria_id;
            $this->mayor_de_edad = $this->registro->mayor_de_edad;
            $this->cinta_negra = $this->registro->cinta_negra; 
            $this->categoriasInscritas = RegistroTorneo::where('torneo_id', $this->registro->torneo_id)
                                                        ->where(function ($query) use ($user_id) {
                                                            $query->where('alumno_id', $user_id)
                                                                ->orWhere('maestro_id', $user_id);
                                                        })
                                                        ->whereNull('deleted_at')
                                                        ->whereHas('categoria', function ($query) {
                                                            // Filtrar por forma_id en la relación con 'categoria'
                                                            $query->whereIn('forma_id', [12, 13, 14]);
                                                        })
                                                        ->with('categoria')
                                                        ->get();
            /* $this->categoriasInscritasIds = $this->categoriasInscritas->pluck('categoria_id')->toArray(); */
            $this->categoriasInscritasIds = $this->categoriasInscritas->pluck('categoria.division')->toArray();

        } else {
            flash()->options([
                'position' => 'top-center',
            ])->addError('Registro no encontrado.');
        }
    }

    public function verificarPeso()
    {
        // Si el peso es el mismo que ya está registrado
        if ($this->peso == $this->registro->peso) {
            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('','El peso es válido y no hay cambios en las categorías.');
            return redirect()->route('inscritos', ['id' => $this->torneo_id]);
        }
    }

    public function updatedPeso($peso)
    {
        $this->fec = $this->registro->fec;
    
        $fechaNacimiento = new DateTime($this->registro->fec);
        $añoActual = Carbon::now();
        $añoSiguiente = Carbon::now()->addYear();

        // Calcula la edad basada en el año
        $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

        // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
        if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
            $edad = $edad-1;
        }

        if ($this->registro->alumno_id) {
            $user_id = $this->registro->alumno_id;
        } else {
            $user_id = $this->registro->maestro_id;
        }

        $this->categoriasValidas = $this->loadCategoriasValidas($edad, $peso, $this->cinta, $this->genero);
        /* dd($this->categoriasValidas); */
        $pesoValido = true;
        $categoriasInvalidas = [];

        // Verificar si el competidor aún está en categorías válidas según su nuevo peso
        foreach ($this->categoriasInscritas as $registro) {
            // Verificar si la categoría o fusión inscrita es válida
            $categoriaValida = $this->categoriasValidas->filter(function($categoriaValida) use ($registro, $peso) {
                return $categoriaValida['id'] === $registro->categoria_id && 
                        ($categoriaValida['peso_minimo'] === null || $categoriaValida['peso_minimo'] <= $peso) &&
                        ($categoriaValida['peso_maximo'] === null || $categoriaValida['peso_maximo'] >= $peso);
            })->isNotEmpty();  // Verifica si hay al menos una categoría válida
        
            if (!$categoriaValida) {
                $pesoValido = false;
                $categoriasInvalidas[] = $registro->categoria->division;
            }
        }

        $this->categoriasInvalidasGlobal = $categoriasInvalidas;

        if ($pesoValido) {
            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('', 'El peso es válido y no hay cambios en las categorías.');
            return redirect()->route('inscritos', ['id' => $this->torneo_id]);
        } elseif (count($categoriasInvalidas) == 1) {
            $categoriaIdsInvalidas = Categoria::whereIn('division', $categoriasInvalidas)->pluck('id');
            RegistroTorneo::where('torneo_id', $this->registro->torneo_id)
                            ->where(function ($query) use ($user_id) {
                                $query->where('alumno_id', $user_id)
                                    ->orWhere('maestro_id', $user_id);
                            })
                            ->whereIn('categoria_id', $categoriaIdsInvalidas)
                            ->delete();

            flash()->options([
                'position' => 'top-center',
            ])->addError('', "El competidor debe cambiar de categoría: {$categoriasInvalidas[0]}.");
            $this->loadTorneoData($edad, $peso, $this->cinta, $this->genero);
        } else {
            $categoriaIdsInvalidas = Categoria::whereIn('division', $categoriasInvalidas)->pluck('id');
            RegistroTorneo::where('torneo_id', $this->registro->torneo_id)
                            ->where(function ($query) use ($user_id) {
                                $query->where('alumno_id', $user_id)
                                    ->orWhere('maestro_id', $user_id);
                            })
                            ->whereIn('categoria_id', $categoriaIdsInvalidas)
                            ->delete();

            $categoriasTexto = implode(', ', $categoriasInvalidas);
            flash()->options([
                'position' => 'top-center',
            ])->addError('', "El competidor debe cambiar de las categorías: {$categoriasTexto}.");
            $this->loadTorneoData($edad, $peso, $this->cinta, $this->genero);
        }
    }
    
    public function updatedCinta($value)
    {
        $this->fec = $this->registro->fec;
    
            $fechaNacimiento = new DateTime($this->registro->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $edad = $edad-1;
            }
        $this->loadTorneoData($edad, $this->peso, $value, $this->genero);
    }

    public function loadCategoriasValidas($edad, $peso, $cinta, $genero)
    {
        if (!$this->torneo_id) {
            return;
        }
    
        $this->torneo = Torneo::findOrFail($this->torneo_id);

            // Inicializar la colección filtrada como vacía
            $this->categoriasFil= collect([]);
    
        $categorias = Categoria::whereHas('torneos', function ($query) {
            $query->where('torneo_id', $this->torneo_id);
        })
        ->whereIn('forma_id', [12, 13, 14])
        ->where('edad_minima', '<=', $edad)
        ->where('edad_maxima', '>=', $edad)
        ->where(function ($query) use ($peso) {
            $query->where(function ($q) use ($peso) {
                $q->whereNull('peso_minimo')
                  ->orWhere('peso_minimo', '<=', $peso);
            })
            ->where(function ($q) use ($peso) {
                $q->whereNull('peso_maximo')
                  ->orWhere('peso_maximo', '>=', $peso);
            });
        })
        ->where(function ($query) {
            $query->whereHas('cintas', function ($q) {
                $q->where('cinta', $this->cinta)
                    ->orWhere('cinta', 'P/I/A');
            })
            ->orWhere('cinta', $this->cinta)
            ->orWhere(function ($q) {
                $q->where('cinta', 'P/I/A')
                    ->whereIn(DB::raw("'" . $this->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
            })
            /* ->orWhereNull('cinta') */;
        })
        ->where(function ($query) use ($genero) {
            $query->where('genero', $genero)
                  ->orWhere('genero', 'mixto');
        })
        ->get();
    
            // Añadir categorías a la colección filtrada
            foreach ($categorias as $categoria) {
                $categoriaTorneo = $categoria->torneos->first();
                $forma = Forma::findOrFail($categoria->forma_id);
                $this->categoriasFil->push([
                    'tipo' => 'categoria',
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'edad_minima' => $categoria->edad_minima,
                    'edad_maxima' => $categoria->edad_maxima,
                    'genero' => $categoria->genero,
                    'division' => $categoria->division,
                    'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                    'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                    'forma_nombre' => $forma->nombre,
                    'forma_id' => $forma->id,
                    'peso_maximo' => $categoria->peso_maximo,
                    'peso_minimo' => $categoria->peso_minimo,
                ]);
            }

            // Segunda búsqueda para categorías de cintas negras y mayores de edad si aplica
        if ($this->cinta_negra && $this->mayor_de_edad) {
            $categoriasCintaNegra = Categoria::whereHas('torneos', function ($query) {
                $query->where('torneo_id', $this->torneo_id);
            })
            ->with(['torneos' => function ($query) {
                $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
            }])
            ->whereIn('forma_id', [12, 13, 14])
            ->where('edad_minima', '<=', 18)
            ->where('edad_maxima', '>=', 18)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $$this->registro->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $$this->registro->peso);
                });
            })
            ->where(function ($query) {
                $query->whereHas('cintas', function ($q) {
                    $q->where('cinta', 'negra');
                })
                ->orWhere('cinta', 'negra')
                /* ->orWhereNull('cinta') */;
            })
            ->where(function ($query) {
                $query->where('genero', $this->registro->genero)
                        ->orWhere('genero', 'mixto');
            })
            ->get();

            foreach ($categoriasCintaNegra as $categoria) {
                $categoriaTorneo = $categoria->torneos->first();
                $forma = Forma::findOrFail($categoria->forma_id);
                $this->categoriasFil->push([
                    'tipo' => 'categoria',
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'edad_minima' => $categoria->edad_minima,
                    'edad_maxima' => $categoria->edad_maxima,
                    'genero' => $categoria->genero,
                    'division' => $categoria->division,
                    'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                    'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                    'forma_nombre' => $forma->nombre,
                    'forma_id' => $forma->id,
                    'peso_maximo' => $categoria->peso_maximo,
                    'peso_minimo' => $categoria->peso_minimo,
                ]);
            }
        }

        // Tercera búsqueda para categorías de mayores de edad si aplica
        if ($this->mayor_de_edad && !$this->cinta_negra) {
            $categoriasMayorEdad = Categoria::whereHas('torneos', function ($query) {
                $query->where('torneo_id', $this->torneo_id);
            })
            ->with(['torneos' => function ($query) {
                $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
            }])
            ->whereIn('forma_id', [12, 13, 14])
            ->where('edad_minima', '<=', 18)
            ->where('edad_maxima', '>=', 18)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $this->registro->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $this->registro->peso);
                });
            })
            ->where(function ($query) {
                $query->whereHas('cintas', function ($q) {
                    $q->where('cinta', $this->cinta)
                        ->orWhere('cinta', 'P/I/A');
                })
                ->orWhere('cinta', $this->cinta)
                ->orWhere(function ($q) {
                    $q->where('cinta', 'P/I/A')
                        ->whereIn(DB::raw("'" . $this->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
                })
                /* ->orWhereNull('cinta') */;
            })
            ->where(function ($query) {
                $query->where('genero', $this->registro->genero)
                        ->orWhere('genero', 'mixto');
            })
            ->get();

            foreach ($categoriasMayorEdad as $categoria) {
                $categoriaTorneo = $categoria->torneos->first();
                $forma = Forma::findOrFail($categoria->forma_id);
                $this->categoriasFil->push([
                    'tipo' => 'categoria',
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'edad_minima' => $categoria->edad_minima,
                    'edad_maxima' => $categoria->edad_maxima,
                    'genero' => $categoria->genero,
                    'division' => $categoria->division,
                    'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                    'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                    'forma_nombre' => $forma->nombre,
                    'forma_id' => $forma->id,
                    'peso_maximo' => $categoria->peso_maximo,
                    'peso_minimo' => $categoria->peso_minimo,
                ]);
            }
        }

        // Cuarta búsqueda para cinta negra si aplica
        if (!$this->mayor_de_edad && $this->cinta_negra) {
            $categoriasNegra = Categoria::whereHas('torneos', function ($query) {
                $query->where('torneo_id', $this->torneo_id);
            })
            ->with(['torneos' => function ($query) {
                $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
            }])
            ->whereIn('forma_id', [12, 13, 14])
            ->where('edad_minima', '<=', $this->registro->edad)
            ->where('edad_maxima', '>=', $this->registro->edad)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $this->registro->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $this->registro->peso);
                });
            })
            ->where(function ($query) {
                $query->whereHas('cintas', function ($q) {
                    $q->where('cinta', 'negra');
                })
                ->orWhere('cinta', 'negra')
                /* ->orWhereNull('cinta') */;
            })
            ->where(function ($query) {
                $query->where('genero', $this->registro->genero)
                        ->orWhere('genero', 'mixto');
            })
            ->get();

            foreach ($categoriasNegra as $categoria) {
                $categoriaTorneo = $categoria->torneos->first();
                $forma = Forma::findOrFail($categoria->forma_id);
                $this->categoriasFil->push([
                    'tipo' => 'categoria',
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                    'edad_minima' => $categoria->edad_minima,
                    'edad_maxima' => $categoria->edad_maxima,
                    'genero' => $categoria->genero,
                    'division' => $categoria->division,
                    'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                    'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                    'forma_nombre' => $forma->nombre,
                    'forma_id' => $forma->id,
                    'peso_maximo' => $categoria->peso_maximo,
                    'peso_minimo' => $categoria->peso_minimo,
                ]);
            }
        }
        return $this->categoriasFil;
    }

    public function loadTorneoData($edad, $peso, $cinta, $genero)
    {
        if (!$this->torneo_id) {
            return;
        }
    
        $this->torneo = Torneo::findOrFail($this->torneo_id);

            // Inicializar la colección filtrada como vacía
            $this->categoriasFiltradas = collect([]);

            $esfusion = Fusion::where('torneo_id', $this->torneo_id)
            ->where('edad_minima', '<=', $edad)
            ->where('edad_maxima', '>=', $edad)
            ->where(function ($query) use ($peso) {
                $query->where(function ($q) use ($peso) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $peso);
                })
                ->where(function ($q) use ($peso) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $peso);
                });
            })
            ->where(function ($query) {
                foreach ($this->categoriasInvalidasGlobal as $division) {
                    $query->orWhere('division', 'LIKE', '%' . $division . '%');
                }
            })
            ->where(function ($query) use ($genero) {
                $query->where('genero', $genero)
                        ->orWhere('genero', 'mixto');
            })
            ->orderByRaw("FIELD(division, '".implode("', '", $this->categoriasInvalidasGlobal)."')") // Ordenar por las divisiones más cercanas
            ->first(); 

            if(!$esfusion){ 

                $categorias = Categoria::whereHas('torneos', function ($query) {
                    $query->where('torneo_id', $this->torneo_id);
                    })
                    ->whereIn('forma_id', [12, 13, 14])
                    ->where('edad_minima', '<=', $edad)
                    ->where('edad_maxima', '>=', $edad)
                    ->where(function ($query) use ($peso) {
                        $query->where(function ($q) use ($peso) {
                            $q->whereNull('peso_minimo')
                            ->orWhere('peso_minimo', '<=', $peso);
                        })
                        ->where(function ($q) use ($peso) {
                            $q->whereNull('peso_maximo')
                            ->orWhere('peso_maximo', '>=', $peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->whereHas('cintas', function ($q) {
                            $q->where('cinta', $this->cinta)
                                ->orWhere('cinta', 'P/I/A');
                        })
                        ->orWhere('cinta', $this->cinta)
                        ->orWhere(function ($q) {
                            $q->where('cinta', 'P/I/A')
                                ->whereIn(DB::raw("'" . $this->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
                        })
                        /* ->orWhereNull('cinta') */;
                    })
                    ->where(function ($query) use ($genero) {
                        $query->where('genero', $genero)
                            ->orWhere('genero', 'mixto');
                    })
                ->get();
            
                    // Añadir categorías a la colección filtrada
                    foreach ($categorias as $categoria) {
                        $categoriaTorneo = $categoria->torneos->first();
                        $forma = Forma::findOrFail($categoria->forma_id);
                        $this->categoriasFiltradas->push([
                            'tipo' => 'categoria',
                            'id' => $categoria->id,
                            'nombre' => $categoria->nombre,
                            'edad_minima' => $categoria->edad_minima,
                            'edad_maxima' => $categoria->edad_maxima,
                            'genero' => $categoria->genero,
                            'division' => $categoria->division,
                            'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                            'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                            'forma_nombre' => $forma->nombre,
                            'forma_id' => $forma->id,
                            'peso_maximo' => $categoria->peso_maximo,
                            'peso_minimo' => $categoria->peso_minimo,
                        ]);
                    }
        
                    // Segunda búsqueda para categorías de cintas negras y mayores de edad si aplica
                if ($this->cinta_negra && $this->mayor_de_edad) {
                    $categoriasCintaNegra = Categoria::whereHas('torneos', function ($query) {
                        $query->where('torneo_id', $this->torneo_id);
                    })
                    ->with(['torneos' => function ($query) {
                        $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
                    }])
                    ->whereIn('forma_id', [12, 13, 14])
                    ->where('edad_minima', '<=', 18)
                    ->where('edad_maxima', '>=', 18)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('peso_minimo')
                                ->orWhere('peso_minimo', '<=', $$this->registro->peso);
                        })
                        ->where(function ($q) {
                            $q->whereNull('peso_maximo')
                                ->orWhere('peso_maximo', '>=', $$this->registro->peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->whereHas('cintas', function ($q) {
                            $q->where('cinta', 'negra');
                        })
                        ->orWhere('cinta', 'negra')
                        /* ->orWhereNull('cinta') */;
                    })
                    ->where(function ($query) {
                        $query->where('genero', $this->registro->genero)
                                ->orWhere('genero', 'mixto');
                    })
                    ->get();
        
                    foreach ($categoriasCintaNegra as $categoria) {
                        $categoriaTorneo = $categoria->torneos->first();
                        $forma = Forma::findOrFail($categoria->forma_id);
                        $this->categoriasFiltradas->push([
                            'tipo' => 'categoria',
                            'id' => $categoria->id,
                            'nombre' => $categoria->nombre,
                            'edad_minima' => $categoria->edad_minima,
                            'edad_maxima' => $categoria->edad_maxima,
                            'genero' => $categoria->genero,
                            'division' => $categoria->division,
                            'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                            'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                            'forma_nombre' => $forma->nombre,
                            'forma_id' => $forma->id,
                            'peso_maximo' => $categoria->peso_maximo,
                            'peso_minimo' => $categoria->peso_minimo,
                        ]);
                    }
                }
        
                // Tercera búsqueda para categorías de mayores de edad si aplica
                if ($this->mayor_de_edad && !$this->cinta_negra) {
                    $categoriasMayorEdad = Categoria::whereHas('torneos', function ($query) {
                        $query->where('torneo_id', $this->torneo_id);
                    })
                    ->with(['torneos' => function ($query) {
                        $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
                    }])
                    ->whereIn('forma_id', [12, 13, 14])
                    ->where('edad_minima', '<=', 18)
                    ->where('edad_maxima', '>=', 18)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('peso_minimo')
                                ->orWhere('peso_minimo', '<=', $this->registro->peso);
                        })
                        ->where(function ($q) {
                            $q->whereNull('peso_maximo')
                                ->orWhere('peso_maximo', '>=', $this->registro->peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->whereHas('cintas', function ($q) {
                            $q->where('cinta', $this->cinta)
                                ->orWhere('cinta', 'P/I/A');
                        })
                        ->orWhere('cinta', $this->cinta)
                        ->orWhere(function ($q) {
                            $q->where('cinta', 'P/I/A')
                                ->whereIn(DB::raw("'" . $this->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
                        })
                        /* ->orWhereNull('cinta') */;
                    })
                    ->where(function ($query) {
                        $query->where('genero', $this->registro->genero)
                                ->orWhere('genero', 'mixto');
                    })
                    ->get();
        
                    foreach ($categoriasMayorEdad as $categoria) {
                        $categoriaTorneo = $categoria->torneos->first();
                        $forma = Forma::findOrFail($categoria->forma_id);
                        $this->categoriasFiltradas->push([
                            'tipo' => 'categoria',
                            'id' => $categoria->id,
                            'nombre' => $categoria->nombre,
                            'edad_minima' => $categoria->edad_minima,
                            'edad_maxima' => $categoria->edad_maxima,
                            'genero' => $categoria->genero,
                            'division' => $categoria->division,
                            'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                            'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                            'forma_nombre' => $forma->nombre,
                            'forma_id' => $forma->id,
                            'peso_maximo' => $categoria->peso_maximo,
                            'peso_minimo' => $categoria->peso_minimo,
                        ]);
                    }
                }
        
                // Cuarta búsqueda para cinta negra si aplica
                if (!$this->mayor_de_edad && $this->cinta_negra) {
                    $categoriasNegra = Categoria::whereHas('torneos', function ($query) {
                        $query->where('torneo_id', $this->torneo_id);
                    })
                    ->with(['torneos' => function ($query) {
                        $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
                    }])
                    ->whereIn('forma_id', [12, 13, 14])
                    ->where('edad_minima', '<=', $this->registro->edad)
                    ->where('edad_maxima', '>=', $this->registro->edad)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('peso_minimo')
                                ->orWhere('peso_minimo', '<=', $this->registro->peso);
                        })
                        ->where(function ($q) {
                            $q->whereNull('peso_maximo')
                                ->orWhere('peso_maximo', '>=', $this->registro->peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->whereHas('cintas', function ($q) {
                            $q->where('cinta', 'negra');
                        })
                        ->orWhere('cinta', 'negra')
                        /* ->orWhereNull('cinta') */;
                    })
                    ->where(function ($query) {
                        $query->where('genero', $this->registro->genero)
                                ->orWhere('genero', 'mixto');
                    })
                    ->get();
        
                    foreach ($categoriasNegra as $categoria) {
                        $categoriaTorneo = $categoria->torneos->first();
                        $forma = Forma::findOrFail($categoria->forma_id);
                        $this->categoriasFiltradas->push([
                            'tipo' => 'categoria',
                            'id' => $categoria->id,
                            'nombre' => $categoria->nombre,
                            'edad_minima' => $categoria->edad_minima,
                            'edad_maxima' => $categoria->edad_maxima,
                            'genero' => $categoria->genero,
                            'division' => $categoria->division,
                            'area' => $categoriaTorneo ? ucfirst($categoriaTorneo->pivot->area) : null,
                            'horario' => $categoriaTorneo ? Carbon::parse($categoriaTorneo->pivot->horario)->format('H:i \h\r\s / d-m-Y') : null,
                            'forma_nombre' => $forma->nombre,
                            'forma_id' => $forma->id,
                            'peso_maximo' => $categoria->peso_maximo,
                            'peso_minimo' => $categoria->peso_minimo,
                        ]);
                    }
                }

            } else { 
                // Filtrar fusiones
                    $fusiones = Fusion::where('torneo_id', $this->torneo_id)
                    ->where('edad_minima', '<=', $edad)
                    ->where('edad_maxima', '>=', $edad)
                    ->where(function ($query) use ($peso) {
                        $query->where(function ($q) use ($peso) {
                            $q->whereNull('peso_minimo')
                            ->orWhere('peso_minimo', '<=', $peso);
                        })
                        ->where(function ($q) use ($peso) {
                            $q->whereNull('peso_maximo')
                            ->orWhere('peso_maximo', '>=', $peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->where('cinta', $this->cinta)
                                ->orWhere('cinta', 'LIKE', '%' . $this->cinta . '%')
                                ->orWhere(function ($q) {
                                    $q->where('cinta', 'P/I/A')
                                        ->whereIn(DB::raw("'" . $this->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
                                })
                                /* ->orWhereNull('cinta') */;
                    })
                    ->where(function ($query) {
                        foreach ($this->categoriasInvalidasGlobal as $division) {
                            $query->Where('division', 'LIKE', '%' . $division . '%');
                        }
                    })
                    ->where(function ($query) use ($genero) {
                        $query->where('genero', $genero)
                            ->orWhere('genero', 'mixto');
                    })
                ->get();

                foreach ($fusiones as $fusion) {
                    // Aquí buscas dentro de la fusión la categoría más adecuada basada en el perfil del usuario
                    $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion, $edad, $peso, $cinta, $genero);
                    if ($categoriaAdecuada) {
                        $formaId = $categoriaAdecuada->forma_id;
                        if ($formaId == 12 || $formaId == 13 || $formaId ==  14){
                            $formaNombre = $categoriaAdecuada->forma->nombre; 
                            
                            // Solo añades la fusión si encontraste una categoría adecuada
                            if ($categoriaAdecuada) {
                                $this->categoriasFiltradas->push([
                                    'tipo' => 'fusion',
                                    'id' => $categoriaAdecuada->id,
                                    'nombre' => $fusion->nombre,
                                    'edad_minima' => $fusion->edad_minima,
                                    'edad_maxima' => $fusion->edad_maxima,
                                    'genero' => $fusion->genero,
                                    'division' => $fusion->division,
                                    'peso_maximo' => $fusion->peso_maximo,
                                    'peso_minimo' => $fusion->peso_minimo,
                                    'forma_nombre' => $formaNombre,
                                    'forma_id' => $formaId,
                                ]);
                            }
                        }
                    } else {
                        
                    }
                }
                // Segunda búsqueda para categorías de cintas negras y mayores de edad si aplica
                if ($this->cinta_negra && $this->mayor_de_edad) {
                    $fusionesCintaNegraMayorEdad = fusion::where('torneo_id', $this->torneo_id)
                    ->where('edad_minima', '<=', 18)
                    ->where('edad_maxima', '>=', 18)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('peso_minimo')
                                ->orWhere('peso_minimo', '<=', $this->registro->peso);
                        })
                        ->where(function ($q) {
                            $q->whereNull('peso_maximo')
                                ->orWhere('peso_maximo', '>=', $this->registro->peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->where('cinta', 'negra')
                            ->orWhere('cinta', 'LIKE', '%' . 'negra' . '%');
                    })
                    ->where(function ($query) {
                        foreach ($this->categoriasInvalidasGlobal as $division) {
                            $query->Where('division', 'LIKE', '%' . $division . '%');
                        }
                    })
                    ->where(function ($query) {
                        $query->where('genero', $this->registro->genero)
                                ->orWhere('genero', 'mixto');
                    });
            
                    $fusionesCintaNegraMayorEdad = $fusionesCintaNegraMayorEdad->get();

                    foreach ($fusionesCintaNegraMayorEdad as $fusion) {
                        $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion, $edad, $peso, $cinta, $genero);
                        if ($categoriaAdecuada) {
                            $formaId = $categoriaAdecuada->forma_id;
                            if ($formaId == 12 || $formaId == 13 || $formaId ==  14){
                                $formaNombre = $categoriaAdecuada->forma->nombre; 
                                
                                // Solo añades la fusión si encontraste una categoría adecuada
                                if ($categoriaAdecuada) {
                                    $this->categoriasFiltradas->push([
                                        'tipo' => 'fusion',
                                        'id' => $categoriaAdecuada->id,
                                        'nombre' => $fusion->nombre,
                                        'edad_minima' => $fusion->edad_minima,
                                        'edad_maxima' => $fusion->edad_maxima,
                                        'genero' => $fusion->genero,
                                        'division' => $fusion->division,
                                        'peso_maximo' => $fusion->peso_maximo,
                                        'peso_minimo' => $fusion->peso_minimo,
                                        'forma_nombre' => $formaNombre,
                                        'forma_id' => $formaId,
                                    ]);
                                }
                            }
                        } else {
                            
                        }
                    }
                }
                // Tercera búsqueda para mayores de edad si aplica
                if (!$this->cinta_negra && $this->mayor_de_edad) {
                    $fusionesMayorEdad = fusion::where('torneo_id', $this->torneo_id)
                    ->where('edad_minima', '<=', 18)
                    ->where('edad_maxima', '>=', 18)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('peso_minimo')
                                ->orWhere('peso_minimo', '<=', $this->registro->peso);
                        })
                        ->where(function ($q) {
                            $q->whereNull('peso_maximo')
                                ->orWhere('peso_maximo', '>=', $this->registro->peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->where('cinta', $this->cinta)
                                ->orWhere('cinta', 'LIKE', '%' . $this->cinta . '%')
                                ->orWhere(function ($q) {
                                    $q->where('cinta', 'P/I/A')
                                        ->whereIn(DB::raw("'" . $this->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
                                })
                                /* ->orWhereNull('cinta') */;
                    })
                    ->where(function ($query) {
                        foreach ($this->categoriasInvalidasGlobal as $division) {
                            $query->Where('division', 'LIKE', '%' . $division . '%');
                        }
                    })
                    ->where(function ($query) {
                        $query->where('genero', $this->registro->genero)
                                ->orWhere('genero', 'mixto');
                    });
            
                    $fusionesMayorEdad = $fusionesMayorEdad->get();

                    foreach ($fusionesMayorEdad as $fusion) {
                        $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion, $edad, $peso, $cinta, $genero);
                        if ($categoriaAdecuada) {
                            $formaId = $categoriaAdecuada->forma_id;
                            if ($formaId == 12 || $formaId == 13 || $formaId ==  14){
                                $formaNombre = $categoriaAdecuada->forma->nombre; 
                                
                                // Solo añades la fusión si encontraste una categoría adecuada
                                if ($categoriaAdecuada) {
                                    $this->categoriasFiltradas->push([
                                        'tipo' => 'fusion',
                                        'id' => $categoriaAdecuada->id,
                                        'nombre' => $fusion->nombre,
                                        'edad_minima' => $fusion->edad_minima,
                                        'edad_maxima' => $fusion->edad_maxima,
                                        'genero' => $fusion->genero,
                                        'division' => $fusion->division,
                                        'peso_maximo' => $fusion->peso_maximo,
                                        'peso_minimo' => $fusion->peso_minimo,
                                        'forma_nombre' => $formaNombre,
                                        'forma_id' => $formaId,
                                    ]);
                                }
                            }
                        } else {
                            
                        }
                    }
                }
                // Cuarta búsqueda para cinta negra si aplica
                if (!$this->cinta_negra && $this->mayor_de_edad) {
                    $fusionesCintaNegra = fusion::where('torneo_id', $this->torneo_id)
                    ->where('edad_minima', '<=', $this->registro->edad)
                    ->where('edad_maxima', '>=', $this->registro->edad)
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('peso_minimo')
                                ->orWhere('peso_minimo', '<=', $this->registro->peso);
                        })
                        ->where(function ($q) {
                            $q->whereNull('peso_maximo')
                                ->orWhere('peso_maximo', '>=', $this->registro->peso);
                        });
                    })
                    ->where(function ($query) {
                        $query->where('cinta', 'negra')
                            ->orWhere('cinta', 'LIKE', '%' . 'negra' . '%');
                    })
                    ->where(function ($query) {
                        foreach ($this->categoriasInvalidasGlobal as $division) {
                            $query->Where('division', 'LIKE', '%' . $division . '%');
                        }
                    })
                    ->where(function ($query) {
                        $query->where('genero', $this->registro->genero)
                                ->orWhere('genero', 'mixto');
                    });
            
                    $fusionesCintaNegra = $fusionesCintaNegra->get();

                    foreach ($fusionesCintaNegra as $fusion) {
                        $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion, $edad, $peso, $cinta, $genero);
                        if ($categoriaAdecuada) {
                            $formaId = $categoriaAdecuada->forma_id;
                            if ($formaId == 12 || $formaId == 13 || $formaId ==  14){
                                $formaNombre = $categoriaAdecuada->forma->nombre; 
                                
                                // Solo añades la fusión si encontraste una categoría adecuada
                                if ($categoriaAdecuada) {
                                    $this->categoriasFiltradas->push([
                                        'tipo' => 'fusion',
                                        'id' => $categoriaAdecuada->id,
                                        'nombre' => $fusion->nombre,
                                        'edad_minima' => $fusion->edad_minima,
                                        'edad_maxima' => $fusion->edad_maxima,
                                        'genero' => $fusion->genero,
                                        'division' => $fusion->division,
                                        'peso_maximo' => $fusion->peso_maximo,
                                        'peso_minimo' => $fusion->peso_minimo,
                                        'forma_nombre' => $formaNombre,
                                        'forma_id' => $formaId,
                                    ]);
                                }
                            }
                        } else {
                            
                        }
                    }
                }
            }

            /* $divisionesInscritas = $this->categoriasInscritasIds;
            // Añadir un estado para indicar si ya está inscrito
            $this->categoriasFiltradas = $this->categoriasFiltradas->map(function($categoria) use ($divisionesInscritas) {
                // Si es una fusión (divisiones en una cadena)
                if (strpos($categoria['division'], ' / ') !== false) {
                    $divisionesFusionadas = explode(' / ', $categoria['division']);
                    // Verificar si alguna de las divisiones inscritas está en las fusionadas
                    $categoria['ya_inscrito'] = count(array_intersect($divisionesFusionadas, $divisionesInscritas)) > 0;
                } else {
                    // Verificar si la división simple está en las inscritas
                    $categoria['ya_inscrito'] = in_array($categoria['division'], $divisionesInscritas);
                }
                return $categoria;
            }); */

            $fusionCercana = Fusion::where('torneo_id', $this->torneo_id)
            ->where('edad_minima', '<=', $edad)
            ->where('edad_maxima', '>=', $edad)
            ->where(function ($query) use ($peso) {
                $query->where(function ($q) use ($peso) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $peso);
                })
                ->where(function ($q) use ($peso) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $peso);
                });
            })
            ->where(function ($query) {
                foreach ($this->categoriasInvalidasGlobal as $division) {
                    $query->orWhere('division', 'LIKE', '%' . $division . '%');
                }
            })
            ->where(function ($query) use ($genero) {
                $query->where('genero', $genero)
                        ->orWhere('genero', 'mixto');
            })
            ->orderByRaw("FIELD(division, '".implode("', '", $this->categoriasInvalidasGlobal)."')") // Ordenar por las divisiones más cercanas
            ->first();

            if ($fusionCercana) {
                $fusionCercana = $this->categoriasFiltradas; 
                return $this->categoriasFiltradas;
            } else {

                $categoriaCercana = Categoria::whereIn('division', $this->categoriasInvalidasGlobal)
                ->where('edad_minima', '<=', $edad)
                ->where('edad_maxima', '>=', $edad)
                ->where('peso_minimo', '<=', $peso)
                ->where('peso_maximo', '>=', $peso)
                ->where(function ($query) use ($genero) {
                    $query->where('genero', $genero)
                        ->orWhere('genero', 'mixto');
                })
                ->orderBy('id', 'asc')
                ->first();

                if ($categoriaCercana) {
                    $categoriaCercana = $this->categoriasFiltradas; 
                }
                
            dd($this->categoriasFiltradas);
                return $this->categoriasFiltradas;
            }
    }

    private function seleccionarCategoriaFusionadaAdecuada($fusion, $edad, $peso, $cinta, $genero)
    {
        // Obtener las divisiones de la fusión, separándolas por '/'
        $divisiones = explode(' / ', $fusion->division);

        $categoriaAdecuada = null;

        // Iterar sobre cada división y buscar la categoría correspondiente
        foreach ($divisiones as $division) {
            $categoriaAdecuada = Categoria::where('division', $division)
                ->whereIn('forma_id', [12, 13, 14])
                ->where(function ($query) use ($edad) {
                        $query->Where('edad_minima', '<=', $edad)
                        ->where('edad_maxima', '>=', $edad);
                    })
                ->where(function ($q) use ($peso) {
                        $q->Where('peso_minimo', '<=', $peso)
                        ->where('peso_maximo', '>=', $peso);
                    })
                ->where(function ($query) use ($cinta) {
                    $query->whereNull('cinta')
                    ->orWhere('cinta', $cinta);
                })
                ->where(function ($query) use ($genero) {
                    $query->where('genero', $genero)
                        ->orWhere('genero', 'mixto');
                })
                ->first();

            // Si encontramos una categoría adecuada, salir del bucle
            if ($categoriaAdecuada) {
                break;
            }
        }

        return $categoriaAdecuada;
    }

    public function render()
    {
        return view('livewire.calificacion', [
            'registro' => $this->registro,
            'selectedCategories' => $this->selectedCategories,
            'currentStep' => $this->step,
        ]);
    }

    public function incrementStep()
    {
        if ($this->step == 1) {
            // Validación del paso 1
            $this->validate([
                'nombre' => 'required|string',
                'apellidos' => 'required|string',
                'email' => 'nullable|email',
                'fec' => 'required|date',
                'cinta' => 'required|string',
                'telefono' => 'nullable|string',
                'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'genero' => 'required|string',
            ]);

            $user_id = $this->registro->alumno_id ? $this->registro->alumno_id : $this->registro->maestro_id;

            // Actualizar el peso en todas las categorías donde el competidor está inscrito
            RegistroTorneo::where('torneo_id', $this->registro->torneo_id)
                ->where(function ($query) use ($user_id) {
                    $query->where('alumno_id', $user_id)
                            ->orWhere('maestro_id', $user_id);
                })
                ->update(['peso' => $this->peso]);

            $this->verificarPeso();

        } elseif ($this->step == 2) {
            // Validación del paso 2
            $this->validate([
                'selectedCategories' => 'required|exists:categorias,id'
            ]);

        }

        if ($this->step < 3) { 
            $this->step++;
        }
    }
    
    public function decrementStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function cancelar()
    {
        return redirect('/');
    }

    private function saveStepOneData()
    {
        session()->put('registro.stepOne', [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'fec' => $this->fec,
            'cinta' => $this->cinta,
            'telefono' => $this->telefono,
            'peso' => $this->peso,
            'estatura' => 1,
            'genero' => $this->genero,
        ]);
    }

    private function saveStepTwoData()
    {
        session()->put('registro.stepTwo', [
            'selectedCategories' => $this->selectedCategories,
        ]);
    }

    public function store()
    {
        if ($this->step == 1) {
            // Validación del paso 1
            $this->validate([
                'nombre' => 'required|string',
                'apellidos' => 'required|string',
                'email' => 'required|email',
                'fec' => 'required|date',
                'cinta' => 'required|string',
                'telefono' => 'nullable|string',
                'peso' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                
                'genero' => 'required|string',
            ]);

            $this->saveStepOneData();
            $this->incrementStep();
        } elseif ($this->step == 2) {
            // Validación del paso 2
            $this->validate([
                'selectedCategories' => 'required|exists:categorias,id'
            ]);

            $this->saveStepTwoData();
            $this->incrementStep();
        } elseif ($this->step == 3) {

            $data =[
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'email' => $this->email,
            'fec' => $this->fec,
            'cinta' => $this->cinta,
            'telefono' => $this->telefono,
            'peso' => $this->peso,
            'estatura' => 1,
            'genero' => $this->genero,
            'categoria_id' => $this->selectedCategories,
            ];
        }

        $this->completeRegistration();

    }

    private function completeRegistration()
    {
        $stepOneData = session()->get('registro.stepOne', []);
        $stepTwoData = session()->get('registro.stepTwo', []);
    
        foreach ($this->selectedCategories as $categoriaId) {
            $keys = [
                'torneo_id' => $this->torneo_id,
                'categoria_id' => $categoriaId,
            ];

            if ($this->tipoUsuario === 'alumno') {
                $keys['alumno_id'] = $this->registro->alumno_id;
            } else {
                $keys['maestro_id'] = $this->registro->maestro_id;
            }

            $values = array_merge($stepOneData, $stepTwoData, [
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'email' => $this->email,
                'fec' => $this->fec,
                'cinta' => $this->cinta,
                'telefono' => $this->telefono,
                'peso' => $this->peso,
                'estatura' => 1,
                'genero' => $this->genero,
            ]);

                        $registroExistente = RegistroTorneo::where($keys)->first();

                        if ($registroExistente) {
                            // Si el registro existe, actualizarlo
                            $registroExistente->update($values);
                        } else {
                            // Si no existe, crear uno nuevo
                            RegistroTorneo::create(array_merge($keys, $values));
                        }
        }
    
        $this->registro_id = optional(RegistroTorneo::where($keys)->latest('id')->first())->id;

        session()->put('selectedCategoriesInfo', Categoria::whereIn('id', $this->selectedCategories)->get());

        flash()->options([
                'position' => 'top-center',
            ])->addSuccess('','Registro actualizado correctamente.');
    }
}
