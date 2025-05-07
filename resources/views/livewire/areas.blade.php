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
            color: #ebbf10ab;
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
            box-shadow: none;
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
            background-color: #EBC010;
            color: #000000;
        }

        .btn.card-area:active {
            background: #090808;
            color: #EBC010;
        }

        /* Categorias Card */
        .card.card-categories {
            background: #0E0D0D;
            border: none;
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

        .btn-generar {
            background: #0E0D0D;
            border: 2px solid #FFFFFF;
            color: #FFFFFF;
        }

        .btn-generar:hover {
            color: #EBC010;
            border: 2px solid #ebc010;
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
            @if ($mostrarRondas === false)
                {{-- <div class="m-4" style="text-align: right;">
                    <button class="btn btn-generar" wire:click="generarEmparejamientosParaTorneo"
                        wire:loading.attr="disabled">Generar combates</button>
                    <div wire:loading class="loading-overlay">
                        <div class="loader">
                            <div class="box-1"></div>
                            <span>Cargando...</span>
                        </div>
                    </div>
                </div> --}}
            @endif
            &nbsp;
            @if ($mostrarRondas === true)
                <div class="m-4" style="text-align: right;">
                    <button class="btn btn-danger" wire:click="reiniciarEmparejamientosTorneo"
                        wire:confirm="¿Estás seguro de reiniciar los combates del torneo?">Reiniciar combates
                        (torneo)</button>
                </div>
            @endif
        </div>
    </div>

    <div class="section-torneo-areas-categorias mt-4">

        @if ($botonActivo == 'todas')

            @foreach ($infoTorneo as $key => $dias)
                <div class="section-areas-date d-flex align-items-center justify-content-between mt-3">
                    <div class="text-left">
                        <h3 class="text-white">{{ $this->formatearFecha($dias['fecha']) }}</h2>
                    </div>
                </div>

                <div class="section-areas mt-4">
                    <div class="row m-0">
                        @foreach ($dias['areas'] as $keyArea => $area)
                            <div class="col-12 col-sm-6 col-lg-3 mt-3" {{-- style="height: 350px;" --}}>
                                <button type="button" class="btn card-area"
                                    wire:click="mostrarCategoriasArea({{ $torneoId }}, {{ $key }}, {{ $keyArea }})">
                                    <div class="card-body card-body1 mt-3">
                                        <h5 style="font-size: 2rem;">Área {{ $area['area'] }}</h5>
                                        {{-- @php
                                            $categoriasOrdenadas = collect($area['categorias'])->sort(function ($a, $b) {
                                                $result = strcmp($a['horario_categoria'], $b['horario_categoria']);
                                                if ($result === 0) {
                                                    return strnatcmp($a['division_categoria'], $b['division_categoria']);
                                                }
                                                return $result;
                                            });
                                        @endphp
                                        @foreach ($categoriasOrdenadas as $categoria)
                                            @if (count($categoria['inscritos']) >= 1)
                                                <div>
                                                    <p class="card-text">{{ $categoria['division_categoria'].' - '.$categoria['horario_categoria'] }} Horas</p>
                                                </div>
                                            @endif
                                        @endforeach --}}
                                        @php
                                            $horarios = array_column($area['categorias'], 'horario_categoria');
                                            $horarios24h = array_map(function ($horarios) {
                                                return date('H:i', strtotime($horarios));
                                            }, $horarios);

                                            sort($horarios24h);
                                            $horarioInicial = $horarios24h
                                                ? date('g:i A', strtotime($horarios24h[0]))
                                                : 'N/A';
                                            $horarioFinal = $horarios24h
                                                ? date('g:i A', strtotime(end($horarios24h)))
                                                : 'N/A';
                                        @endphp
                                        <h6 class="card-subtitle mb-2">{{ $horarioInicial }} - {{ $horarioFinal }}
                                        </h6>
                                    </div>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="section-areas-date d-flex alig-items-center justify-content-between">
                <div class="text-left">
                    <h3 class="text-white">{{ $this->formatearFecha($fechaTorneoSeleccion) }}</h3>
                </div>
            </div>

            <div class="section-areas mt-4">
                <div class="row m-0">
                    @foreach ($areasFecha as $keyArea => $area)
                        <div class="col-12 col-sm-6 col-lg-3 mt-3">
                            <button type="button" class="btn card-area"
                                wire:click="mostrarCategoriasArea({{ $torneoId }}, {{ $fechaId }}, {{ $keyArea }})">
                                <div class="card-body card-body1">
                                    <h5 style="font-size: 2rem;">Área {{ $area['area'] }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted"></h6>

                                    @php
                                        $horarios = array_column($area['categorias'], 'horario_categoria');
                                        $horarios24h = array_map(function ($horarios) {
                                            return date('H:i', strtotime($horarios));
                                        }, $horarios);

                                        sort($horarios24h);
                                        $horarioInicial = $horarios24h
                                            ? date('g:i A', strtotime($horarios24h[0]))
                                            : 'N/A';
                                        $horarioFinal = $horarios24h
                                            ? date('g:i A', strtotime(end($horarios24h)))
                                            : 'N/A';
                                    @endphp
                                    <h6 class="card-subtitle mb-2">{{ $horarioInicial }} - {{ $horarioFinal }}
                                    </h6>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
