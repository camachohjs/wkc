<div class="px-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">Rankings</h2>
        </div>
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Selecciona un ciclo
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton" style="background: linear-gradient(145deg, #1c1c1c, #000000);">
                @foreach($ciclos as $ciclo)
                    <li><a class="dropdown-item" href="#" wire:click.prevent="exportarExcel('{{ $ciclo }}')">{{ $ciclo }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="d-flex">
        <div class="w-100 d-flex flex-column">
            <div class="w-100 rounded-lg bg-dark p-4" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <h5 class="text-white">
                    Filtros
                </h5><br>
                <div class="d-flex flex-column">
                    <div class="form-group w-100 mx-2">
                        <label class="text-white" for="competidor">Competidor</label>
                        <input wire:model.live.debounce.150ms="competidor" type="text" class="form-control" placeholder="Buscar Competidor..." id="competidor">
                    </div>
                    <div class="form-group w-100 mx-2 mt-4">
                        <label class="text-white" for="competencia">Competencia</label>
                        <select wire:model.live="competenciaSelected" wire:change="agregarCompetenciaBusqueda" type="text" class="form-control" id="competencia" style="height: 100%;">
                            <option value="">Selecciona una división</option>
                            @foreach ($categoriasLista as $item)
                                <option value="{{$item->id}}">{{$item->division}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if ($mostrar)
                <div class="mt-3 row">
                    @foreach ($competenciasBusqueda as $competencia)
                        <div class="col-3 my-2">
                            <div class="position-relative">
                                <div class="w-100 d-flex justify-content-center align-items-center p-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    <span class="flex-grow-1 text-center text-white" style="font-size: x-large;">
                                        {{ $competencia->division }}
                                    </span>
                                </div>
                                <button class="position-absolute top-0 end-0 btn btn-danger btn-sm m-1" style="border: none;" wire:click="eliminarCompetenciaBusqueda({{ $competencia->id }})">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="form-group w-100 mx-2 mt-4">
                    <label class="text-white" for="torneo">Torneo</label>
                    <div class="d-flex flex-nowrap overflow-auto">
                        <button wire:click="seleccionarTorneo('')" class="btn mx-2 my-2 {{ $torneoSeleccionado === '' ? 'active' : '' }}"
                            style="background: {{ $torneoSeleccionado === '' ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)' }}; 
                                    border: 2px solid #EBC010; border-radius: 0.5rem; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease; color: {{ $torneoSeleccionado === '' ? 'black' : 'white' }};">
                            Todos los Torneos
                        </button>
                        @foreach ($torneosLista as $torneo)
                            <button wire:click="seleccionarTorneo('{{ $torneo->nombre_torneo }}')" class="btn mx-2 my-2 {{ $torneoSeleccionado === $torneo->nombre_torneo ? 'active' : '' }}"
                                style="background: {{ $torneoSeleccionado === $torneo->nombre_torneo ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)' }}; 
                                        border: 2px solid #EBC010; border-radius: 0.5rem; 
                                        transition: transform 0.3s ease, box-shadow 0.3s ease; color: {{ $torneoSeleccionado === $torneo->nombre_torneo ? 'black' : 'white' }};">
                                {{ $torneo->nombre_torneo }}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button wire:click="cambiarAgrupamiento('category')" 
                            class="btn mx-2 my-2 {{ $agrupamiento === 'category' ? 'active' : '' }}" 
                            style="background: {{ $agrupamiento === 'category' ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)' }}; 
                                    border: 2px solid #EBC010; 
                                    border-radius: 0.5rem; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease; 
                                    color: {{ $agrupamiento === 'category' ? 'black' : 'white' }};">
                        Agrupar por categoría
                    </button>
                    <button wire:click="cambiarAgrupamiento('name')" 
                            class="btn mx-2 my-2 {{ $agrupamiento === 'name' ? 'active' : '' }}" 
                            style="background: {{ $agrupamiento === 'name' ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)' }}; 
                                    border: 2px solid #EBC010; 
                                    border-radius: 0.5rem; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease; 
                                    color: {{ $agrupamiento === 'name' ? 'black' : 'white' }};">
                        Agrupar por nombre
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3 text-white">
        @if ($agrupamiento === 'category' && $torneoSeleccionado === '')
            @foreach ($rankings as $categoriaId => $participantes)
                <div class="col-lg-12 my-2">
                    <div class="w-100 p-3 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="row">
                            <div class="col-11">
                                <span class="text-white h5">
                                    {{ $participantes->first()['division'] }}
                                </span>
                            </div>
                            <div class="col-1">
                                <i class="bi bi-chevron-down" wire:click="toggleCategoria({{ $categoriaId }})" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        @if (in_array($categoriaId, $categoriasAbiertas))
                            <div class="mt-3">
                                <div class="row text-center text-yellow">
                                    <div class="col-1"><strong>Posición</strong></div>
                                    <div class="col-3"><strong>Foto</strong></div>
                                    <div class="col-4"><strong>Nombre</strong></div>
                                    {{-- <div class="col-3"><strong>País</strong></div> --}}
                                    <div class="col-2"><strong>Total</strong></div>
                                </div>
                                @foreach ($participantes as $participante)
                                    <div class="row mt-2 text-center">
                                        <div class="col-1">{{ $participante['posicion'] }}</div>
                                        <div class="col-3"><img src="{{ $participante['foto'] }}" alt="foto_de_perfil" class="rounded-circle foto-perfil" style="height: 3rem; width: 3rem;"></div>
                                        <div class="col-4">{{ $participante['nombre'] }}</div>
                                        {{-- <div class="col-3">{{ $participante['pais'] }}</div> --}}
                                        <div class="col-2">{{ $participante['puntos'] }}</div>
                                        <div class="col-1 px-2 d-flex flex-column text-center">
                                            <span class="text-white h5 mb-1" wire:click="mostrarDetalles({{ $participante['persona']->id }})" style="cursor: pointer;">
                                                <i class="bi bi-chevron-down"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($participanteSeleccionado && $participanteSeleccionado->id == $participante['persona']->id)
                                        <div class="mt-3 p-3 rounded text-center">
                                            <h5 class="text-yellow">Detalles del Participante</h5>
                                            <p class="text-center"><img src="https://flagcdn.com/h24/{{ $participanteSeleccionado->codigo_bandera ?? 'unknown' }}.png" alt="{{ $participanteSeleccionado->nacionalidad ?? '' }}"></p>
                                            <p class="text-center"><strong class="text-yellow">Edad:</strong> <span class="text-white"></span>{{ $participanteSeleccionado->edad }}</p>
                                            <p class="text-center"><strong class="text-yellow">Cinta:</strong> <span class="text-white"></span>{{ $participanteSeleccionado->cinta }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @elseif ($agrupamiento === 'category' && $torneoSeleccionado !== '')
            @foreach ($rankings as $categoriaId => $participantes)
                <div class="col-lg-12 my-2">
                    <div class="w-100 p-3 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="row">
                            <div class="col-11">
                                <span class="text-white h5">
                                    {{ $participantes->first()['division'] }}
                                </span>
                            </div>
                            <div class="col-1">
                                <i class="bi bi-chevron-down" wire:click="toggleCategoria({{ $categoriaId }})" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        @if (in_array($categoriaId, $categoriasAbiertas))
                            <div class="mt-3">
                                <div class="row text-center text-yellow">
                                    <div class="col-1"><strong>Posición</strong></div>
                                    <div class="col-3"><strong>Foto</strong></div>
                                    <div class="col-4"><strong>Nombre</strong></div>
                                    {{-- <div class="col-3"><strong>País</strong></div> --}}
                                    <div class="col-2"><strong>Total</strong></div>
                                </div>
                                @foreach ($participantes as $participante)
                                    <div class="row mt-2 text-center">
                                        <div class="col-1">{{ $participante['posicion'] }}</div>
                                        <div class="col-3"><img src="{{ $participante['foto'] }}" alt="foto_de_perfil" class="rounded-circle foto-perfil" style="height: 3rem; width: 3rem;"></div>
                                        <div class="col-4">{{ $participante['nombre'] }}</div>
                                        {{-- <div class="col-3">{{ $participante['pais'] }}</div> --}}
                                        <div class="col-2">{{ $participante['puntos'] }}</div>
                                        <div class="col-1 px-2 d-flex flex-column text-center">
                                            <span class="text-white h5 mb-1" wire:click="mostrarDetalles({{ $participante['persona']->id }})" style="cursor: pointer;">
                                                <i class="bi bi-chevron-down"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @if ($participanteSeleccionado && $participanteSeleccionado->id == $participante['persona']->id)
                                        <div class="mt-3 p-3 rounded text-center">
                                            <h5 class="text-yellow">Detalles del Participante</h5>
                                            <p class="text-center"><img src="https://flagcdn.com/h24/{{ $participanteSeleccionado->codigo_bandera ?? 'unknown' }}.png" alt="{{ $participanteSeleccionado->nacionalidad ?? '' }}"></p>
                                            <p class="text-center"><strong class="text-yellow">Edad:</strong> <span class="text-white"></span>{{ $participanteSeleccionado->edad }}</p>
                                            <p class="text-center"><strong class="text-yellow">Cinta:</strong> <span class="text-white"></span>{{ $participanteSeleccionado->cinta }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            @foreach ($rankings as $personaId => $participante)
                <div class="col-lg-12 my-2">
                    <div class="w-100 p-3 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="{{ $participante['foto'] }}" alt="foto_de_perfil" class="rounded-circle foto-perfil" style="height: 3rem; width: 3rem;">
                            <span class="text-white h5">
                                {{ $participante['nombre'] }} <img src="https://flagcdn.com/h24/{{ $participante['nacionalidad'] ?? 'unknown' }}.png" alt="{{ $participante['nacionalidad_nombre'] ?? '' }}">
                            </span>
                            <i class="bi bi-chevron-down" wire:click="togglePersona({{ $personaId }})" style="cursor: pointer;"></i>
                        </div>
                        @if (in_array($personaId, $personasAbiertas))
                            <div class="mt-3">
                                <div class="row text-center text-yellow">
                                    <div class="col-2"><strong>Lugar</strong></div>
                                    <div class="col-4"><strong>Torneo</strong></div>
                                    <div class="col-4"><strong>División</strong></div>
                                    <div class="col-2"><strong>Puntos</strong></div>
                                </div>
                                @foreach ($participante['torneos'] as $torneo)
                                    <div class="row mt-2 text-center">
                                        <div class="col-2">{{ $torneo['lugar'] }}</div>
                                        <div class="col-4">{{ $torneo['nombre_torneo'] }}</div>
                                        <div class="col-4">{{ $torneo['categoria'] }}</div>
                                        <div class="col-2">{{ $torneo['puntos'] }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>