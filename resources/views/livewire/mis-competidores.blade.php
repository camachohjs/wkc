<div class="container mb-3">
    <div class="row text-white mt-4">
        <div class="col-md-12 mt-2 mb-2">
            <h4 class="margin-left">Mis competidores</h4>
        </div>
    </div>
    <div class="row text-white mt-4">
        <div class="col-md-6 d-flex justify-content-start">
            <div class="input-group mb-3">
                <input type="text" class="form-control buscar" wire:model.live.debounce.150ms="search"
                    placeholder="Buscar competidor..." aria-describedby="button-addon2">
                <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <div class="text-right">
                <button class="btn btn-amarillo" wire:click="create">Crear Competidor</button>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <ul class="list-group">
                    @foreach ($atletas as $index => $atleta)
                        @if ($index % 2 == 0)
                            <li class="list-group-item" style="min-height: 190px">
                                <div class="row">
                                    <div class="col-md-4">
                                        @if (auth()->user()->id == $atleta->user_id)
                                            <a href="{{ route('panel') }}">
                                                <img src="{{ $atleta->foto ?: 'libs/images/profile/user-1.png' }}"
                                                    class="img-fluid img-atleta" alt="Imagen del atleta">
                                            </a>
                                        @else
                                            <a href="{{ route('competidor-edit', ['id' => $atleta->id]) }}">
                                                <img src="{{ $atleta->foto ?: 'libs/images/profile/user-1.png' }}"
                                                    class="img-fluid img-atleta" alt="Imagen del atleta">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <span
                                            style="font-size: large;">{{ ucfirst($atleta->nombre) . ' ' . ucfirst($atleta->apellidos) . ' - ' . $atleta->peso }}
                                            kg - {{ $atleta->edad }} años</span>
                                        @if ($atleta->user_id == $maestro->user_id)
                                            <span class="text-yellow">(sensei)</span>
                                        @else
                                            <button wire:click="edit({{ $atleta->id }})"
                                                class="btn btn-outline-primary btn-sm"
                                                data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Editar competidor"><i
                                                    class="bi bi-pencil"></i></button>&nbsp;
                                            <button wire:click="deleteAlumno({{ $atleta->id }})"
                                                class="btn btn-outline-danger btn-sm"
                                                wire:confirm="¿Estás seguro de eliminar al competidor?"
                                                data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip"
                                                data-bs-placement="top" data-bs-title="Eliminar competidor"><i
                                                    class="bi bi-trash3-fill"></i></button>
                                        @endif
                                        <br>
                                        <span style="font-size: large;">{{ $atleta->email }}</span><br>
                                        @if ($atleta->registros->isNotEmpty())
                                            @php
                                                $registrosAgrupados = $atleta->registros->groupBy('alumno_id');
                                            @endphp
                                            @foreach ($registrosAgrupados as $alumnoId => $registros)
                                                <div>
                                                    <ul>
                                                        @foreach ($registros->take($mostrarRegistrosPorAlumno[$alumnoId] ?? 3) as $registro)
                                                            <li>
                                                                <div class="d-flex align-items-center justify-content-between"
                                                                    style="margin-bottom: 10px;">
                                                                    <span
                                                                        style="flex-grow: 1; color: #FFF; font-size: medium;">
                                                                        {{ $registro->categoria->division }} -
                                                                        {{ $registro->categoria->nombre }} -
                                                                        {{ $registro->torneo->nombre }}
                                                                    </span>
                                                                    <button wire:click="editar({{ $registro->id }})"
                                                                        class="btn text-amarillo"
                                                                        style="margin-right: 10px;">
                                                                        Editar
                                                                    </button>
                                                                    <button wire:click="delete({{ $registro->id }})"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        wire:confirm="¿Estás seguro de eliminar el registro?"
                                                                        data-bs-custom-class="delete-tooltip"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        data-bs-title="Eliminar registro">
                                                                        <i class="bi bi-trash3-fill"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @if ($registros->count() > ($mostrarRegistrosPorAlumno[$alumnoId] ?? 3))
                                                        <button wire:click="cargarMasRegistros('{{ $alumnoId }}')"
                                                            class="btn btn-guardar">Mostrar más</button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <br><span style="font-size: medium;">No se ha registrado a ningún
                                                torneo.</span><br><br>
                                            <button class="btn btn-guardar"
                                                wire:click="registrar({{ $atleta->id }})">
                                                Registrar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <!-- Columna derecha -->
            <div class="col-md-6">
                <ul class="list-group">
                    @foreach ($atletas as $index => $atleta)
                        @if ($index % 2 == 1)
                            <li class="list-group-item" style="min-height: 190px">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{ route('competidor-edit', ['id' => $atleta->id]) }}">
                                            <img src="{{ $atleta->foto ?: 'libs/images/profile/user-1.png' }}"
                                                class="img-fluid img-atleta" alt="Imagen del atleta">
                                        </a>
                                    </div>
                                    <div class="col-md-8">
                                        <span
                                            style="font-size: large;">{{ ucfirst($atleta->nombre) . ' ' . ucfirst($atleta->apellidos) . ' - ' . $atleta->peso }}
                                            kg - {{ $atleta->edad }} años</span>
                                        <button wire:click="edit({{ $atleta->id }})"
                                            class="btn btn-outline-primary btn-sm" data-bs-custom-class="edit-tooltip"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            data-bs-title="Editar competidor"><i
                                                class="bi bi-pencil"></i></button>&nbsp;
                                        <button wire:click="deleteAlumno({{ $atleta->id }})"
                                            class="btn btn-outline-danger btn-sm"
                                            wire:confirm="¿Estás seguro de eliminar al competidor?"
                                            data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-title="Eliminar competidor"><i
                                                class="bi bi-trash3-fill"></i></button>
                                        <br>
                                        <span style="font-size: large;">{{ $atleta->email }}</span><br>
                                        @if ($atleta->registros->isNotEmpty())
                                            @php
                                                $registrosAgrupados = $atleta->registros->groupBy('alumno_id');
                                            @endphp
                                            @foreach ($registrosAgrupados as $alumnoId => $registros)
                                                <div>
                                                    <ul>
                                                        @foreach ($registros->take($mostrarRegistrosPorAlumno[$alumnoId] ?? 3) as $registro)
                                                            <li>
                                                                <div class="d-flex align-items-center justify-content-between"
                                                                    style="margin-bottom: 10px;">
                                                                    <span
                                                                        style="flex-grow: 1; color: #FFF; font-size: medium;">
                                                                        {{ $registro->categoria->division }} -
                                                                        {{ $registro->categoria->nombre }} -
                                                                        {{ $registro->torneo->nombre }}
                                                                    </span>
                                                                    <button wire:click="editar({{ $registro->id }})"
                                                                        class="btn text-amarillo"
                                                                        style="margin-right: 10px;">
                                                                        Editar
                                                                    </button>
                                                                    <button wire:click="delete({{ $registro->id }})"
                                                                        class="btn btn-outline-danger btn-sm"
                                                                        wire:confirm="¿Estás seguro de eliminar el registro?"
                                                                        data-bs-custom-class="delete-tooltip"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        data-bs-title="Eliminar registro">
                                                                        <i class="bi bi-trash3-fill"></i>
                                                                    </button>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                    @if ($registros->count() > ($mostrarRegistrosPorAlumno[$alumnoId] ?? 3))
                                                        <button wire:click="cargarMasRegistros('{{ $alumnoId }}')"
                                                            class="btn btn-guardar">Mostrar más</button>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <br><span style="font-size: medium;">No se ha registrado a ningún
                                                torneo.</span><br><br>
                                            <button class="btn btn-guardar"
                                                wire:click="registrar({{ $atleta->id }})">
                                                Registrar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <br>
        <div class="mt-3 d-flex align-items-start justify-content-between">
            <div>
                <form wire:submit.prevent="buscar" class="d-flex">
                    <select class="form-select select-amarillo" id="perPage" wire:change='paginacion'
                        wire:model="perPage">
                        <option value="28">Mostrar 28</option>
                        <option value="50">Mostrar 50</option>
                        <option value="100">Mostrar 100</option>
                    </select>
                </form>
            </div>
            <div>
                {{ $atletas->links('custom') }}
            </div>
        </div>
    </div>
</div>
