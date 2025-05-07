<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">🏆 {{ $nombreTorneo }}</h2>
        </div>
    </div>

    <style>
        /* Tamaño Pagina */

        .body-wrapper>.container-fluid {
            max-width: none !important;
        }

        .card-body1 {
            overflow-y: auto;
            height: 100%;
        }

        .card-body {
            padding: 15px;
        }

        .btn.card-area {
            background-color: transparent;
            border: none;
            box-shadow: none;
            color: inherit;
            width: 100%;
            height: 100%;
            padding: 0;
        }

        .btn.card-seleccionado {
            width: 100%;
            height: 100%;
            margin-bottom: 20px;
            padding: 0;
            border-radius: 0.25rem;
        }

        .btn.card-area:hover {
            background-color: #f8f9fa;
            color: #333;
        }

        /* Botones Menu Fechas */
        .buttons-date {
            border: 2px solid #ebc010;
            border-radius: 8px;
        }

        .btn-option-date {
            background: #090808;
            border: none;
            box-shadow: none;
            color: #EAEAEA;
        }

        .btn-option-date.active:hover {
            color: #090808;
        }

        .btn-option-date:hover {
            color: #EBC010;
        }

        .btn-option-date.active {
            background: #EBC010;
        }

        .btn-option-date:active {
            background: #EBC010 !important;
            color: #090808;
        }

        /* Boton Areas */

        .btn.card-area {
            border: 1px solid #EBC010;
            background: #090808;
            color: #EAEAEA;
        }

        .btn.card-area:hover {
            background: #090808;
            color: #EBC010;
        }

        .btn.card-area:active {
            background: #090808;
            color: #EBC010;
        }

        .btn.card-seleccionado {
            border: 1px solid #ffffff;
            background: #EBC010;
            color: #090808;
        }

        .btn.card-seleccionado:hover {
            background: #090808;
            color: #EBC010;
        }

        .btn.card-seleccionado:active {
            background: #090808;
            color: #EBC010;
        }

        .card-seleccionado {
            border: none;
            background: #EBC010;
            color: #090808;
            border-radius: 10px;
        }


        /* Categorias Card */
        .card.card-categories {
            background: #0E0D0D;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        ul {
            padding-left: 0;
            list-style-type: none;
        }

        li {
            padding: 10px;
        }

        .card.card-categories:hover {
            transform: none;
            z-index: unset;
            border: none;
        }

        .card.card-category {
            border: none;
        }

        .card.card-category:hover {
            transform: none;
            z-index: unset;
            border: none;
        }

        .btn-invisible {
            background-color: transparent;
            border: none;
            padding: 0;
            color: inherit;
            width: 100%;
            box-shadow: none;
        }

        .torneo {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .ronda {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .partido-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .partido {
            border: solid 0.5px #ebc010;
            padding: 10px;
            margin: 10px;
            width: 200px;
            text-align: center;
        }

        .ganador {
            font-weight: bold;
            color: green;
        }

        .vs {
            margin: 5px 0;
            color: white;
            border-radius: 5px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: auto;
        }

        .line {
            width: 2px;
            background: #ccc;
            height: 20px;
            margin: -5px 0;
        }

        .participante {
            margin: 15px 1px;
            background-color: #444;
            color: white;
            border: 2px solid #EBC010;
            border-radius: 5px;
            text-align: center;
        }

        .participante {
            background: linear-gradient(145deg, #1c1c1c, #000000);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .participante:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px #EBC010;
        }

        .ganador {
            color: #00FF00;
            border: 2px solid #00FF00;
            box-shadow: 0 0 10px #00FF00;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .ganador:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px #00FF00;
        }

        .btn-generar {
            background: #0E0D0D;
            border: 2px solid #FFFFFF;
            color: #FFFFFF;
        }

        .btn-generar:hover {
            color: #EBC010;
            border: 2px solid #EBC010;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.354);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            align-content: center;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(22, 22, 22, 0.67);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loader {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .box-1 {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 150px;
            width: 150px;
            background-color: #242424;
            background-image: linear-gradient(135deg, #bd9e1f 0%, #ebc010 34%, #242424 100%);
            border-radius: 50%;
            animation: rotate 3s linear infinite;
            box-shadow: 0px -5px 20px 0px #ebc010, 0px 5px 20px 0px #242424;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .box-1::before {
            content: '';
            position: absolute;
            inset: 15px;
            background: #242424;
            border-radius: 50%;
            box-shadow: 0px -5px 20px 0px #ebc010, 0px 5px 20px 0px #242424;
        }

        .loader span {
            position: absolute;
            margin-top: 20px;
            color: #ebc010;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            letter-spacing: 2px;
            font-weight: 800;
            font-size: 1rem;
            animation: text-animate412 3s linear infinite;
        }

        @keyframes text-animate412 {
            0% {
                opacity: 1;
            }

            10% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        .ausente {
            opacity: 0.5;
            /* background-color: #ffcccc; */
        }

        .shuffle ul {
            padding-left: 0;
            list-style-type: none;
            position: relative;
        }

        .shuffle li {
            width: 100px;
            line-height: 2em;
            text-align: center;
            transition: all 0.9s ease-out;
        }

        .shuffle-animation {
            transition: transform 0.3s ease-in-out;
        }
    </style>

    <div class="row">
        <div class="col-8">
            <div class="buttons-section d-flex mt-4">
                <div class="buttons-date d-flex">
                    <button type="button" class="btn btn-option-date {{ $botonActivo === 'todas' ? 'active' : '' }}"
                        wire:click="navegarFechasMenu('todas', {{ $torneoId }})">Todos</button>

                    @foreach ($infoTorneo as $key => $dias)
                        <button type="button" class="btn btn-option-date {{ $botonActivo == $key ? 'active' : '' }}"
                            wire:click="navegarFechasMenu('{{ $key }}', '{{ $torneoId }}')">{{ $this->formatearFecha($dias['fecha']) }}</button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-4">

        </div>
    </div>

    <div wire:loading class="loading-overlay">
        <div class="loader">
            <div class="box-1"></div>
            <span>Cargando...</span>
        </div>
    </div>

    {{-- Areas Categories --}}
    <div class="section-areas-date d-flex align-items-center justify-content-between mt-4 row">
        <div class="col-md-4">
            <h3 class="text-white">{{ $this->formatearFecha($dias['fecha']) }}</h2>
        </div>
        <div class="col-md-4 text-center">
            @if ($mostrarboton === false)
                <button class="btn btn-puntos"
                    wire:click="generarPosicionamiento({{ $categoriaSeleccionada['categoria_id'] }})"
                    wire:loading.attr="disabled">
                    <a class="agregar">
                        <i class="bi bi-shuffle"></i>
                    </a>
                </button>
            @endif
            @if ($mostrarboton === true)
                <button class="btn btn-puntos"
                    wire:click="reiniciarPosicionamiento({{ $categoriaSeleccionada['categoria_id'] }})"
                    wire:confirm="¿Estás seguro de reiniciar el orden de los participantes?">
                    <a class="agregar">
                        <i class="bi bi-shuffle"></i>
                    </a>
                </button>
            @endif
            <button type="button" class="btn btn-puntos" wire:click="refrescar">
                <a class="agregar">
                    <i class="bi bi-arrow-repeat"></i>
                </a>
            </button>
            <button type="button" class="btn btn-puntos" wire:click="abrirModalCompetidores()">
                <a class="agregar">
                    <i class="bi bi-person-fill-add"></i>
                </a>
            </button>
            <button type="button" class="btn btn-puntos" wire:click="abrirModalSplit()">
                <a class="agregar">
                    <i class="bi bi-layout-split"></i>
                </a>
            </button>
        </div>
        <div class="col-md-4">

        </div>
    </div>

    <!-- Modal para la división -->
    @if ($showModalSplit)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-labelledby="splitModalLabel"
            style="background: rgba(0,0,0,0.5);" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="splitModalLabel">Dividir Categoría</h5>
                        <button type="button" class="close btn btn-danger" wire:click="cerrarModalSplit"
                            data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex align-items-center mt-3 justify-content-around">
                            <div class="form-group">
                                <label class="form-label mb-2">Criterio de División</label>
                                <select wire:model="criterioDivision" class="form-select">
                                    <option value="manual">Manual</option>
                                    <option value="automatico">Automático</option>
                                    <option value="genero">Género</option>
                                    <option value="peso">Peso</option>
                                    <option value="edad">Edad</option>
                                    <option value="grado">Grado</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Número de Categorías</label>
                                <input type="number" wire:model.lazy="numeroCategorias"
                                    class="form-control custom-form-control" min="2">
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-dark text-center text-white">
                                        <thead>
                                            <tr>
                                                <th>Participantes</th>
                                                <th>División</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($participantes as $participante)
                                                <tr>
                                                    <td>{{ ucwords(strtolower($participante->participante['nombre'] . ' ' . $participante->participante['apellidos'])) }}
                                                    </td>
                                                    <td>
                                                        <select
                                                            wire:model="asignaciones.{{ $participante->participante['id'] }}"
                                                            class="form-select">
                                                            <option value="">Seleccione una división</option>
                                                            @for ($i = 0; $i < $numeroCategorias; $i++)
                                                                <option value="{{ $i }}">
                                                                    {{ $divisionesClaves[$i] }} /
                                                                    {{ $divisionesNombres[$i] }}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            @for ($i = 0; $i < $numeroCategorias; $i++)
                                @php
                                    $claveValue =
                                        $i === 0
                                            ? $categoriaSeleccionada['division_categoria']
                                            : $categoriaSeleccionada['division_categoria'] . ' - ' . $i;
                                    $nombreValue =
                                        $i === 0
                                            ? $categoriaSeleccionada['nombre_categoria']
                                            : $categoriaSeleccionada['nombre_categoria'] . ' - ' . $i;
                                @endphp
                                <div class="col-12 row mb-3 justify-content-center">
                                    <div class="col-6">
                                        <!-- Input para la clave de la división -->
                                        <input type="text" wire:model="divisionesClaves.{{ $i }}"
                                            class="form-control custom-form-control mb-2" value="{{ $claveValue }}"
                                            @if ($i === 0) disabled
                                                style="background-color: #0e0e0e !important; border: 1px solid #ebc010;" @endif
                                            style="border: 1px solid #ebc010;">

                                        <!-- Input para el nombre de la división -->
                                        <input type="text" wire:model="divisionesNombres.{{ $i }}"
                                            class="form-control custom-form-control" value="{{ $nombreValue }}"
                                            @if ($i === 0) disabled
                                                style="background-color: #0e0e0e !important; border: 1px solid #ebc010;" @endif
                                            style="border: 1px solid #ebc010;">
                                    </div>
                                </div>
                            @endfor
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning"
                                wire:click="guardarDivisiones">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Bootstrap -->
    @if ($showModal)
        <div class="modal fade show d-block" tabindex="-1" role="dialog" aria-labelledby="modalCompetidoresLabel"
            style="background: rgba(0,0,0,0.5);" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCompetidoresLabel">Agregar Competidor a la Categoría</h5>
                        <button type="button" class="close btn btn-danger" wire:click="cerrarModalCompetidores"
                            data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Buscador -->
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Buscar competidor..."
                                wire:model.live="search">
                        </div>

                        <!-- Tabla de competidores -->
                        <div class="table-responsive">
                            <table class="table table-striped table-dark text-center text-white" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nombre</th>
                                        <th>Nacionalidad</th>
                                        <th>Escuela</th>
                                        <th>Cinta</th>
                                        <th>Sensei</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($competidores as $competidor)
                                        <tr>
                                            <td>
                                                <img src="{{ !$competidor->foto ? asset('libs/images/profile/user-1.png') : asset($competidor->foto) }}"
                                                    alt="foto_de_perfil" class="rounded-circle" width="35"
                                                    height="35">
                                            </td>
                                            <td>{{ $competidor->nombre . ' ' . $competidor->apellidos }}</td>
                                            <td>
                                                <img src="https://flagcdn.com/h24/{{ $competidor->codigo_bandera ?? 'unknown' }}.png"
                                                    alt="{{ $competidor->nacionalidad ?? '' }}">
                                            </td>
                                            <td>{{ $competidor->escuelas->first()->nombre ?? '' }}</td>
                                            <td>{{ $competidor->cinta }}</td>
                                            <td>{{ $competidor->maestros->first() ? $competidor->maestros->first()->nombre . ' ' . $competidor->maestros->first()->apellidos : '' }}
                                            </td>
                                            <td>
                                                <button wire:click="inscribir({{ $competidor->id }})"
                                                    class="btn btn-warning btn-sm" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Inscribir competidor">
                                                    <i class="bi bi-person-fill-add"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación y control de elementos por página -->
                        <div class="mt-3 d-flex flex-column flex-md-row align-items-start justify-content-between">
                            <div class="mb-3 mb-md-0">
                                <select wire:model="perPage" class="form-select select-amarillo">
                                    <option value="25">Mostrar 25</option>
                                    <option value="50">Mostrar 50</option>
                                    <option value="100">Mostrar 100</option>
                                </select>
                            </div>
                            <div>
                                {{ $competidores->links('paginador') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container-fluid text-center mt-3">
        <div class="row row-cols-2 row-cols-sm-2 row-cols-lg-5 g-3">
            <!-- Botón Seleccionado -->
            <div class="col w-100">
                <div class="card-seleccionado p-3">
                    <div class="card-body card-body1 row">
                        <div class="d-flex align-items-center mt-3 mb-3 justify-content-center">
                            <!-- Select de Categorías -->
                            <select class="form-select me-2" style="width: auto;" wire:model="categoriaId"
                                wire:change="mostrarInscritos({{ $torneoId }}, {{ $fechaId }}, {{ $areaId }}, $event.target.value)">
                                <!-- Listar categorías filtradas -->
                                @foreach ($categoriasArea as $categoria)
                                    <option value="{{ $categoria['categoria_id'] }}">
                                        {{ $categoria['division_categoria'] . ' - ' . $categoria['nombre_categoria'] . ' - ' . count($categoria['inscritos']) }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Botón para mostrar/ocultar finalizadas -->
                            <button type="button"
                                class="btn {{ $mostrarFinalizadas ? 'btn-success' : 'btn-secondary' }} me-2"
                                wire:click="toggleFinalizadas">
                                {{ $mostrarFinalizadas ? 'Ocultar Finalizadas' : 'Mostrar Finalizadas' }}
                            </button>

                            <!-- Botón para mostrar/ocultar vacías -->
                            <button type="button" class="btn {{ $mostrarVacias ? 'btn-success' : 'btn-secondary' }}"
                                wire:click="toggleVacias">
                                {{ $mostrarVacias ? 'Ocultar Vacías' : 'Mostrar Vacías' }}
                            </button>
                        </div>

                        <div class="col-md-4">
                            <h5 class="card-text">Área {{ $areaSeleccionada['area'] }}</h5>
                        </div>
                        <div class="col-md-4">
                            <h5 class="card-text">
                                {{ $categoriaSeleccionada['division_categoria'] . ' - ' . $categoriaSeleccionada['nombre_categoria'] }}
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <h5 class="card-text">{{ $categoriaSeleccionada['horario_categoria'] }} Horas</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($hayGanador && $todosPasaron == 0)
        <div class="container-fluid">
            <h2 class="mt-5 text-yellow">Top 10 Lugares</h2>
            <div class="table-responsive">
                <table class="table table-striped table-dark mt-3 text-center" style="font-size: x-large;">
                    <thead>
                        <tr>
                            <th style="color: #EBC010 !important;">#</th>
                            <th style="color: #EBC010 !important;">Participante</th>
                            <th style="color: #EBC010 !important;">Escuela</th>
                            <th style="color: #EBC010 !important;">Calificaciones jueces</th>
                            <th style="color: #EBC010 !important;">Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $resultado)
                            <tr>
                                <td><span
                                        style="{{ $resultado->posicion == 1 ? 'font-weight: bold; color: #00FF00 !important;' : '' }}">{{ $resultado->posicion }}</span>
                                </td>
                                <td><span
                                        style="{{ $resultado->posicion == 1 ? 'font-weight: bold; color: #00FF00 !important;' : '' }}">{{ ucwords(strtolower($resultado->participante->nombre . ' ' . $resultado->participante->apellidos)) }}</span>
                                </td>
                                <td><span
                                        style="{{ $resultado->posicion == 1 ? 'font-weight: bold; color: #00FF00 !important;' : '' }}">({{ ucwords(strtolower($resultado->participante->alumno->escuelas[0]->nombre ?? $resultado->participante->maestro->escuelas[0]->nombre)) }})</span>
                                </td>
                                <td>
                                    <span>{{ number_format($resultado->participante->katas[0]->calificacion_nueva_1, 2) }}</span>
                                    <del style="color: #c01a1a;">
                                        @if (
                                            $resultado->participante->katas[0]->calificacion_nueva_1 != $resultado->participante->katas[0]->calificacion_1 &&
                                                $resultado->participante->katas[0]->calificacion_1 !== null)
                                            {{ number_format($resultado->participante->katas[0]->calificacion_1, 2) }}
                                        @endif
                                    </del>&nbsp;&nbsp;
                                    <span>{{ number_format($resultado->participante->katas[0]->calificacion_nueva_2, 2) }}</span>
                                    <del style="color: #c01a1a;">
                                        @if (
                                            $resultado->participante->katas[0]->calificacion_nueva_2 != $resultado->participante->katas[0]->calificacion_2 &&
                                                $resultado->participante->katas[0]->calificacion_2 !== null)
                                            {{ number_format($resultado->participante->katas[0]->calificacion_2, 2) }}
                                        @endif
                                    </del>&nbsp;&nbsp;
                                    <span>{{ number_format($resultado->participante->katas[0]->calificacion_nueva_3, 2) }}</span>
                                    <del style="color: #c01a1a;">
                                        @if (
                                            $resultado->participante->katas[0]->calificacion_nueva_3 != $resultado->participante->katas[0]->calificacion_3 &&
                                                $resultado->participante->katas[0]->calificacion_3 !== null)
                                            {{ number_format($resultado->participante->katas[0]->calificacion_3, 2) }}
                                        @endif
                                    </del>
                                </td>
                                <td><span
                                        style="{{ $resultado->posicion == 1 ? 'font-weight: bold; color: #00FF00 !important;' : '' }}">{{ number_format($resultado->participante->katas[0]->total_nuevo, 2) ?? 'No ha participado' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <br>
    @endif

    @if ($mostrarboton && $todosPasaron != 0)
        @if ($shuffleCounter > 0 && $shuffleCounter < 4)
            <h2 id="shuffleMessage" class="text-white text-center mt-4">Shuffle {{ $shuffleCounter }}</h2>
        @endif
        <div class="areas-categories categories-section m-4 mt-4">
            <div class="card card-categories text-center">
                <div drag-root class="card-body">
                    <ul id="sortable-list" wire:sortable="moveParticipante">
                        @foreach ($inscritosKata as $participante)
                            <li wire:sortable.item="{{ $participante->id }}"
                                wire:key="participante-{{ $participante->participante->id }}"
                                class="{{ $participante->asistencia == 0 ? 'ausente' : '' }} shuffle">
                                <div class="row">
                                    <div class="col-1 text-white">
                                        {{ $participante->order_position }}
                                    </div>
                                    <div wire:sortable.handle class="col-1">✋🏻</div>
                                    <div class="col-1">
                                        <button wire:click="toggleAsistencia({{ $participante->id }})"
                                            class="btn {{ $participante->asistencia == 1 ? 'btn-success' : 'btn-danger' }}">
                                            {{ $participante->asistencia == 1 ? 'Asistente' : 'Ausente' }}
                                        </button>
                                    </div>
                                    <div class="col-8 text-white">
                                        {{ ucwords(strtolower($participante->participante->nombre)) . ' ' . ucwords(strtolower($participante->participante->apellidos)) }}
                                    </div>
                                    <div class="col-1 text-white">
                                        @if ($participante->asistencia == 1)
                                            <button type="button" wire:click="play({{ $participante->id }})"
                                                class="btn btn-guardar">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Livewire listo');
        initializeSortable();

        Livewire.on('shuffleCompleted', function() {
            console.log('Shuffle completado');
            setTimeout(function() {
                Livewire.dispatch('startShuffle');
            }, 500); // Intervalo de tiempo entre cada shuffle
        });

        Livewire.dispatch('startShuffle'); // Iniciar el primer shuffle

        Livewire.on('refrescarPagina', () => {
            window.location.reload();
        });

        Livewire.on('hideShuffleMessage', () => {
            let shuffleMessage = document.getElementById('shuffleMessage');
            if (shuffleMessage) {
                setTimeout(function() {
                    shuffleMessage.style.display = 'none';
                }, 500);
            }
        });
    });

    function initializeSortable() {
        let el = document.getElementById('sortable-list');
        if (el && typeof Sortable !== 'undefined') {
            Sortable.create(el, {
                handle: '.col-1',
                animation: 150,
                onEnd: function(evt) {
                    let order = [];
                    el.querySelectorAll('li').forEach(function(item, index) {
                        order.push({
                            id: item.getAttribute('wire:sortable.item'),
                            order: index + 1
                        });
                    });
                    @this.call('moveParticipante', order);
                }
            });
        } else {
            console.error('SortableJS no está definido o el elemento sortable-list no se encuentra.');
        }
    }
</script>
