<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">Inscritos al torneo {{ $torneo_datos->nombre }}</h2>
        </div>
        <div style="text-align: right;">
            <div class="input-group mb-3">
                <input type="text" class="form-control buscar" wire:model.live.debounce.150ms="search"
                    placeholder="Buscar participante..." aria-describedby="button-addon2">
                <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle w-100" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <span class="bi bi-filetype-pdf"> Reportes</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark">
                    <li>
                        {{-- <a class="dropdown-item" href="#"
                            wire:click.prevent="abrirModalPDF({{ $torneo_datos->id }})">
                            <span class="bi bi-filetype-pdf"> Generar fichas de torneo</span>
                        </a> --}}
                        <a class="dropdown-item" href="#" wire:click.prevent="generarPDF">
                            <span class="bi bi-filetype-pdf"> Generar fichas de torneo</span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="#" wire:click.prevent="exportarExcel()">
                            <span class="bi bi-file-earmark-spreadsheet"> Generar reporte de escuelas</span>
                        </a>
                    </li> --}}
                    <li>
                        <a class="dropdown-item" href="#" wire:click.prevent="torneoExcel()">
                            <span class="bi bi-file-earmark-bar-graph-fill"> Generar reporte de competidores</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div wire:loading class="loading-overlay" wire:target="generarPDF">
        <div class="loader">
            <div class="box-1"></div>
            <span>Cargando PDF</span>
        </div>
    </div>

    {{-- filtro de escuela --}}
    <div class="d-flex">
        <div class="d-flex">
            <label for="escuela" class="text-white">Escuela: </label>
            <select class="form-select select-amarillo ms-2" id="escuela"
                wire:change="filtroUpdated('escuela',$event.target.value)">
                <option value="">Todas</option>
                @foreach ($ArrayEscuelas as $escuela)
                    <option value="{{ $escuela->id }}">{{ $escuela->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div style="width: 20px;"></div>
        <div class="d-flex">
            <label for="categoria" class="text-white">Sensei: </label>
            <select class="form-select select-amarillo ms-2" id="categoria"
                wire:change="filtroUpdated('maestro',$event.target.value)">
                <option value="">Todas</option>
                @foreach ($ArraySenseis as $sensie)
                    <option value="{{ $sensie->id }}">{{ $sensie->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div style="width: 20px;"></div>
        <div class="d-flex">
            <label class="form-check label text-white" for="checkAll">
                Seleccionar todos
            </label>
            <input class="form-check-input ms-2" type="checkbox" value="" id="checkAll"
                wire:click="actualizarCheckTodo()" {{ $checkTodos ? 'checked' : '' }} />
        </div>
        {{-- check box seleccionar todo --}}
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success" id="success-message" x-init="init()">
            {{ session('message') }}
        </div>
    @endif
    <div class="table-responsive">
        <table class="table table-striped table-dark mt-5" id="tablaInscritos">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Escuela</th>
                    <th>Sensei</th>
                    <th>Division y Categoria</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participantes as $index => $participante)
                    @php
                        $index = preg_replace('/[A-Z]/', '', $index);
                    @endphp
                    <tr>
                        <td>{{ $participante['nombre'] }} <br> {{ $participante['edad'] }} años</td>
                        <td>{{ $participante['escuelas'] }}</td>
                        <td>{{ $participante['maestros'] }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                {{-- declarar una variable --}}
                                @php
                                    $total = 0;
                                @endphp
                                @foreach ($participante['categorias'] as $catIndex => $categoria)
                                    @php
                                        $total += $categoria['precio'];
                                    @endphp
                                    <div class="d-flex justify-content-between align-items-center mb-2 text-nowrap">
                                        <span
                                            style="flex: 1;">{{ $categoria['division'] . ' - ' . $categoria['nombre'] . ' : $' . number_format($categoria['precio'], 0) }}
                                        </span>
                                        @if (in_array($categoria['forma'], [12, 13, 14]))
                                            <button wire:click="verificar({{ $categoria['id_registro'] }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Check-In">
                                                <i class="bi bi-hand-thumbs-up"></i> Check-In
                                            </button>
                                        @endif
                                        &nbsp;
                                        <input type="checkbox" class="genero-radio3 ms-2"
                                            wire:click="actualizarCheckPago({{ $categoria['id_registro'] }})"
                                            id="checkPago_{{ $index }}_{{ $catIndex }}"
                                            {{ $categoria['checked'] ? 'checked' : '' }}>
                                        <div>
                                            &nbsp;
                                            {{-- <button wire:click="calificar({{ $categoria['id_registro'] }})"
                                                class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Check-In"><i
                                                    class="bi bi-hand-thumbs-up"></i> Check-In</button>&nbsp; --}}

                                            <button wire:click="delete({{ $categoria['id_registro'] }})"
                                                class="btn btn-danger btn-sm" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Eliminar registro"
                                                wire:confirm="¿Estás seguro de eliminar el registro?"><i
                                                    class="bi bi-trash3-fill"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-center align-items-center mb-2 text-nowrap">
                                    Total: ${{ $total }}
                                </div>
                                <button type="button" wire:click="toggleCheckAll({{ $index }})"
                                    class="btn btn-amarillo" style="width: 75%">
                                    {{ isset($checkAll[$index]) && $checkAll[$index] ? 'Deseleccionar todas' : 'Seleccionar todas' }}
                                    para
                                    {{ $participante['nombre'] }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex align-items-start justify-content-between">
        <div>
            <form wire:submit.prevent="buscar" class="d-flex">
                <select class="form-select select-amarillo" id="perPage" wire:change='update' wire:model="perPage">
                    <option value="25">Mostrar 25</option>
                    <option value="50">Mostrar 50</option>
                    <option value="100">Mostrar 100</option>
                </select>
            </form>
        </div>
        <div>
            {{ $participantes->links('custom') }}
        </div>
    </div>
    @include('modal-pdf')
</div>
