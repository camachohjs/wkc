<?php

namespace App\Livewire;

use App\Models\Alumno;
use App\Models\Categoria;
use App\Models\Forma;
use App\Models\Fusion;
use App\Models\Maestro;
use App\Models\RegistroTorneo;
use App\Models\Torneo;
use App\Models\TorneosPrecios;
use App\services\GetPreciosService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

class RegistrarAdmin extends Component
{
    use WithPagination;
    public $torneo_id, $registro_id, $alumno_id, $id, $costoTotal;
    public $nombre, $apellidos, $email, $cinta, $peso, $estatura, $genero, $fec, $telefono, $usuario, $torneo, $torneo_datos, $tipoUsuario, $search, $edad, $id_participante;
    public $step = 1;
    public $categoria_seleccionada;
    public $usuarioEncontrado = true;
    public $perPage = 28;
    public $selectedCategories = []; 
    public $categoriasFiltradas;
    public $mayor_de_edad = false;
    public $cinta_negra = false;

    #[Title('Registrar')]
    #[Layout('components.layouts.layout')]

    public function mount($torneo_id, $id_participante)
    {
        $this->torneo_id = $torneo_id;
        $this->TorneoDatos();
        $this->selectedCategories = [];
        $this->id_participante = $id_participante;
        $this->buscarUsuarioPorId($this->id_participante);
    }

    public function TorneoDatos()
    {
        if ($this->torneo_id) {
            $this->torneo_datos = Torneo::findOrFail($this->torneo_id);
        }
    }

    public function buscarUsuarioPorId($id_participante)
    {
        $usuario = Alumno::where('id', $id_participante)->first();
        $tipoUsuario = 'alumno';
        
        // Si no se encuentra como alumno, busca como maestro
        if (!$usuario) {
            $usuario = Maestro::where('id', $id_participante)->first();
            $tipoUsuario = 'maestro';
        }

        if ($usuario) {
            $fechaNacimiento = new DateTime($usuario->fec);
            $añoActual = Carbon::now();
            $añoSiguiente = Carbon::now()->addYear();

            // Calcula la edad basada en el año
            $edad = $añoActual->format('Y') - $fechaNacimiento->format('Y');

            // Si aún no hemos llegado al 1 de enero del año siguiente al cumpleaños
            if ($añoActual->lt(Carbon::createFromDate($añoSiguiente->year, 1, 1))) {
                $edad = $edad-1;
            }
            /* dd($edad); */
            $this->usuario = $usuario;
            $this->usuario->edad = $edad;
            $this->nombre = $usuario->nombre;
            $this->apellidos = $usuario->apellidos;
            $this->email = $usuario->email;
            $this->cinta = $usuario->cinta;
            $this->peso = $usuario->peso;
            $this->estatura = number_format($usuario->estatura, 2);
            $this->genero = $usuario->genero;
            $this->fec = $usuario->fec;
            $this->telefono = $usuario->telefono;
            $this->usuarioEncontrado = true;
            $this->tipoUsuario = $tipoUsuario;
            $this->mayor_de_edad = $usuario->mayor_de_edad;
            $this->cinta_negra = $usuario->cinta_negra; 

            $this->loadTorneoData();
        } else {
            $this->resetInputFields();
            flash()->options([
                'position' => 'top-center',
            ])->addWarning('No se encontró el usuario. Por favor, regístrate en el sistema.');
            $this->usuarioEncontrado = false;
        }
    }

