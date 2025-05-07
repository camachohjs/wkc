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

        @media (min-width: 992px) {
            .w-lg-85 {
                width: 85% !important;
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
                            @if (auth()->user()->hasRole('torneo user')) wire:click="navegarFechasMenu('{{ $key }}', '{{ $torneoId }}', '{{ $areaId }}')"
                            @else
                                wire:click="navegarFechasMenu('{{ $key }}', '{{ $torneoId }}')" @endif>{{ $this->formatearFecha($dias['fecha']) }}</button>
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

    @if ($seleccionArea)

        {{-- Areas Categories --}}

        <div class="section-areas-date d-flex align-items-center justify-content-between mt-4">
            <div class="text-left">
                <h3 class="text-white">{{ $this->formatearFecha($dias['fecha']) }}</h2>
            </div>
        </div>

        @role('admin')
            <div class="container-fluid text-center">
                <div class="row row-cols-2 row-cols-sm-2 row-cols-lg-5 g-3">
                    <!-- Botón Seleccionado -->
                    <div class="col">
                        <button type="button" class="btn card-seleccionado">
                            <div class="card-body card-body1">
                                <h5 style="font-size: 2rem;">Área {{ $areaSeleccionada['area'] }}</h5>
                                @php
                                    $horarios = array_column($areaSeleccionada['categorias'], 'horario_categoria');
                                    $horarios24h = array_map(function ($horarios) {
                                        return date('H:i', strtotime($horarios));
                                    }, $horarios);

                                    sort($horarios24h);
                                    $horarioInicial = $horarios24h ? date('g:i A', strtotime($horarios24h[0])) : 'N/A';
                                    $horarioFinal = $horarios24h ? date('g:i A', strtotime(end($horarios24h))) : 'N/A';
                                @endphp
                                <h6 class="card-subtitle mb-2">{{ $horarioInicial }} - {{ $horarioFinal }}</h6>
                            </div>
                        </button>
                    </div>
                    <!-- Otros Botones -->
                    @foreach ($areasFecha as $keyArea => $area)
                        <div class="col">
                            <button type="button" class="btn card-area"
                                wire:click="mostrarCategoriasArea({{ $torneoId }}, {{ $fechaId }}, {{ $keyArea }})">
                                <div class="card-body card-body1">
                                    <h5 style="font-size: 2rem;">Área {{ $area['area'] }}</h5>
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
                                    <h6 class="card-subtitle mb-2">{{ $horarioInicial }} - {{ $horarioFinal }}</h6>
                                </div>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endrole

        @php
            $esAdmin = auth()->user()->hasRole('admin');
            $scorekeeper = auth()->user()->hasRole('torneo user');
        @endphp
        <div @class([
            'areas-categories',
            'categories-section',
            'mt-4',
            'w-100',
            'container-fluid',
            'w-lg-85' => $esAdmin,
        ])>
            <div class="card card-categories">
                <div drag-root class="card-body">
                    <h5 class="card-title text-center text-yellow">Área {{ $areaSeleccionada['area'] }}</h5>
                    <ul wire:sortable="updateCategoriasOrder" class="list-unstyled">
                        @foreach ($categoriasArea as $categoria)
                            {{-- @if (count($categoria['inscritos']) >= 1)  este if hace que solo se vean las que tengal al menos un inscrito, se quita por orden del cliente --}}
                            @php
                                $hayGanador = DB::table('categoria_torneo')
                                    ->where('torneo_id', $torneoId)
                                    ->where('categoria_id', $categoria['categoria_id'])
                                    ->whereNotNull('ganador_id')
                                    ->exists();
                                $hayGanadorKata = DB::table('katas')
                                    ->where('torneo_id', $torneoId)
                                    ->where('categoria_id', $categoria['categoria_id'])
                                    ->where('order_position', 1)
                                    ->whereNotNull('total_nuevo')
                                    ->exists();
                            @endphp
                            <li wire:sortable.item="{{ $categoria['categoria_id'] }}"
                                wire:key="task-{{ $categoria['categoria_id'] }}" class="mb-3">
                                <div class="row align-items-center">
                                    @role('admin')
                                        <div wire:sortable.handle class="col-1 d-none d-md-block">
                                            ✋🏻
                                        </div>
                                    @endrole
                                    <div @class(['col-12', 'col-md-7' => $esAdmin])>
                                        <button type="button" class="btn btn-invisible w-100 text-lg-start"
                                            wire:click="mostrarInscritos({{ $torneoId }}, {{ $fechaId }}, {{ $areaId }}, {{ $categoria['categoria_id'] }})">
                                            <div class="row {{ $hayGanador || $hayGanadorKata ? 'text-yellow' : '' }}">
                                                <div @class([
                                                    'col-12',
                                                    'col-sm-6',
                                                    'col-md-3',
                                                    'text-center' => $scorekeeper,
                                                ])>
                                                    {{ $categoria['division_categoria'] }}
                                                </div>
                                                <div @class([
                                                    'col-12',
                                                    'col-sm-6',
                                                    'col-md-3',
                                                    'text-center' => $scorekeeper,
                                                ])>
                                                    {{ 'Inscritos: ' . count($categoria['inscritos']) }}
                                                </div>
                                                <div @class([
                                                    'col-12',
                                                    'col-sm-6',
                                                    'col-md-3' => $scorekeeper,
                                                    'text-center' => $scorekeeper,
                                                    'col-md-2' => $esAdmin,
                                                ])>
                                                    Área {{ $areaSeleccionada['area'] }}
                                                </div>
                                                <div @class([
                                                    'col-12',
                                                    'col-sm-6',
                                                    'col-md-3' => $scorekeeper,
                                                    'text-center' => $scorekeeper,
                                                    'col-md-2' => $esAdmin,
                                                ])>
                                                    {{ $categoria['horario_categoria'] }}
                                                </div>
                                                @role('admin')
                                                    @if ($hayGanador || $hayGanadorKata)
                                                        <div class="col-12 col-sm-6 col-md-2">
                                                            Concluida
                                                        </div>
                                                    @endif
                                                @endrole
                                            </div>
                                        </button>
                                    </div>
                                    @role('admin')
                                        <div class="col-12 col-md-4 mt-3 mt-md-0">
                                            <div class="row">
                                                <div class="col-8">
                                                    <select class="form-select"
                                                        wire:model="selectedArea.{{ $categoria['categoria_id'] }}">
                                                        <option value="">Mover a área...</option>
                                                        @for ($i = 1; $i <= 7; $i++)
                                                            <option value="{{ $i }}">Área
                                                                {{ $i }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <div class="col-4">
                                                    <button type="button" class="btn btn-amarillo w-100"
                                                        wire:click="moverCategoria({{ $categoria['categoria_id'] }})">Mover</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endrole
                                </div>
                            </li>
                            {{-- @endif --}}
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- <script>
    let root = document.querySelector('[drag-root]')

    root.querySelectorAll('[drag-item]').forEach(element => {

        element.addEventListener('dragstart', e => {
            e.target.setAttribute('dragging', true)
        })

        element.addEventListener('drop', e => {
            e.target.classList.remove('bg-warning')
            let draggingEl = root.querySelector('[dragging]')

            if (draggingEl !== e.target) {
                let rect = e.target.getBoundingClientRect();
                let midY = rect.top + rect.height / 2;
                if (e.clientY > midY) {
                    e.target.after(draggingEl);
                } else {
                    e.target.before(draggingEl);
                }
            }

            // refresh livewire component
            let component = Livewire.find(
                e.target.closest('[wire\\:id]').getAttribute('wire:id')
            )

            let orderIds = Array.from(root.querySelectorAll('[drag-item]'))
                .map(itemEl => itemEl.getAttribute('drag-item'))

            // component.call('reorder', orderIds)


        })

        element.addEventListener('dragenter', e => {
            e.target.classList.add('bg-warning')
            e.preventDefault()
        })

        element.addEventListener('dragover', e => e.preventDefault())

        element.addEventListener('dragleave', e => {
            e.target.classList.remove('bg-warning')

        })

        element.addEventListener('dragend', e => {
            e.target.removeAttribute('dragging')
        })

    });
</script> --}}
