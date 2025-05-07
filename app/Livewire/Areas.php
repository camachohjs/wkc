<?php

namespace App\Livewire;

use App\Models\CategoriaTorneo;
use App\Models\Combate;
use App\Models\RegistroTorneo;
use App\Models\ResultadosTorneo;
use App\Models\Torneo;
use App\Models\TorneoUser;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class Areas extends Component
{
    public $torneoId;
    public $fechaId;
    public $categoriaId;
    public $nombreTorneo;
    public $fechaAreas;
    public $areasFecha = [];
    public $fechaTorneoSeleccion;
    public $botonActivo;
    public $infoTorneo = [];
    public $seleccionArea = FALSE; 
    public $areaId = null;
    public $participantes = [];
    public $partidos = [];
    public $mostrarRondas = false;
    public $rondasCompletadas = [];
    public $areas = [];

    // Vista Categorias
    public $areaSeleccionada = [];
    public $categoriasArea = [];

    #[Layout('components.layouts.combates')]

    public function mount($torneoId, $fechaId, $areaId = null)
    {
        $this->areaId = $areaId;
        $this->deshacerFusiones($torneoId);
        // Buscamos el nombre del Torneo
        $this->nombreTorneo = Torneo::findOrFail($torneoId)->nombre;
        // Obtenemos los Dias, Areas y Categorias de los torneos
        $this->infoTorneo = $this->obtenerInfoTorneo($torneoId);
        // Asignamos por default como todos, para mostrar todos los dias del terno
        $this->botonActivo = $fechaId;

        if ($fechaId != 'todas') {
            $this->obtenerAreasTorneoFecha($fechaId);
            $this->fechaId = $fechaId;
        }
        $this->areas = range(1, 8);
    }

    protected $listeners = ['combateFinalizado' => 'handleCombateFinalizado'];

    public function handleCombateFinalizado($combateId)
    {
        $combate = Combate::find($combateId);

        $todosFinalizados = Combate::where('torneo_id', $combate->torneo_id)
            ->where('categoria_id', $combate->categoria_id)
            ->where('ronda', $combate->ronda)
            ->where('estado', '!=', 'terminada')
            ->count() == 0;

        if ($todosFinalizados) {
            $this->generarRondaSiguiente($combate->categoria_id, $combate->ronda);
        }
    }

    public function finalizarCombate($combateId, $ganadorId)
    {
        $combate = Combate::find($combateId);
        $combate->ganador_id = $ganadorId;
        $combate->estado = 'terminada';
        $combate->save();

        $this->dispatch('combateFinalizado', $combateId);
    }

    /**
     * Obtiene las fechas del torneo seleccionado.
     *
     * @param int $torneoId El ID del torneo del cual obtener las fechas.
     * @return array Un array de fechas del torneo, ordenadas de menor a mayor.
     */
    public function obtenerFechasTorneo($torneoId)
    {
        $torneoFechas = CategoriaTorneo::where('torneo_id', $torneoId)
            ->pluck('horario')
            ->map(function ($fecha) {
                return substr($fecha, 0, 10); // Obtener solo la parte de la fecha (primeros 10 caracteres)
            })
            ->unique()
            ->toArray();

        sort($torneoFechas);
        $torneoFechasReIndex = array_values($torneoFechas);

        return $torneoFechasReIndex;
    }

    /**
     * Obtiene las áreas asignadas de acuerdo al toneo y fecha especifica.
     *
     * @param int $torneoId El ID del torneo del cual obtener las áreas.
     * @param string $fecha La fecha para la cual se desean obtener las áreas (en formato YYYY-MM-DD).
     * @return array Un array de áreas del torneo para la fecha dada, ordenadas de menor a mayor.
     */
    public function obtenerAreasFechaTorneo($torneoId, $fecha)
    {
        $areasTorneo = CategoriaTorneo::where('torneo_id', $torneoId)
            ->whereDate('horario', $fecha)
            ->pluck('area')
            ->unique()
            ->toArray();

        sort($areasTorneo);

        return $areasTorneo;
    }

    /**
     * Obtiene las categorías asignadas a un área específica de un torneo para una fecha determinada.
     *
     * @param int $torneoId El ID del torneo del cual obtener las categorías.
     * @param string $fecha La fecha para la cual se desean obtener las categorías (en formato YYYY-MM-DD).
     * @param int $area El ID del área para la cual se desean obtener las categorías.
     * @return array Un array de IDs de categorías del área especificada para el torneo y fecha dados.
     */
    public function obtenerCategoriasArea($torneoId, $fecha, $area)
    {
        $categoriasArea = CategoriaTorneo::where('torneo_id', $torneoId)
            ->whereDate('horario', $fecha)
            ->where('area', $area)
            ->get()
            ->toArray();

        return $categoriasArea;
    }

    public function deshacerFusiones($torneoId)
    {
        $fusiones = \App\Models\Fusion::where('torneo_id', $torneoId)->with('categorias')->get();

        // Para calcular correctamente el orden sin duplicar
        $categoriasExistentes = \App\Models\CategoriaTorneo::where('torneo_id', $torneoId)->pluck('categoria_id')->toArray();
        $maxOrder = \App\Models\CategoriaTorneo::where('torneo_id', $torneoId)->max('order_position') ?? 0;
        $order_position = $maxOrder + 1;

        foreach ($fusiones as $fusion) {
            foreach ($fusion->categorias as $categoria) {
                // Si la categoría ya existe en categoria_torneo, no la insertamos de nuevo
                if (in_array($categoria->id, $categoriasExistentes)) {
                    continue;
                }

                \App\Models\CategoriaTorneo::create([
                    'torneo_id' => $torneoId,
                    'categoria_id' => $categoria->id,
                    'area' => $fusion->area,
                    'horario' => $fusion->horario,
                    'order_position' => $order_position++
                ]);
            }
        }

    }

    /**
     * Obtiene la información completa de un torneo, incluyendo fechas, áreas y categorías.
     *
     * @param int $torneoId El ID del torneo del cual obtener la información.
     * @return array Un array con la información completa del torneo, incluyendo fechas, áreas y categorías.
     */
    public function obtenerInfoTorneo($torneoId)
    {
        // Obtenemos las fechas disponibles para el torneo
        $fechasTorneo = $this->obtenerFechasTorneo($torneoId);

        // Inicializamos un array para almacenar la información del torneo
        $infoTorneo = [];

        // Iteramos sobre cada fecha del torneo
        foreach ($fechasTorneo as $fecha) {

            // Obtenemos las áreas disponibles para la fecha actual
            $areasTorneo = $this->obtenerAreasFechaTorneo($torneoId, $fecha);

            // Inicializamos un array para almacenar las áreas y categorías de la fecha actual
            $areasCategoriasTorneo = [];

            // Iteramos sobre cada área de la fecha actual
            foreach ($areasTorneo as $area) {

                // Obtenemos las categorías disponibles para el área y fecha actual
                $categoriasArea = $this->obtenerCategoriasArea($torneoId, $fecha, $area);

                $nombresCategorias = [];

                foreach ($categoriasArea as $categoria) {

                    $nombreCategoria = $this->obtenerCategoria($categoria['categoria_id']);

                    $inscirtosCategoriaTorneo = $this->obtenerInscritosCategoriaTorneo($torneoId, $categoria['categoria_id']);

                    $nombresCategorias[] = [
                        'categoria_id' => $categoria['categoria_id'],
                        'division_categoria' => $nombreCategoria->division,
                        'nombre_categoria' => $nombreCategoria->nombre,
                        'horario_categoria' => Carbon::createFromFormat('Y-m-d H:i:s', $categoria['horario'])->format('H:i'),
                        'inscritos' => $inscirtosCategoriaTorneo,
                        'order_position' => $categoria['order_position']
                    ];
                }

                usort($nombresCategorias, function($a, $b) {
                    return $a['order_position'] - $b['order_position'];
                });

                // Agregamos el área y sus categorías al array de áreas y categorías de la fecha actual
                $areasCategoriasTorneo[] = [
                    'area' => $area,
                    'categorias' => $nombresCategorias,
                ];
            }

            // Agregamos la fecha y sus áreas con categorías al array de información del torneo
            $infoTorneo[] = [
                'fecha' => $fecha,
                'areas' => $areasCategoriasTorneo
            ];
        }

        // Devolvemos el array con la información completa del torneo
        return $infoTorneo;
    }

    /**
     * Actualiza la información de las áreas del torneo basada en la fecha seleccionada.
     *
     * @param mixed $dia La clave del array de información del torneo que indica la fecha seleccionada, o 'todos' si se seleccionan todas las fechas.
     * @return array La información de las áreas del torneo para la fecha seleccionada, o toda la información del torneo si se seleccionan todas las fechas.
     */
    public function obtenerAreasTorneoFecha($dia)
    {
        $this->botonActivo = $dia;

        // dd($dia);

        if ($dia != 'todas') {


            $this->fechaAreas = $dia;
            $this->fechaTorneoSeleccion = $this->infoTorneo[$dia]['fecha'];
            return $this->areasFecha = $this->infoTorneo[$dia]['areas'];
        }

        return $this->infoTorneo;
    }

    /**
     * Navega a través de las fechas del menú y actualiza las áreas del torneo según la fecha seleccionada.
     *
     * @param mixed $dia La clave del array de información del torneo que indica la fecha seleccionada.
     */
    public function navegarFechasMenu($fechaId, $torneoId, $areaId = null)
    {
        // Actualiza las áreas del torneo basadas en la fecha seleccionada
        $this->obtenerAreasTorneoFecha($fechaId);

        // Desactiva u oculta la vista de seleccion de area
        $this->seleccionArea = FALSE;

        // Redirige a la ruta con el área si está presente
        if(auth()->user()->hasRole('torneo user')){
            if ($areaId) {
                return redirect()->route('areas-categorias', ['torneoId' => $torneoId, 'fechaId' => $fechaId, 'areaId' => $areaId]);
            }
        } 

        return redirect()->route('areas', ['torneoId' => $torneoId, 'fechaId' => $fechaId]);
    }

    /**
     * Muestra las categorías disponibles para un área seleccionada en una fecha determinada del torneo.
     *
     * @param mixed $dia La clave del array de información del torneo que indica la fecha seleccionada.
     * @param mixed $keyArea La clave del array que identifica el área seleccionada.
     * @param array $areaSeleccionada El array que contiene la información del área seleccionada.
     * @return array El array de categorías disponibles para el área seleccionada.
     */
    public function mostrarCategoriasArea($torneoId, $dia, $keyArea)
    {
        return redirect()->route('areas-categorias', ['torneoId' => $torneoId, 'fechaId' => $dia, 'areaId' => $keyArea]);
    }

    /**
     * Muestra las categorías disponibles para un área seleccionada en una fecha determinada del torneo.
     *
     * @param mixed $dia La clave del array de información del torneo que indica la fecha seleccionada.
     * @param mixed $keyArea La clave del array que identifica el área seleccionada.
     * @param array $areaSeleccionada El array que contiene la información del área seleccionada.
     * @return array El array de categorías disponibles para el área seleccionada.
     */
    public function mostrarCompetidoresArea($torneoId, $dia, $keyArea, $categoriaId)
    {
        return redirect()->route('areas-categorias-divisiones', ['torneoId' => $torneoId, 'fechaId' => $dia, 'areaId' => $keyArea, 'categoriaId' => $categoriaId]);
    }

    public function obtenerCategoria($categoriaId)
    {
        return $categoria = DB::table('categorias')->find($categoriaId);
    }

    
    public function mostrarInscritos($torneoId, $dia, $keyArea, $categoriaId)
    {
        $categoriaKata = CategoriaTorneo::with(['categorias', 'categorias.forma'])
            ->where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->get();

        foreach ($categoriaKata as $categoriaTorneo) {
            $categoria = $categoriaTorneo->categorias;
            $formaNombre = strtolower($categoria->forma->nombre ?? '');

            if (str_contains($formaNombre, 'combate')) {
                return redirect()->route('areas-categorias-divisiones', [
                    'torneoId' => $torneoId,
                    'fechaId' => $dia,
                    'areaId' => $keyArea, 
                    'categoriaId' => $categoriaId
                ]);
            } else {
                return redirect()->route('areas-categorias-katas', [
                    'torneoId' => $torneoId,
                    'fechaId' => $dia,
                    'areaId' => $keyArea, 
                    'categoriaId' => $categoriaId
                ]);
            }
        }
    }

    public function obtenerInscritosCategoriaTorneo($torneoId, $categoriaId)
    {
        $inscritosCategoriaTorneo = RegistroTorneo::with(['alumno.escuelas', 'maestro.escuelas'])
            ->where('torneo_id', $torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('check_pago', 1)
            ->whereNull('deleted_at')
            ->get();

        return $inscritosCategoriaTorneo;
    }

    /**
     * Formatea una fecha dada en el formato "Nombre del Día de la Semana, Día de mes + Mes".
     *
     * @param string $fecha La fecha a formatear en formato 'Y-m-d'.
     * @return string La fecha formateada en el formato "Nombre del Día de la Semana, Día de mes + Mes".
     */
    public function formatearFecha($fecha)
    {

        try {
            $fechaOriginal = Carbon::createFromFormat('Y-m-d', $fecha);
    
            $diaNombre = ucfirst($fechaOriginal->isoFormat('dddd'));
            $diaNumero = $fechaOriginal->day;
            $mes = ucfirst($fechaOriginal->isoFormat('MMMM'));
    
            $fechaFormateada = $diaNombre . ', ' . $diaNumero . ' de ' . $mes;
    
            return $fechaFormateada;
        } catch (\Exception $e) {
             //se modifico la funcion con un try catch para cuando las categorias no tiene horario y/o area 
        }
      
    }

    public function generarEmparejamientosParaTorneo()
    {

        // Obtener todas las categorías del torneo
        $categorias = CategoriaTorneo::with(['categorias', 'categorias.forma'])
        ->where('torneo_id', $this->torneoId)->get();

        foreach ($categorias as $categoria) {
            $idForma = $categoria->categorias->forma->id;
            if (in_array($idForma, [12, 13, 14, 15])) {
                $existeCombate = Combate::where('torneo_id', $this->torneoId)
                    ->where('categoria_id', $categoria->categoria_id)
                    ->where('ronda', 1)
                    ->exists();

                if (!$existeCombate) {
                    $this->mostrarRondas = true;
                    $participantes = $this->obtenerInscritosCategoriaTorneo($this->torneoId, $categoria->categoria_id);

                    // Filtrar competidores únicos
                    $competidores = [];
                    foreach ($participantes as $participante) {
                        if (!in_array($participante->id, array_column($competidores, 'id'))) {
                            $competidores[] = $participante;
                        }
                    }

                    $numeroDeCompetidores = count($competidores);

                    if ($numeroDeCompetidores == 1) {
                        // Si solo hay un participante, es el ganador por defecto
                        $ganadorId = $competidores[0]->id;
                        Combate::create([
                            'participante1_id' => $ganadorId,
                            'participante2_id' => null,
                            'torneo_id' => $this->torneoId,
                            'categoria_id' => $categoria->categoria_id,
                            'ronda' => 1,
                            'estado' => 'terminada',
                            'ganador_id' => $ganadorId
                        ]);
                        
                        DB::table('categoria_torneo')
                        ->where('torneo_id', $this->torneoId)
                        ->where('categoria_id', $categoria->categoria_id)
                        ->update(['ganador_id' => $ganadorId]);

                        DB::table('resultados_torneo')->insert([
                            'torneo_id' => $this->torneoId,
                            'categoria_id' => $categoria->categoria_id,
                            'participante_id' => $ganadorId,
                            'posicion' => 1,
                        ]);

                        continue;
                    }

                    // Calcular "Byes"
                    $byes = $this->calcularByes($numeroDeCompetidores);

                    // Generar emparejamientos con "Byes" solo para la primera ronda
                    $emparejamientos = $this->generarEmparejamientos($competidores, $byes);

                    if (isset($emparejamientos[0]) && is_array($emparejamientos[0])) {
                        foreach ($emparejamientos[0] as $partido) {
                            $combate = Combate::create([
                                'participante1_id' => $partido['participante1']->id ?? null,
                                'participante2_id' => $partido['participante2']->id ?? null,
                                'torneo_id' => $this->torneoId,
                                'categoria_id' => $categoria->categoria_id,
                                'ronda' => 1,
                                'estado' => 'pendiente',
                            ]);

                            // Si el participante 2 es nulo, el participante 1 gana automáticamente
                            if (is_null($partido['participante2'])) {
                                $combate->ganador_id = $partido['participante1']->id;
                                $combate->estado = 'terminada';
                                $combate->save();
                            }
                        }
                    }
                } else {
                    // Cargar los combates existentes en lugar de crear nuevos
                    $this->partidos[$categoria->id] = Combate::where('torneo_id', $this->torneoId)
                        ->where('categoria_id', $categoria->categoria_id)
                        ->get()
                        ->groupBy('ronda');
                }
            }
        }
    }

    public function generarEmparejamientos($competidores, $byes)
    {
        shuffle($competidores);
        $partidos = [];
        $total = count($competidores);

        // Distribuir los "byes"
        for ($i = 0; $i < $byes; $i++) {
            array_push($competidores, null);
        }

        // Actualizar el total después de distribuir los "byes"
        $total = count($competidores);
        
        $totalRondas = $this->calcularNumeroDeRondas($total);
        /* dd($byes, $total, $totalRondas, $competidores); */

        // Crear todas las rondas de emparejamientos
        for ($ronda = 0; $ronda < 4; $ronda++) {
            shuffle($competidores);
            /* dd($competidores); */
            $partidos[$ronda] = [];
            $nuevoCompetidores = [];

            
            // Verificar y reorganizar si hay combates null vs null
            while ($this->tieneCombatesInvalidos($competidores)) {
                shuffle($competidores);
            }

            for ($i = 0; $i < $total; $i += 2) {
                $participante1 = $competidores[$i];
                $participante2 = $competidores[$i + 1] ?? null;

                // Mover null a participante2 si participante1 es null
                if (is_null($participante1) && !is_null($participante2)) {
                    $participante1 = $competidores[$i + 1];
                    $participante2 = $competidores[$i];
                }

                // Evitar emparejamientos de null vs null
                if (is_null($participante1) && is_null($participante2)) {
                    continue;
                }

                /* dd($partidos[$ronda], 'hola'); */

                // Verificar si los dos participantes son de la misma escuela
                if ($participante1 && $participante2) {
                    $escuelaParticipante1 = $participante1->alumno ? optional($participante1->alumno->escuelas->first())->nombre : optional($participante1->maestro->escuelas->first())->nombre;
                    $escuelaParticipante2 = $participante2->alumno ? optional($participante2->alumno->escuelas->first())->nombre : optional($participante2->maestro->escuelas->first())->nombre;

                    /* dd($ronda); */
                    if ($ronda == 0 && $escuelaParticipante1 === $escuelaParticipante2) {
                        // Buscar un participante diferente
                        for ($j = $i + 2; $j < $total; $j++) {
                            if ($competidores[$j]) {
                                $escuelaOtroParticipante = $competidores[$j]->alumno ? optional($competidores[$j]->alumno->escuelas->first())->nombre : optional($competidores[$j]->maestro->escuelas->first())->nombre;
                                if ($escuelaOtroParticipante !== $escuelaParticipante1) {
                                    // Intercambiar participantes
                                    $temp = $competidores[$i + 1];
                                    $competidores[$i + 1] = $competidores[$j];
                                    $competidores[$j] = $temp;
                                    $participante2 = $competidores[$i + 1];
                                    break;
                                }
                            }
                        }
                    }
                }

                $partidos[$ronda][] = [
                    'participante1' => $participante1,
                    'participante2' => $participante2,
                    'id' => $i
                ];

                if ($participante1 && !is_null($participante2)) {
                    $nuevoCompetidores[] = $participante1; // Los ganadores hipotéticos pasan a la siguiente ronda
                }

                // Si hay un "bye", el participante 1 gana automáticamente
                if ($participante2 === null && $participante1 !== null) {
                    $nuevoCompetidores[] = $participante1;
                }
            }

            // Preparar los competidores para la siguiente ronda
            $competidores = $nuevoCompetidores;
            $total = count($competidores);
        }

        return $partidos;
    }

    private function tieneCombatesInvalidos($competidores)
    {
        for ($i = 0; $i < count($competidores); $i += 2) {
            $participante1 = $competidores[$i];
            $participante2 = $competidores[$i + 1] ?? null;

            if (is_null($participante1) && is_null($participante2)) {
                return true;
            }
        }
        return false;
    }

    public function calcularByes($numeroDeCompetidores)
    {
        $byes = [
            1 => 0, 2 => 0, 3 => 1, 4 => 0, 5 => 3, 6 => 2, 7 => 1, 8 => 0, 9 => 7, 10 => 6, 11 => 5, 12 => 4, 13 => 3, 14 => 2, 15 => 1, 16 => 0, 17 => 15, 18 => 14, 19 => 13, 20 => 12, 21 => 11, 22 => 10, 23 => 9, 24 => 8, 25 => 7, 26 => 6, 27 => 5, 28 => 4, 29 => 3, 30 => 2, 31 => 1, 32 => 0, 33 => 31, 34 => 30, 35 => 29, 36 => 28, 37 => 27, 38 => 26, 39 => 25, 40 => 24, 41 => 23, 42 => 22, 43 => 21, 44 => 20, 45 => 19, 46 => 18, 47 => 17, 48 => 16, 49 => 15, 50 => 14, 51 => 13, 52 => 12, 53 => 11, 54 => 10, 55 => 9, 56 => 8, 57 => 7, 58 => 6, 59 => 5, 60 => 4, 61 => 3, 62 => 2, 63 => 1, 64 => 0, 65 => 63, 66 => 62, 67 => 61, 68 => 60, 69 => 59, 70 => 58, 71 => 57, 72 => 56, 73 => 55, 74 => 54, 75 => 53, 76 => 52, 77 => 51, 78 => 50, 79 => 49, 80 => 48, 81 => 47, 82 => 46, 83 => 45, 84 => 44, 85 => 43, 86 => 42, 87 => 41, 88 => 40, 89 => 39, 90 => 38, 91 => 37, 92 => 36, 93 => 35, 94 => 34, 95 => 33, 96 => 32, 97 => 31, 98 => 30, 99 => 29, 100 => 28
        ];

        return $byes[$numeroDeCompetidores] ?? 0;
    }

    public function calcularNumeroDeRondas($numeroDeCompetidores)
    {
        return ceil(log($numeroDeCompetidores, 2));
    }

    public function generarRondaSiguiente($categoriaId, $rondaActual)
    {
        $combates = Combate::where('torneo_id', $this->torneoId)
            ->where('categoria_id', $categoriaId)
            ->where('ronda', $rondaActual)
            ->where('estado', 'terminada')
            ->get();

        if ($combates->count() < 2) {
            return;
        }

        $ganadores = $combates->map(function ($combate) {
            return RegistroTorneo::find($combate->ganador_id);
        })->toArray();

        $perdedores = $combates->filter(function ($combate) {
            return !is_null($combate->ganador_id);
        })->map(function ($combate) {
            return RegistroTorneo::find($combate->participante1_id === $combate->ganador_id ? $combate->participante2_id : $combate->participante1_id);
        })->toArray();

        $byes = $this->calcularByes(count($ganadores));
        $totalRondas = $this->calcularNumeroDeRondas(count($ganadores));
        $emparejamientos = $this->generarEmparejamientos($ganadores, $byes);

        foreach ($emparejamientos[0] as $partido) {
            $combate = Combate::create([
                'participante1_id' => $partido['participante1']->id ?? null,
                'participante2_id' => $partido['participante2']->id ?? null,
                'torneo_id' => $this->torneoId,
                'categoria_id' => $categoriaId,
                'ronda' => $rondaActual + 1,
                'estado' => 'pendiente',
            ]);

            if (is_null($partido['participante2'])) {
                $combate->ganador_id = $partido['participante1']->id;
                $combate->estado = 'terminada';
                $combate->save();
            }
        }

       /*  dd($rondaActual); */
        // Crear combate por el tercer lugar
        if ($rondaActual + 1 == $totalRondas) {
            $this->crearCombateTercerLugar($categoriaId, $perdedores);
        }
    }

    public function crearCombateTercerLugar($categoriaId, $perdedores)
    {
        if (count($perdedores) == 2) {
            Combate::create([
                'participante1_id' => $perdedores[0]->id,
                'participante2_id' => $perdedores[1]->id,
                'torneo_id' => $this->torneoId,
                'categoria_id' => $categoriaId,
                'ronda' => $this->calcularNumeroDeRondas(count($perdedores)) + 1,
                'estado' => 'pendiente',
            ]);
        }
    }

    public function render()
    {
        $this->partidos = Combate::with(['participante1', 'participante2'])
            ->where('torneo_id', $this->torneoId)
            ->get();

        $conteo = count($this->partidos);

            /* dd($conteo); */
            if ($conteo > 0){
                $this->mostrarRondas = true;
            }

        return view('livewire.areas');
    }

    public function reiniciarEmparejamientosTorneo() {
        
        $combate_categoria = Combate::where('torneo_id', $this->torneoId)->get();
        foreach ($combate_categoria as $combate) {
            $combate->delete();
        }

        $resultados = ResultadosTorneo::where('torneo_id', $this->torneoId)->get();
        foreach ($resultados as $resultado) {
            $resultado->delete();
        }

        CategoriaTorneo::where('torneo_id', $this->torneoId)->update(['ganador_id' => NULL]);

        $this->mostrarRondas = false;
        flash()->options([
            'position' => 'top-center',
        ])->addSuccess('', 'Comabates reiniciados correctamente.');
        
        return redirect()->back();
    }

    public function refrescar() {
        $this->dispatch('refrescarPagina');
    }
}