    public function loadTorneoData()
    {
        if ($this->torneo_id && $this->usuario) {
            $this->torneo = Torneo::findOrFail($this->torneo_id);
    
            // Inicializar la colección filtrada como vacía
            $this->categoriasFiltradas = collect([]);
    
            // Filtrar categorías
            $categorias = Categoria::whereHas('torneos', function ($query) {
                $query->where('torneo_id', $this->torneo_id);
            })
            ->with(['torneos' => function ($query) {
                $query->where('torneo_id', $this->torneo_id)->withPivot('area', 'horario');
            }])
            ->where('edad_minima', '<=', $this->usuario->edad)
            ->where('edad_maxima', '>=', $this->usuario->edad)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $this->usuario->peso);
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
                $query->where('genero', $this->usuario->genero)
                        ->orWhere('genero', 'mixto');
            })
            ->get();
            /* dd($categorias); */
    
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
            ->where('edad_minima', '<=', 18)
            ->where('edad_maxima', '>=', 18)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $this->usuario->peso);
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
                $query->where('genero', $this->usuario->genero)
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
                ->where('edad_minima', '<=', 18)
                ->where('edad_maxima', '>=', 18)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->whereNull('peso_minimo')
                            ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                    })
                    ->where(function ($q) {
                        $q->whereNull('peso_maximo')
                            ->orWhere('peso_maximo', '>=', $this->usuario->peso);
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
                    $query->where('genero', $this->usuario->genero)
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
            ->where('edad_minima', '<=', $this->usuario->edad)
            ->where('edad_maxima', '>=', $this->usuario->edad)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $this->usuario->peso);
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
                $query->where('genero', $this->usuario->genero)
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
    
            // Filtrar fusiones
            $fusiones = Fusion::where('torneo_id', $this->torneo_id)
            ->where('edad_minima', '<=', $this->usuario->edad)
            ->where('edad_maxima', '>=', $this->usuario->edad)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNull('peso_minimo')
                        ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                })
                ->where(function ($q) {
                    $q->whereNull('peso_maximo')
                        ->orWhere('peso_maximo', '>=', $this->usuario->peso);
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
                $query->where('genero', $this->usuario->genero)
                        ->orWhere('genero', 'mixto');
            });
    
            $fusiones = $fusiones->get();
            $categoriasNoEncontradas = [];
            foreach ($fusiones as $fusion) {
                $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion);
                if (!$categoriaAdecuada) {
                    $categoriasNoEncontradas[] = $fusion->division;
                    continue;
                }
                $formaId = $categoriaAdecuada->forma_id; 
                $formaNombre = $categoriaAdecuada->forma->nombre; 
                
                if ($categoriaAdecuada) {
                    $this->categoriasFiltradas->push([
                        'tipo' => 'fusion',
                        'id' => $categoriaAdecuada->id,
                        'nombre' => $fusion->nombre,
                        'edad_minima' => $fusion->edad_minima,
                        'edad_maxima' => $fusion->edad_maxima,
                        'genero' => $fusion->genero,
                        'division' => $fusion->division,
                        'area' => ucfirst($fusion->area),
                        'horario' => Carbon::parse($fusion->horario)->format('H:i \h\r\s / d-m-Y'),
                        'forma_nombre' => $formaNombre,
                        'forma_id' => $formaId,
                        'peso_maximo' => $fusion->peso_maximo,
                        'peso_minimo' => $fusion->peso_minimo,
                        'categoria' => $categoriaAdecuada,
                    ]);
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
                            ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                    })
                    ->where(function ($q) {
                        $q->whereNull('peso_maximo')
                            ->orWhere('peso_maximo', '>=', $this->usuario->peso);
                    });
                })
                ->where(function ($query) {
                    $query->where('cinta', 'negra')
                        ->orWhere('cinta', 'LIKE', '%' . 'negra' . '%');
                })
                ->where(function ($query) {
                    $query->where('genero', $this->usuario->genero)
                            ->orWhere('genero', 'mixto');
                });
        
                $fusionesCintaNegraMayorEdad = $fusionesCintaNegraMayorEdad->get();
    
                foreach ($fusionesCintaNegraMayorEdad as $fusion) {
                    $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion);
                    if (!$categoriaAdecuada) {
                        $categoriasNoEncontradas[] = $fusion->division;
                        continue;
                    }
                    $formaId = $categoriaAdecuada->forma_id; 
                    $formaNombre = $categoriaAdecuada->forma->nombre; 
                    
                    if ($categoriaAdecuada) {
                        $this->categoriasFiltradas->push([
                            'tipo' => 'fusion',
                            'id' => $categoriaAdecuada->id,
                            'nombre' => $fusion->nombre,
                            'edad_minima' => $fusion->edad_minima,
                            'edad_maxima' => $fusion->edad_maxima,
                            'genero' => $fusion->genero,
                            'division' => $fusion->division,
                            'area' => ucfirst($fusion->area),
                            'horario' => Carbon::parse($fusion->horario)->format('H:i \h\r\s / d-m-Y'),
                            'forma_nombre' => $formaNombre,
                            'forma_id' => $formaId,
                            'peso_maximo' => $fusion->peso_maximo,
                            'peso_minimo' => $fusion->peso_minimo,
                            'categoria' => $categoriaAdecuada,
                        ]);
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
                            ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                    })
                    ->where(function ($q) {
                        $q->whereNull('peso_maximo')
                            ->orWhere('peso_maximo', '>=', $this->usuario->peso);
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
                    $query->where('genero', $this->usuario->genero)
                            ->orWhere('genero', 'mixto');
                });
        
                $fusionesMayorEdad = $fusionesMayorEdad->get();
    
                foreach ($fusionesMayorEdad as $fusion) {
                    $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion);
                    if (!$categoriaAdecuada) {
                        $categoriasNoEncontradas[] = $fusion->division;
                        continue;
                    }
                    $formaId = $categoriaAdecuada->forma_id; 
                    $formaNombre = $categoriaAdecuada->forma->nombre; 
                    
                    if ($categoriaAdecuada) {
                        $this->categoriasFiltradas->push([
                            'tipo' => 'fusion',
                            'id' => $categoriaAdecuada->id,
                            'nombre' => $fusion->nombre,
                            'edad_minima' => $fusion->edad_minima,
                            'edad_maxima' => $fusion->edad_maxima,
                            'genero' => $fusion->genero,
                            'division' => $fusion->division,
                            'area' => ucfirst($fusion->area),
                            'horario' => Carbon::parse($fusion->horario)->format('H:i \h\r\s / d-m-Y'),
                            'forma_nombre' => $formaNombre,
                            'forma_id' => $formaId,
                            'peso_maximo' => $fusion->peso_maximo,
                            'peso_minimo' => $fusion->peso_minimo,
                            'categoria' => $categoriaAdecuada,
                        ]);
                    }
                }
            }
            // Cuarta búsqueda para cinta negra si aplica
            if (!$this->cinta_negra && $this->mayor_de_edad) {
                $fusionesCintaNegra = fusion::where('torneo_id', $this->torneo_id)
                ->where('edad_minima', '<=', $this->usuario->edad)
                ->where('edad_maxima', '>=', $this->usuario->edad)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->whereNull('peso_minimo')
                            ->orWhere('peso_minimo', '<=', $this->usuario->peso);
                    })
                    ->where(function ($q) {
                        $q->whereNull('peso_maximo')
                            ->orWhere('peso_maximo', '>=', $this->usuario->peso);
                    });
                })
                ->where(function ($query) {
                    $query->where('cinta', 'negra')
                        ->orWhere('cinta', 'LIKE', '%' . 'negra' . '%');
                })
                ->where(function ($query) {
                    $query->where('genero', $this->usuario->genero)
                            ->orWhere('genero', 'mixto');
                });
        
                $fusionesCintaNegra = $fusionesCintaNegra->get();
    
                foreach ($fusionesCintaNegra as $fusion) {
                    $categoriaAdecuada = $this->seleccionarCategoriaFusionadaAdecuada($fusion);
                    if (!$categoriaAdecuada) {
                        $categoriasNoEncontradas[] = $fusion->division;
                        continue;
                    }
                    $formaId = $categoriaAdecuada->forma_id; 
                    $formaNombre = $categoriaAdecuada->forma->nombre; 
                    
                    if ($categoriaAdecuada) {
                        $this->categoriasFiltradas->push([
                            'tipo' => 'fusion',
                            'id' => $categoriaAdecuada->id,
                            'nombre' => $fusion->nombre,
                            'edad_minima' => $fusion->edad_minima,
                            'edad_maxima' => $fusion->edad_maxima,
                            'genero' => $fusion->genero,
                            'division' => $fusion->division,
                            'area' => ucfirst($fusion->area),
                            'horario' => Carbon::parse($fusion->horario)->format('H:i \h\r\s / d-m-Y'),
                            'forma_nombre' => $formaNombre,
                            'forma_id' => $formaId,
                            'peso_maximo' => $fusion->peso_maximo,
                            'peso_minimo' => $fusion->peso_minimo,
                            'categoria' => $categoriaAdecuada,
                        ]);
                    }
                }
            }
            if (!empty($categoriasNoEncontradas)) {
                $mensaje = 'No se encontró una categoría adecuada para las siguientes fusiones: ' . implode(', ', $categoriasNoEncontradas);
                flash()->options([
                    'position' => 'top-center',
                ])->addWarning('', $mensaje);
            }
        }
        /* $this->categoriasFiltradas = $this->categoriasFiltradas->unique('id'); */
    }

    private function seleccionarCategoriaFusionadaAdecuada($fusion)
    {
        $categoriaAdecuada = $fusion->categorias()
            ->where('edad_minima', '<=', $this->usuario->edad)
            ->where('edad_maxima', '>=', $this->usuario->edad)
            ->where(function ($query) {
                $query->whereNull('peso_minimo')
                    ->orWhere('peso_minimo', '<=', $this->usuario->peso);
            })
            ->where(function ($query) {
                $query->whereNull('peso_maximo')
                    ->orWhere('peso_maximo', '>=', $this->usuario->peso);
            })
            ->where(function ($query) {
                $query->where('cinta', $this->usuario->cinta) //  Buscar en la columna "cinta"
                    ->orWhere('cinta', 'LIKE', '%' . $this->usuario->cinta . '%') //  Buscar con "LIKE"
                    ->orWhere(function ($q) {
                        $q->where('cinta', 'P/I/A')
                            ->whereIn(DB::raw("'" . $this->usuario->cinta . "'"), ['principiante', 'intermedio', 'avanzada']);
                    })
                    ->orWhereHas('cintas', function ($query) { //  Buscar en la tabla intermedia "categoria_cinta"
                        $query->where('cinta', $this->usuario->cinta);
                    });
            })
            ->where(function ($query) {
                $query->where('genero', $this->usuario->genero)
                    ->orWhere('genero', 'mixto');
            });

        // Si el usuario es cinta negra, filtrar también en la tabla intermedia
        if ($this->cinta_negra) {
            $categoriaAdecuada = $categoriaAdecuada->where(function ($query) {
                $query->where('cinta', 'negra') // En la columna "cinta"
                    ->orWhereHas('cintas', function ($subQuery) {
                        $subQuery->where('cinta', 'negra'); // En la tabla intermedia
                    });
            });
        }

        // Filtrar por mayor de edad si es aplicable
        if ($this->mayor_de_edad) {
            $categoriaAdecuada = $categoriaAdecuada->where('edad_minima', '<=', 18)
                                                ->where('edad_maxima', '>=', 18);
        }

        $categoriaAdecuada = $categoriaAdecuada->first();
            return $categoriaAdecuada;
    }

    public function resetInputFields()
    {
        $this->nombre = '';
        $this->apellidos = '';
        $this->email = '';
        $this->cinta = '';
        $this->peso = '';
        $this->estatura = '';
        $this->genero = '';
        $this->fec = '';
        $this->telefono = '';
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

        } elseif ($this->step == 3) {
            // Validación del paso 3
            $this->validate([
                'selectedCategories' => 'required|exists:categorias,id'
            ]);

        }

        if ($this->step < 4) { 
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

    public function render()
    {
        if (is_array($this->selectedCategories) && count($this->selectedCategories) == 0 && $this->step == 3) {
            $this->step--;
            flash()->options([
                'position' => 'top-center',
            ])->addError(' ','Por favor, selecciona al menos una categoría antes de continuar.');
        }

        $user = auth()->user();
        $maestroId = $user->maestro->id;

        $maestro = Maestro::with(['alumnos.registros.torneo', 'alumnos.registros.categoria'])
                            ->where('id', $maestroId)
                            ->with(['registros' => function($query) {
                                $query->with('torneo', 'categoria');
                            }])
                            ->first();
    
        $atletas = Alumno::whereHas('escuelas', function ($query) use ($maestroId) {
            $query->where('maestro_id', $maestroId);
        })
        ->with(['registros' => function($query) {
            $query->with('torneo', 'categoria');
        }])
        ->where(function($query) {
            $query->whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$this->search}%"])
                    ->orWhere('email', 'LIKE', "%{$this->search}%");
        })
        ->paginate($this->perPage);

        $maestroComoAtleta = new Alumno([
            'nombre' => $maestro->nombre,
            'apellidos' => $maestro->apellidos,
            'fec' => $maestro->fec,
            'peso' => $maestro->peso,
            'user_id' => $maestro->user_id,
            'id' => $maestro->id,
        ]);
        $maestroComoAtleta->id = $maestro->id;
        $atletas->prepend($maestroComoAtleta);

        return view('livewire.registrar-admin', [
            'torneo' => $this->torneo_id,
            'selectedCategories' => $this->selectedCategories,
            'currentStep' => $this->step,
            'atletas' => $atletas,
            'maestro' => $maestro,
        ]);
    }

    public function buscar()
    {
        $this->resetPage();
    }

    private function savestepTwoData()
    {
        session()->put('registro.stepTwo', [
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

    private function saveStepThreeData()
    {
        session()->put('registro.StepThree', [
            'selectedCategories' => $this->selectedCategories,
        ]);
    }

    public function loadStepData()
    {
        if ($stepTwoData = session()->get('registro.stepTwo')) {
            $this->nombre = $stepTwoData['nombre'];
            $this->apellidos = $stepTwoData['apellidos'];
            $this->email = $stepTwoData['email'];
            $this->fec = $stepTwoData['fec'];
            $this->cinta = $stepTwoData['cinta'];
            $this->telefono = $stepTwoData['telefono'];
            $this->peso = $stepTwoData['peso'];
            $this->estatura = $stepTwoData['estatura'];
            $this->genero = $stepTwoData['genero'];
        }
        /* dd($stepTwoData); */

        // Cargar datos del paso 3
        if ($StepThreeData = session()->get('registro.StepThree')) {
            $this->selectedCategories = $StepThreeData['selectedCategories'];
        }
    }

    public function store()
    {
        if ($this->step == 1)  {
            // Validación del paso 2
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

            $this->savestepTwoData();
            $this->incrementStep();
        } elseif ($this->step == 2) {
            // Validación del paso 2
            $this->validate([
                'selectedCategories' => 'required|exists:categorias,id'
            ]);

            $this->saveStepThreeData();
            $this->incrementStep();
        } elseif ($this->step == 3) {
            $this->incrementStep();
        }

        elseif ($this->step == 4) {

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

    public function deseleccionarCategoria($categoriaId)
    {
        unset($this->selectedCategories[array_search($categoriaId, $this->selectedCategories)]);
        $this->selectedCategories = array_values($this->selectedCategories);
    }

    private function completeRegistration()
    {
        $stepOneData = session()->get('registro.stepOne', []);
        $stepTwoData = session()->get('registro.stepTwo', []);

        $categoriasEspeciales = 0;
        $cuartetas = 0;
        $categoriasBasicas = 0;
        $equipo = 0;

        foreach ($this->selectedCategories as $categoriaId) {
            $categoria = Categoria::find($categoriaId);
            $tipoFormaId = $categoria->forma->tipos_formas_id;

            if ($tipoFormaId == 2) { // Si es una CATEGORÍA ESPECIAL
                $categoriasEspeciales++;
            } elseif ($tipoFormaId == 3) { // Si es una CUARTETA
                $cuartetas++;
            } elseif ($tipoFormaId == 1) { // Si es una básica
                $categoriasBasicas++;
            } elseif ($tipoFormaId == 7) { // Si es una básica
                $equipo++;
            }

            $keys = [
                'torneo_id' => $this->torneo_id,
                'categoria_id' => $categoriaId,
            ];

            if ($this->tipoUsuario === 'alumno') {
                $keys['alumno_id'] = $this->id_participante;
            } else {
                $keys['maestro_id'] = $this->id_participante;
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

            RegistroTorneo::updateOrCreate($keys, $values);
        }


        $getPrecios = new GetPreciosService();
        $allCategorias = Categoria::whereIn('id', $this->selectedCategories)->get();
        
        $allCategorias->each(function ($categoria) {
            $categoria->id_categoria = $categoria->id;
            $categoria->fecha = $categoria->created_at->format('d/m/Y');
        });
        $allCategorias = $getPrecios->precioCategoria($allCategorias, $this->torneo_id);
        foreach ($allCategorias as $categoria) {
            $this->costoTotal += $categoria['precio'];
        }


        // Lógica para determinar el costo basado en las combinaciones
        // if ($categoriasBasicas > 0) {
        //     // Siempre sumar una categoría básica
        //     $torneoPrecioBasica = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //         ->where('tipos_formas_id', 1) // ID para BÁSICA (KATA Y/O COMBATE)
        //         ->first();

        //     if ($torneoPrecioBasica) {
        //         $fechaActual = now();
        //         $this->costoTotal += $fechaActual->isBefore($torneoPrecioBasica->fecha)
        //             ? $torneoPrecioBasica->costo_pre_registro
        //             : $torneoPrecioBasica->costo_registro;
        //     }

        //     // Si hay más de una básica, sumar como adicionales
        //     if ($categoriasBasicas > 1) {
        //         $torneoPrecioAdicional = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //             ->where('tipos_formas_id', 2) // ID para CATEGORÍA ADICIONAL
        //             ->first();

        //         if ($torneoPrecioAdicional) {
        //             $this->costoTotal += ($categoriasBasicas - 1) * ($fechaActual->isBefore($torneoPrecioAdicional->fecha)
        //                 ? $torneoPrecioAdicional->costo_pre_registro
        //                 : $torneoPrecioAdicional->costo_registro);
        //         }
        //     }
        // }

        // if ($categoriasEspeciales >= 2) {
        //     // C. ESPECIAL MÁS 2 CATEGORÍAS
        //     $torneoPrecio = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //         ->where('tipos_formas_id', 6) // ID para C. ESPECIAL MÁS 2 CATEGORÍAS
        //         ->first();
        // } elseif ($categoriasEspeciales == 1 && $cuartetas > 0) {
        //     // C. ESPECIAL Y CUARTETAS
        //     $torneoPrecio = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //         ->where('tipos_formas_id', 4) // ID para C. ESPECIAL Y CUARTETAS
        //         ->first();
        // } elseif ($categoriasEspeciales == 1) {
        //     // C. ESPECIAL MÁS 1 CATEGORÍA
        //     $torneoPrecio = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //         ->where('tipos_formas_id', 5) // ID para C. ESPECIAL MÁS 1 CATEGORÍA
        //         ->first();
        // } elseif ($cuartetas > 0) {
        //     // CUARTETAS
        //     $torneoPrecio = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //         ->where('tipos_formas_id', 3) // ID para CUARTETAS
        //         ->first();
        // } elseif ($equipo > 0) {
        //     // CUARTETAS
        //     $torneoPrecio = TorneosPrecios::where('torneo_id', $this->torneo_id)
        //         ->where('tipos_formas_id', 7) // ID para CUARTETAS
        //         ->first();
        // }

        // if (isset($torneoPrecio) && $torneoPrecio) {
        //     $fechaActual = now();
        //     $this->costoTotal += $fechaActual->isBefore($torneoPrecio->fecha)
        //         ? $torneoPrecio->costo_pre_registro
        //         : $torneoPrecio->costo_registro;
        // }

        session()->put('costoTotal', $this->costoTotal);
    }
}