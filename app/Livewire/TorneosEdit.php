<?php

namespace App\Livewire;

use App\Models\Categoria;
use App\Models\CategoriaTorneo;
use App\Models\Forma;
use App\Models\Fusion;
use App\Models\Seccion;
use App\Models\TiposFormas;
use App\Models\TorneosPrecios;
use Livewire\Component;
use App\Models\Torneo;
use Flasher\Prime\FlasherInterface;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TorneosEdit extends Component
{
    use WithPagination, WithFileUploads;
    public $torneo_id;
    public $nombre, $descripcion, $fecha_evento, $fecha_registro, $fecha_actual, $direccion, $banner, $ranking, $rankings = [], $premios  = [], $buscar, $datosCombinados = [];
    public $isOpen = 0;
    public $banner_actual;
    public $step = 1;
    public $formas, $secciones;
    public $selectedCategories = [];
    public $seleccionarCategoriasCombinadas = [];
    public $areas = [];
    public $horarios = [];
    public $fusionesSeleccionadas = [];
    public $areasFusiones = [];
    public $horariosFusiones = [];
    public $combinacionareasHorarios = [];
    public $areaCompartida;
    public $horarioCompartido;
    public $mostrarBotonConfirmar = false;
    public $mostrarBotonCombinacionAreas = false;
    public $tiposFormas;
    public $torneosPrecios = [];
    public $bannerPreview;
    public $categoriasYaFusionadas = [];

    #[Title('Torneos')]
    #[Layout('components.layouts.layout')]

    public function render()
    {

        if ($this->step == 3 && !empty($this->buscar)) {
            $this->secciones = Seccion::with(['formas' => function ($query) {
                // Filtrar formas que tengan categorías coincidentes
                $query->whereHas('categorias', function ($subquery) {
                    $subquery->where('nombre', 'like', '%' . $this->buscar . '%')
                                ->orWhere('division', 'like', '%' . $this->buscar . '%')
                                ->orWhere('cinta', 'like', '%' . $this->buscar . '%');
                });
            }, 'formas.categorias' => function ($query) {
                // Filtrar categorías dentro de las formas
                $query->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhere('division', 'like', '%' . $this->buscar . '%')
                        ->orWhere('cinta', 'like', '%' . $this->buscar . '%');
            }])->get();
        } else {
            $this->secciones = Seccion::with('formas.categorias')->get();
        }
        if(count($this->selectedCategories) == 0 && $this->step == 4) {
            $this->step--;
            flash()->options([
                'position' => 'top-center',
            ])->addError(' ','Por favor, selecciona al menos una categoría antes de continuar.');
        }
        /* dd($this->banner); */
        return view('livewire.torneos-edit', [
            'formas' => $this->formas,
            'secciones' => $this->secciones,
            'torneosPrecios' => $this->torneosPrecios,
        ]);
    }

    public function updatedBanner()
    {
        $this->bannerPreview = $this->banner->temporaryUrl();
    }

    public function incrementStep()
    {
        if ($this->step == 1) {
            // Validación del paso 1
            $this->validate([
                'nombre' => 'required|string',
                'direccion' => 'required|string',
                'ranking' => 'integer|nullable',
                'banner' => 'image|max:5120|nullable',
            ]);

            $nombre_imagen = '';

            if ($this->banner) {
                $nombre_imagen = Str::uuid() . '.' . $this->banner->getClientOriginalExtension();
                $ruta_storage = 'Img/torneos/' . $nombre_imagen;
                Storage::disk('public')->put($ruta_storage, file_get_contents($this->banner->getRealPath()));
                $urlImagen = asset(Storage::url($ruta_storage));
            } elseif ($this->banner_actual) {
                $urlImagen = $this->banner_actual;
            } else {
                $urlImagen = null;
            }

            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'fecha_evento' => $this->fecha_evento ?? now(),
                'fecha_registro' => $this->fecha_registro ?? now(),
                'direccion' => $this->direccion,
                'ranking' => $this->ranking ?? NULL,
                'banner' => $urlImagen,
                'premios' => NULL,
                'rankings' =>  NULL,
            ];

            $torneo = Torneo::updateOrCreate(['id' => $this->torneo_id], $data);
            $this->torneo_id = $torneo->id;

        } elseif ($this->step == 2) {
            // Validación del paso 2
            $this->validate([
                'fecha_evento' => 'required|date',
                'fecha_registro' => 'required|date',
                'premios.*' => 'string|nullable', 
                'rankings.*' => 'string|nullable', 
            ]);

            $this->saveStepTwoData();

            Torneo::where('id', $this->torneo_id)->update([
                'fecha_evento' => $this->fecha_evento,
                'fecha_registro' => $this->fecha_registro,
            ]);

            DB::table('torneos_premios')->where('torneo_id', $this->torneo_id)->delete();
            foreach ($this->premios as $premio) {
                DB::table('torneos_premios')->insert([
                    'torneo_id' => $this->torneo_id,
                    'premio' => $premio,
                ]);
            }

            DB::table('torneos_rankings')->where('torneo_id', $this->torneo_id)->delete();
            foreach ($this->rankings as $ranking) {
                DB::table('torneos_rankings')->insert([
                    'torneo_id' => $this->torneo_id,
                    'ranking' => $ranking,
                ]);
            }
        }

        if ($this->step == 3) {
            $this->guardarCategoriasSeleccionadas();
        }

        if ($this->step < 5) { 
            $this->step++;
        }

        if($this->step == 5) {

            $mensajes = [];

            foreach ($this->torneosPrecios as $i => $precio) {
                $nombre = strtoupper($precio['nombre_tipo_formas'] ?? "TIPO {$i}");

                $mensajes["torneosPrecios.{$i}.fecha.required"] = "El campo fecha del tipo {$nombre} es obligatorio.";
                $mensajes["torneosPrecios.{$i}.costo_pre_registro.required"] = "El campo pre registro del tipo {$nombre} es obligatorio.";
                $mensajes["torneosPrecios.{$i}.costo_registro.required"] = "El campo registro del tipo {$nombre} es obligatorio.";
            }

            $this->validate([
                'torneosPrecios.*.fecha' => 'required|date',
                'torneosPrecios.*.costo_pre_registro' => 'required|numeric',
                'torneosPrecios.*.costo_registro' => 'required|numeric',
                'torneosPrecios.*.tipos_formas_id' => 'required|integer',
            ], $mensajes);
            foreach ($this->torneosPrecios as $precio) {
                if($precio['id'] == null){
                    $torneosPrecios = new TorneosPrecios();
                    $torneosPrecios->fecha = $precio['fecha'];
                    $torneosPrecios->costo_pre_registro = $precio['costo_pre_registro'];
                    $torneosPrecios->costo_registro = $precio['costo_registro'];
                    $torneosPrecios->tipos_formas_id = $precio['tipos_formas_id'];
                    $torneosPrecios->torneo_id = $this->torneo_id;
                    $torneosPrecios->save();
                }else{
                    $torneosPrecios = TorneosPrecios::find($precio['id']);
                    $torneosPrecios->fecha = $precio['fecha'];
                    $torneosPrecios->costo_pre_registro = $precio['costo_pre_registro'];
                    $torneosPrecios->costo_registro = $precio['costo_registro'];
                    $torneosPrecios->tipos_formas_id = $precio['tipos_formas_id'];
                    $torneosPrecios->torneo_id = $this->torneo_id;
                    $torneosPrecios->save();
                }
            }
        }
    }
    
    public function decrementStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function resetInputFields()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->fecha_evento = '';
        $this->fecha_registro = '';
        $this->direccion = '';
        $this->banner = '';
        $this->ranking = '';
        $this->premios = [];
        $this->rankings = [];
    }

    public function mount()
    {
        $this->torneo_id = request()->route('id');

        $this->fecha_actual = now()->format('Y-m-d\TH:i');

        if ($this->torneo_id) {
            $torneo = Torneo::with('categorias', 'fusiones')->findOrFail($this->torneo_id);

            $this->nombre = $torneo->nombre;
            $this->descripcion = $torneo->descripcion;
            $this->fecha_evento = $torneo->fecha_evento;
            $this->fecha_registro = $torneo->fecha_registro;
            $this->direccion = $torneo->direccion;
            $this->banner_actual = $torneo->banner;
            $this->ranking = $torneo->ranking;
            $this->premios = $torneo->premios ?: [];
            $this->rankings = $torneo->rankings ?: [];
            
            $this->premios = DB::table('torneos_premios')
                ->where('torneo_id', $this->torneo_id)
                ->pluck('premio')
                ->toArray();

            $this->rankings = DB::table('torneos_rankings')
                ->where('torneo_id', $this->torneo_id)
                ->pluck('ranking')
                ->toArray();

            $this->selectedCategories = $torneo->categorias->pluck('id')->toArray();
            $this->areas = [];
            $this->horarios = [];
            foreach ($torneo->categorias as $categoria) {
                $this->areas[$categoria->pivot->categoria_id] = $categoria->pivot->area ?? '';
                $this->horarios[$categoria->pivot->categoria_id] = $categoria->pivot->horario ?? '';
            }
            foreach ($torneo->fusiones as $fusion) {
                $this->areas[$fusion->id] = $fusion->area ?? '';
                $this->horarios[$fusion->id] = $fusion->horario ?? '';
            }

            // Cargar precios
            $this->tiposFormas = TiposFormas::all();
            $precios = TorneosPrecios::where('torneo_id', $this->torneo_id)->get();
            $this->torneosPrecios = [];
            foreach ($this->tiposFormas as $formas) {
                if ($precios->contains('tipos_formas_id', $formas->id)) {
                    $this->torneosPrecios[] = [
                        'id' => $precios->where('tipos_formas_id', $formas->id)->first()->id,
                        'fecha' => $precios->where('tipos_formas_id', $formas->id)->first()->fecha,
                        'costo_pre_registro' => $precios->where('tipos_formas_id', $formas->id)->first()->costo_pre_registro,
                        'costo_registro' => $precios->where('tipos_formas_id', $formas->id)->first()->costo_registro,
                        'tipos_formas_id' => $formas->id,
                        'nombre_tipo_formas' => $formas->nombre,
                    ];
                } else {
                    $this->torneosPrecios[] = [
                        'id' => null,
                        'fecha' => null,
                        'costo_pre_registro' => null,
                        'costo_registro' => null,
                        'tipos_formas_id' => $formas->id,
                        'nombre_tipo_formas' => $formas->nombre,
                    ];
                }
            }
        } else {
            $this->rankings = [''];
            $this->premios = [''];
        }
        
        $this->secciones = Seccion::with('formas')->get();
        $this->formas = Forma::with('categorias')->get();
        $fusiones = Fusion::where('torneo_id', $this->torneo_id)->with('categorias')->get();

                foreach ($fusiones as $fusion) {
                    foreach ($fusion->categorias as $categoria) {
                        $this->categoriasYaFusionadas[$categoria->id] = $fusion->division;
                    }
                }

        $this->fusionesSeleccionadas = [];
    }

    public function agregarPremio()
    {
        $this->premios[] = '';
    }

    public function eliminarPremio($index)
    {
        unset($this->premios[$index]);
        $this->premios = array_values($this->premios); 
    }

    public function agregarRanking()
    {
        $this->rankings[] = '';
    }

    public function eliminarRanking($index)
    {
        unset($this->rankings[$index]);
        $this->rankings = array_values($this->rankings); 
    }

    public function saveStepOneData()
    {
        $nombre_imagen = '';

        if ($this->banner) {
            $nombre_imagen = Str::uuid() . '.' . $this->banner->getClientOriginalExtension();
            $ruta_storage = 'Img/torneos/' . $nombre_imagen;
            Storage::disk('public')->put($ruta_storage, file_get_contents($this->banner->getRealPath()));
            $urlImagen = asset(Storage::url($ruta_storage));
        } elseif ($this->banner_actual) {
            $urlImagen = $this->banner_actual;
        } else {
            $urlImagen = null;
        }

        session()->put('torneo.stepOne', [
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'banner' => $ $urlImagen,
        ]);
    }

    public function saveStepTwoData()
    {
        session()->put('torneo.stepTwo', [
            'fecha_evento' => $this->fecha_evento,
            'fecha_registro' => $this->fecha_registro,
            'premios' => $this->premios,
            'rankings' => $this->rankings,
        ]);
    }

    public function loadStepData()
    {
        if ($stepOneData = session()->get('torneo.stepOne')) {
            $this->nombre = $stepOneData['nombre'];
            $this->direccion = $stepOneData['direccion'];
            $this->banner = $stepOneData['banner'];
        }

        // Cargar datos del paso 2
        if ($stepTwoData = session()->get('torneo.stepTwo')) {
            $this->fecha_evento = $stepTwoData['fecha_evento'];
            $this->fecha_registro = $stepTwoData['fecha_registro'];
            $this->premios = $stepTwoData['premios'];
            $this->rankings = $stepTwoData['rankings'];
        }
    }

    public function store()
    {
        /* dd($this->step); */
        if ($this->step == 1) {
            // Validación del paso 1
            $this->validate([
                'nombre' => 'required|string',
                'direccion' => 'required|string',
                'ranking' => 'integer|nullable',
                'banner' => 'image|max:5120|nullable',
            ]);

            $this->saveStepOneData();
            $this->incrementStep();
        } elseif ($this->step == 2) {
            // Validación del paso 2
            $this->validate([
                'fecha_evento' => 'required|date',
                'fecha_registro' => 'required|date',
                'premios.*' => 'string|nullable', 
                'rankings.*' => 'string|nullable', 
            ]);

            $this->saveStepTwoData();
            $this->incrementStep();
        } elseif ($this->step == 5) {

            $nombre_imagen = '';

            if ($this->banner) {
                $nombre_imagen = Str::uuid() . '.' . $this->banner->getClientOriginalExtension();
                $ruta_storage = 'Img/torneos/' . $nombre_imagen;
                Storage::disk('public')->put($ruta_storage, file_get_contents($this->banner->getRealPath()));
                $urlImagen = asset(Storage::url($ruta_storage));
            } elseif ($this->banner_actual) {
                $urlImagen = $this->banner_actual;
            } else {
                $urlImagen = null;
            }

            $data = [
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'fecha_evento' => $this->fecha_evento,
                'fecha_registro' => $this->fecha_registro,
                'direccion' => $this->direccion,
                'ranking' => $this->ranking,
                'banner' => $urlImagen,
                'premios' => $this->premios,
                'rankings' => $this->rankings,
                'torneo_configurado' => 1,
            ];

            /* dd($data); */
            $torneo = Torneo::updateOrCreate(['id' => $this->torneo_id], $data);
            /* dd($this->selectedCategories); */
            $order_position = 0;
            $categories = [];

            foreach ($this->selectedCategories as $categoryId) {
                $categories[$categoryId] = ['order_position' => $order_position];
            }

            $torneo->categorias()->sync($categories);

            /* dd($this->selectedCategories); */
            $torneoId = $this->torneo_id;

            foreach ($this->selectedCategories as $categoriaId) {
                /* dd($categoriaId); */
                $categoriaTorneo = CategoriaTorneo::where('torneo_id', $torneoId)
                                                    ->where('categoria_id', $categoriaId)
                                                    ->first();
                if ($categoriaTorneo) {
                    $categoriaTorneo->area = $this->areas[$categoriaId] ?? null;
                    $horario = $this->horarios[$categoriaId] ?? null;
                    $categoriaTorneo->horario = $horario !== '' ? $horario : null;
                    $categoriaTorneo->save();
                }
            }
            $fusiones = Fusion::where('torneo_id', $this->torneo_id)->get();
            foreach ($fusiones as $fusion) {
                /* dd($fusion); */
                if (isset($this->areas[$fusion->id])) {
                    $fusion->area = $this->areas[$fusion->id];
                }
            
                $fusion->horario = isset($this->horarios[$fusion->id]) && $this->horarios[$fusion->id] !== ''
                ? $this->horarios[$fusion->id]
                : null;

                $fusion->save();
            }

            $erroresPrecios = false;

            foreach ($this->torneosPrecios as $index => $precio) {
                $nombre = $this->tiposFormas->firstWhere('id', $precio['tipos_formas_id'])->nombre ?? 'Tipo desconocido';

                if (empty($precio['fecha'])) {
                    $this->addError("torneosPrecios.$index.fecha", "El campo fecha del tipo $nombre es obligatorio.");
                    $erroresPrecios = true;
                }

                if ($precio['costo_pre_registro'] === null || $precio['costo_pre_registro'] === '') {
                    $this->addError("torneosPrecios.$index.costo_pre_registro", "El pre registro del tipo $nombre es obligatorio.");
                    $erroresPrecios = true;
                }

                if ($precio['costo_registro'] === null || $precio['costo_registro'] === '') {
                    $this->addError("torneosPrecios.$index.costo_registro", "El registro del tipo $nombre es obligatorio.");
                    $erroresPrecios = true;
                }
            }

            if ($erroresPrecios) {
                flash()->options(['position' => 'top-center'])->addError(' ','Por favor llena todos los campos obligatorios de precios.');
                return;
            }

            foreach ($this->torneosPrecios as $precio) {
                if($precio['id'] == null){
                    $torneosPrecios = new TorneosPrecios();
                    $torneosPrecios->fecha = $precio['fecha'];
                    $torneosPrecios->costo_pre_registro = $precio['costo_pre_registro'];
                    $torneosPrecios->costo_registro = $precio['costo_registro'];
                    $torneosPrecios->tipos_formas_id = $precio['tipos_formas_id'];
                    $torneosPrecios->torneo_id = $this->torneo_id;
                    $torneosPrecios->save();
                }else{
                    $torneosPrecios = TorneosPrecios::find($precio['id']);
                    $torneosPrecios->fecha = $precio['fecha'];
                    $torneosPrecios->costo_pre_registro = $precio['costo_pre_registro'];
                    $torneosPrecios->costo_registro = $precio['costo_registro'];
                    $torneosPrecios->tipos_formas_id = $precio['tipos_formas_id'];
                    $torneosPrecios->torneo_id = $this->torneo_id;
                    $torneosPrecios->save();
                }
            }

            flash()->options([
                'position' => 'top-center',
            ])->addSuccess('', $this->torneo_id ? 'Torneo actualizado correctamente.' : 'Torneo creado correctamente.');

            $this->resetInputFields();
            return redirect()->route('categorias-torneo', ['id' => $this->torneo_id]);
        }
    }

    public function updatedSelectedCategories()
    {
        $torneoId = $this->torneo_id;

        $categoriasGuardadas = CategoriaTorneo::where('torneo_id', $torneoId)->pluck('categoria_id')->toArray();
        $eliminadas = array_diff($categoriasGuardadas, $this->selectedCategories);

        foreach ($eliminadas as $categoriaId) {
            CategoriaTorneo::where('torneo_id', $torneoId)
                ->where('categoria_id', $categoriaId)
                ->delete();
        }

        $this->guardarCategoriasSeleccionadas();
    }

    public function guardarCategoriasSeleccionadas()
    {
        $torneoId = $this->torneo_id;

        $order_position = 0;
        foreach ($this->selectedCategories as $categoriaId) {
            $categoriaTorneo = CategoriaTorneo::updateOrCreate(
                [
                    'torneo_id' => $torneoId,
                    'categoria_id' => $categoriaId,
                ],
                [
                    'order_position' => $order_position++,
                ]
            );
        }

        $categoriasSeleccionadas = Categoria::whereIn('id', $this->selectedCategories)
                                            ->with('forma')
                                            ->get();

        $fusiones = Fusion::where('torneo_id', $torneoId)->get();

        $datosCombinados = [];

        foreach ($categoriasSeleccionadas as $categoria) {
            $datosCombinados[] = [
                'tipo' => 'categoria',
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'division' => $categoria->division,
                'forma' => $categoria->forma->nombre ?? '',
                'area' => $this->areas[$categoria->id] ?? '',
                'horario' => $this->horarios[$categoria->id] ?? '',
            ];
        }

        foreach ($fusiones as $fusion) {
            $datosCombinados[] = [
                'tipo' => 'fusion',
                'id' => $fusion->id,
                'nombre' => $fusion->nombre,
                'division' => $fusion->division,
                'forma' => '',
                'area' => $fusion->area ?? '',
                'horario' => $fusion->horario ?? '',
            ];
        }

        $this->datosCombinados = $datosCombinados;
    }

    public function deseleccionarCategoria($categoriaId, $tipo)
    {
        $this->datosCombinados = array_filter($this->datosCombinados, function($item) use ($categoriaId, $tipo) {
            return !($item['id'] === $categoriaId && $item['tipo'] === $tipo);
        });
    
        if ($tipo === 'categoria') {
            $this->selectedCategories = array_diff($this->selectedCategories, [$categoriaId]);
        }
    
        if ($tipo === 'fusion') {
            Fusion::where('id', $categoriaId)->where('torneo_id', $this->torneo_id)->delete();
        }
    
        $this->datosCombinados = array_values($this->datosCombinados);
        $this->selectedCategories = array_values($this->selectedCategories);
    }

    public function mostrarBotonConfirmacion()
    {
        $this->mostrarBotonConfirmar = true;
    }

    public function mostrarBotonCombinacionHorarios()
    {
        $this->mostrarBotonCombinacionAreas = true;
    }

    public function buttonAction()
    {
        if (!empty($this->seleccionarCategoriasCombinadas)) {
            $this->combinarCategorias();
        }
    }
    
    public function combinarCategorias()
    {
        $torneoId = $this->torneo_id;

        $divisionesSeleccionadas = Categoria::whereIn('id', $this->seleccionarCategoriasCombinadas)->pluck('division');

        $categoriaYaCombinada = false;

        $fusiones = Fusion::where('torneo_id', $torneoId)->get();
        
        foreach ($fusiones as $fusion) {
            $divisionesFusion = explode(' / ', $fusion->division); 
            foreach ($divisionesSeleccionadas as $divisionSeleccionada) {
                if (in_array($divisionSeleccionada, $divisionesFusion)) {
                    $categoriaYaCombinada = true;
                    break 2; 
                }
            }
        }

        if ($categoriaYaCombinada) {
            flash()->options(['position' => 'top-center'])
                ->addError('Una o más de las categorías seleccionadas ya han sido combinadas en este torneo.');
            return;
        }

        $categoriasSeleccionadas = Categoria::whereIn('id', $this->seleccionarCategoriasCombinadas)->with('cintas')->get();

        $nombreFusion = $categoriasSeleccionadas->first()->nombre;
        $cintasFusion = $categoriasSeleccionadas->pluck('cintas')->flatten()->pluck('cinta')->unique()->implode(', ');
        $pesoMinimo = $categoriasSeleccionadas->min('peso_minimo');
        $pesoMaximo = $categoriasSeleccionadas->max('peso_maximo');
        $edadMinima = $categoriasSeleccionadas->min('edad_minima');
        $edadMaxima = $categoriasSeleccionadas->max('edad_maxima');
        $divisiones = $categoriasSeleccionadas->pluck('division')->implode(' / ');
        $generos = $categoriasSeleccionadas->pluck('genero')->unique();
        $genero = $generos->count() > 1 ? 'mixto' : $generos->first();

        $nuevaFusion = Fusion::create([
            'nombre' => $nombreFusion,
            'descripcion' => 'Fusión creada el ' . now()->toDateString(),
            'genero' => $genero,
            'peso_minimo' => $pesoMinimo,
            'peso_maximo' => $pesoMaximo,
            'edad_minima' => $edadMinima,
            'edad_maxima' => $edadMaxima,
            'division' => $divisiones,
            'cinta' => $cintasFusion,
            'torneo_id' => $torneoId,
        ]);

        // Guardar en la tabla intermedia
        $nuevaFusion->categorias()->attach($categoriasSeleccionadas->pluck('id'));

        $this->seleccionarCategoriasCombinadas = [];

        flash()->options(['position' => 'top-center'])
            ->addSuccess('', 'Categorías combinadas con éxito.');

            $fusiones = Fusion::where('torneo_id', $this->torneo_id)->with('categorias')->get();

        foreach ($fusiones as $fusion) {
            foreach ($fusion->categorias as $categoria) {
                $this->categoriasYaFusionadas[$categoria->id] = $fusion->division;
            }
        }

        $this->secciones = Seccion::with('formas.categorias')->get();
    }

    public function aplicarAreaHorarioCompartidos()
    {
        $torneoId = $this->torneo_id;
    
        foreach ($this->combinacionareasHorarios as $idSeleccionado) {
            $this->areas[$idSeleccionado] = $this->areaCompartida;
            $this->horarios[$idSeleccionado] = $this->horarioCompartido;
        }
    
        /* dd($this->combinacionareasHorarios); */
        foreach ($this->combinacionareasHorarios as $categoriaId) {
            $categoriaTorneo = CategoriaTorneo::where('torneo_id', $torneoId)
                                                ->where('categoria_id', $categoriaId)
                                                ->first();
            if ($categoriaTorneo) {
                $categoriaTorneo->area = $this->areas[$categoriaId];
                $categoriaTorneo->horario = $this->horarios[$categoriaId];
                $categoriaTorneo->save();
                $this->areas[$categoriaId] = $categoriaTorneo->area;
                $this->horarios[$categoriaId] = $categoriaTorneo->horario;
                $this->mostrarBotonCombinacionAreas = false;
                $this->areaCompartida = '';
                $this->horarioCompartido = '';
                $this->combinacionareasHorarios = [];
            }
        }

        $fusiones = Fusion::where('torneo_id', $this->torneo_id)->get();
            foreach ($fusiones as $fusion) {
                /* dd($fusion); */
                if (isset($this->areas[$fusion->id])) {
                    $fusion->area = $this->areas[$fusion->id];
                }
            
                if (isset($this->horarios[$fusion->id])) {
                    $fusion->horario = $this->horarios[$fusion->id];
                }
                $fusion->save();
                $this->areas[$fusion->id] = $fusion->area;
                $this->horarios[$fusion->id] = $fusion->horario;
            }
    
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Se ha combinado correctamente las áreas y horarios.');
    }
}
