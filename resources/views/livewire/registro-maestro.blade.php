<div class="container">
    <h2 class="text-white">Registro a {{ $torneo_datos->nombre }}</h2><br>
    <div class="container-fluid">
        <div class="row justify-content-start">
            <div class="col-12 col-md-8">
                <div class="d-flex flex-wrap justify-content-around text-center "
                    style="border-radius: 0px; border: 1px solid #F9F9F9; background: #010206;">
                    <span class="flex-fill p-2"
                        style=" {{ $step == 1 ? 'background-color: #EBC010; color: black;' : 'color: white;' }}">
                        Mis competidores
                    </span>
                    <span class="flex-fill p-2"
                        style=" {{ $step == 2 ? 'background-color: #EBC010; color: black;' : 'color: white;' }}">
                        Perfil
                    </span>
                    <span class="flex-fill p-2"
                        style=" {{ $step == 3 ? 'background-color: #EBC010; color: black;' : 'color: white;' }}">
                        Categorías
                    </span>
                    <span class="flex-fill p-2"
                        style=" {{ $step == 4 ? 'background-color: #EBC010; color: black;' : 'color: white;' }}">
                        Confirmación
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if ($step == 1)
        <div class="container mb-3 mt-3">
            <div class="d-flex justify-content-end">
                <div class="input-group mb-3">
                    <input type="text" class="form-control buscar" wire:model.live.debounce.150ms="search"
                        placeholder="Buscar competidor..." aria-describedby="button-addon2">
                    <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
                </div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-lg-6 col-md-12">
                        <ul class="list-group">
                            @foreach ($atletas as $index => $atleta)
                                @if ($index % 2 == 0)
                                    <li class="list-group-item">
                                        <button
                                            wire:click="seleccionarCompetidor({{ $atleta->id }}, '{{ $atleta->nombre }}', '{{ $atleta->apellidos }}')"
                                            class="btn text-start w-100 text-white"
                                            style="background-color: transparent; border: none; padding: 0;">
                                            <div class="row align-items-center" style="min-height: 127px">
                                                <div class="col-3">
                                                    <img src="{{ $atleta->foto ?: asset('libs/images/profile/user-1.png') }}"
                                                        class="img-fluid" alt="Imagen del atleta"
                                                        style="max-height: 127px;">
                                                </div>
                                                <div class="col-7">
                                                    <span
                                                        class="d-block font-weight-bold">{{ ucfirst($atleta->nombre) . ' ' . ucfirst($atleta->apellidos) }}</span>
                                                    @if ($atleta->user_id == $maestro->user_id)
                                                        <span class="text-yellow"> (sensei)</span>
                                                    @endif
                                                    <span class="d-block">{{ $atleta->peso }} kg - {{ $atleta->edad }}
                                                        años</span>
                                                    <span class="d-block">{{ $atleta->email }}</span>
                                                </div>
                                                <div class="col-2 text-right">
                                                    <i class="bi bi-chevron-compact-right d-none d-md-inline"
                                                        style="font-size: 85px;"></i>
                                                    <i class="bi bi-chevron-compact-right d-inline d-md-none"
                                                        style="font-size: 45px;"></i>
                                                </div>
                                            </div>
                                        </button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <!-- Columna derecha -->
                    <div class="col-lg-6 col-md-12">
                        <ul class="list-group">
                            @foreach ($atletas as $index => $atleta)
                                @if ($index % 2 == 1)
                                    <li class="list-group-item">
                                        <button
                                            wire:click="seleccionarCompetidor({{ $atleta->id }}, '{{ $atleta->nombre }}', '{{ $atleta->apellidos }}')"
                                            class="btn text-start w-100 text-white"
                                            style="background-color: transparent; border: none; padding: 0;">
                                            <div class="row align-items-center" style="min-height: 127px">
                                                <div class="col-3">
                                                    <img src="{{ $atleta->foto ?: asset('libs/images/profile/user-1.png') }}"
                                                        class="img-fluid" alt="Imagen del atleta"
                                                        style="max-height: 127px;">
                                                </div>
                                                <div class="col-7">
                                                    <span
                                                        class="d-block font-weight-bold">{{ ucfirst($atleta->nombre) . ' ' . ucfirst($atleta->apellidos) }}</span>
                                                    <span class="d-block">{{ $atleta->peso }} kg - {{ $atleta->edad }}
                                                        años</span>
                                                    <span class="d-block">{{ $atleta->email }}</span>
                                                </div>
                                                <div class="col-2 text-right">
                                                    <i class="bi bi-chevron-compact-right d-none d-md-inline"
                                                        style="font-size: 85px;"></i>
                                                    <i class="bi bi-chevron-compact-right d-inline d-md-none"
                                                        style="font-size: 45px;"></i>
                                                </div>
                                            </div>
                                        </button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <br>
                <div class="mt-3 d-flex align-items-start justify-content-between flex-wrap">
                    <div class="mb-3">
                        <form wire:submit.prevent="buscar" class="d-flex">
                            <select class="form-select select-amarillo" id="perPage" wire:change='paginacion'
                                wire:model="perPage">
                                <option value="28">Mostrar 28</option>
                                <option value="50">Mostrar 50</option>
                                <option value="100">Mostrar 100</option>
                            </select>
                        </form>
                    </div>
                    <div class="mb-3">
                        {{ $atletas->links('custom') }}
                    </div>
                </div>
            </div>
        </div>
    @elseif ($step == 2)
        <form wire:submit.prevent="store">
            {{-- Paso 2: Formulario de registro --}}
            <div class="container-fluid mt-4 text-white">
                <!-- Modal de exoneración -->
                <div class="modal fade" id="exoneracionModal" tabindex="-1" aria-labelledby="exoneracionModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content text-black">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exoneracionModalLabel">Exoneración de Responsabilidad</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Yo, <span class="fw-bold">{{ $nombre ?? 'No has llenado la inscripcion' }}</span>,
                                    el que suscribe, por medio de
                                    la presente libero a la World Karate Comission (WKC), a John Douvris, al Sensei Hugo
                                    Mendoza, a la sede del evento, y a todos los profesores y promotores de la Liga WKC
                                    México, a cualquiera y a todas las personas asociadas con este evento en cualquier
                                    capacidad, de cualquier responsabilidad, debido a accidentes o lesiones, etc.
                                </p>
                                <p>
                                    He leído, entendido y acuerdo respetar las reglas de competencia asociadas con
                                    eventos de la WKC y asumo toda responsabilidad legal o de cualquier tipo por
                                    infringir esas reglas. Adicionalmente, estoy completamente consciente de mi
                                    condición médica personal y aquí certifico que soy mental y físicamente apto para
                                    competir en este torneo.
                                </p>
                                <p>
                                    <strong>Nombre y firma del competidor o del Tutor (si es menor de 18 años).</strong>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success"
                                    data-bs-dismiss="modal">Aceptar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Formulario de registro -->
                <div class="row mb-3">
                    <!-- Email -->
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control custom-form-control" wire:model.lazy="email">
                        @error('email')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-4">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control custom-form-control" wire:model="nombre" readonly>
                        @error('nombre')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Apellido -->
                    <div class="col-md-4">
                        <label for="apellidos" class="form-label">Apellido</label>
                        <input type="text" class="form-control custom-form-control" wire:model="apellidos"
                            readonly>
                        @error('apellidos')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <!-- Fecha de Nacimiento -->
                    <div class="col-md-4">
                        <label for="fec" class="form-label">Fecha de Nacimiento</label>
                        <input type="date" class="form-control custom-form-control" wire:model="fec" readonly>
                        @error('fec')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cinta / Grado -->
                    <div class="col-md-4">
                        <label for="cinta" class="form-label mb-2">Grado</label>
                        <select id="cinta" name="cinta" wire:model="cinta" class="form-select mb-3">
                            <option value="">Selecciona un grado</option>
                            <option value="Principiante">Principiante</option>
                            <option value="Novato">Novato</option>
                            <option value="Intermedio">Intermedio</option>
                            <option value="Avanzado">Avanzado</option>
                            <option value="Negra">Cinta negra</option>
                        </select>
                        @error('cinta')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-4">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="number" class="form-control custom-form-control" wire:model="telefono">
                        @error('telefono')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3 justify-content-space-between">
                    <!-- Peso -->
                    <div class="col-md-4">
                        <label for="peso" class="form-label">Peso</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control custom-form-control"
                                wire:model="peso">
                            <span class="input-group-text">kg</span>
                        </div>
                        @error('peso')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Estatura -->
                    <div class="col-md-4">
                        {{-- <label for="estatura" class="form-label">Estatura</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control custom-form-control"
                                wire:model="estatura">
                            <span class="input-group-text">mts</span>
                        </div>
                        @error('estatura')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror --}}
                    </div>

                    <!-- Género -->
                    <div class="col-md-4">
                        <label for="genero" class="form-label">Género</label>
                        <div class="radio-container">
                            <input type="radio" class="genero-radio" id="male" name="genero"
                                value="masculino" wire:model="genero" disabled='disabled'>
                            <label for="masculino">Hombre</label>

                            <input class="ml-50 genero-radio" type="radio" id="female" name="genero"
                                value="femenino" wire:model="genero" disabled='disabled'>
                            <label for="femenino">Mujer</label>
                        </div>
                        @error('genero')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3 justify-content-space-between">
                    <div class="col-md-4 mb-3 text-center mt-5 d-flex justify-content-evenly">
                        <div class="form-group">
                            <input type="checkbox" id="aceptoTerminos" wire:model.lazy="aceptoTerminos"
                                class="genero-radio3 ms-2">
                            <label for="aceptoTerminos" class="text-center">
                                He leído y acepto la
                                <a href="#" class="underline cursor-pointer" data-bs-toggle="modal"
                                    data-bs-target="#exoneracionModal">
                                    responsiva
                                </a>
                            </label>
                            @error('aceptoTerminos')
                                <span class="text-warning">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-8">
                        <!-- Botón de envío -->
                        <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                            <button type="button" wire:click="decrementStep"
                                class="btn btn-registro p-8 w-40">Anterior</button>
                            <button type="button" wire:click="incrementStep"
                                {{ !$aceptoTerminos ? 'disabled' : '' }}
                                class="btn btn-registro p-8  w-40">Siguiente</button>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($step == 3)
            {{-- Paso 3: Selección de categorías --}}
            <div class="container" wire:key="categoria-{{ now() }}">
                <h5 class="text-white">Selecciona las categorias para registrarte</h5>
                <div class="table-responsive">
                    @if (!is_null($categoriasFiltradas) && $categoriasFiltradas->isNotEmpty())
                        @php
                            $categoriasAgrupadas = collect($categoriasFiltradas)->groupBy('forma_nombre');
                        @endphp
                        <table class="table table-dark mt-5">
                            @foreach ($categoriasAgrupadas as $forma => $categorias)
                                <thead>
                                    <tr>
                                        <th colspan="4">{{ $forma }}</th>
                                    </tr>
                                </thead>
                                <tbody style="border: 1px solid #fff;">
                                    @foreach ($categorias as $item)
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model="selectedCategories"
                                                    value="{{ $item['id'] }}">
                                            </td>
                                            <td>
                                                @php
                                                    $divisiones = explode(' / ', $item['division']);
                                                    $categoriaAdecuada = $item['categoria']['division'] ?? '';
                                                @endphp

                                                @foreach ($divisiones as $division)
                                                    <span
                                                        style="color: {{ trim($division) === trim($categoriaAdecuada) ? '#EBC010' : '#FFFFFF' }};">
                                                        {{ $division }}
                                                    </span>
                                                    @if (!$loop->last)
                                                        /
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                {{ $item['nombre'] }} &nbsp;
                                                @if ($item['edad_minima'] == 18)
                                                    +18 años
                                                @elseif ($item['edad_maxima'] == 10 && $item['edad_minima'] == 1)
                                                    -10 años
                                                @elseif ($item['edad_maxima'] == 6 && $item['edad_minima'] == 1)
                                                    -6 años
                                                @elseif ($item['edad_minima'] == 35)
                                                    +35 años
                                                @elseif ($item['edad_minima'] == 42)
                                                    +42 años
                                                @elseif ($item['edad_minima'] == 48)
                                                    +48 años
                                                @elseif ($item['edad_minima'] == 1 && $item['edad_maxima'] == 99)
                                                    Todas las edades
                                                @elseif(isset($item['edad_minima']) && isset($item['edad_maxima']))
                                                    {{ $item['edad_minima'] }} - {{ $item['edad_maxima'] }} años
                                                @else
                                                    N/A
                                                @endif
                                                &nbsp;
                                                @if ($item['genero'] == 'masculino')
                                                    (Varonil)
                                                @elseif ($item['genero'] == 'femenino')
                                                    (Femenino)
                                                @else
                                                    (Mixta)
                                                @endif
                                                &nbsp;
                                                @if (
                                                    ($item['forma_id'] == '12' || $item['forma_id'] == '13' || $item['forma_id'] == '14') &&
                                                        $item['peso_maximo'] <= 99 &&
                                                        isset($item['peso_maximo']))
                                                    <b style="color:red;">-</b> {{ $item['peso_maximo'] }} Kg
                                                @elseif (($item['forma_id'] == '12' || $item['forma_id'] == '13' || $item['forma_id'] == '14') && $item['peso_minimo'] <= 10)
                                                    <b style="color:green;">Todos los pesos</b>
                                                @elseif (
                                                    ($item['forma_id'] == '12' || $item['forma_id'] == '13' || $item['forma_id'] == '14') &&
                                                        $item['peso_maximo'] >= 100 &&
                                                        isset($item['peso_minimo']))
                                                    <b style="color:green;">+</b> {{ $item['peso_minimo'] }} Kg
                                                @endif
                                            <td class="text-right" style="color: #EBC010 !important;">área
                                                {{ $item['area'] ?? 'N/A' }} - {{ $item['horario'] ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            @endforeach
                        </table>
                    @else
                        <br><br><br>
                        <div class="alert alert-warning mt-8 mb-8" id="warning-message">
                            No hay categorías disponibles que coincidan con tu perfil.
                        </div>
                        <div class="text-center">
                            <button type="button" wire:click="cancelar"
                                class="btn btn-registro mb-3 mt-3 justify-center w-40">Cancelar</button>
                        </div>
                    @endif
                </div>
                @if (!is_null($categoriasFiltradas) && $categoriasFiltradas->isNotEmpty())
                    <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                        <button type="button" wire:click="decrementStep"
                            class="btn btn-registro p-8 w-40">Anterior</button>
                        <button type="button" wire:click="incrementStep"
                            class="btn btn-registro p-8 w-40">Siguiente</button>
                    </div>
                @endif
            </div>
        @elseif ($step == 4)
            {{-- Paso 4: Confirmación --}}
            <div class="container">
                <h5 class="text-white">Categorias seleccionadas</h5>
                <div class="table-responsive">
                    <table class="table table-dark mt-5">
                        @foreach ($selectedCategories as $index => $categoriaId)
                            @php
                                $categoria = $categoriasFiltradas->firstWhere('id', $categoriaId);
                            @endphp
                            @if ($categoria)
                                <thead>
                                    <tr>
                                    </tr>
                                </thead>
                                <tbody style="border: 1px solid #fff;">
                                    <tr>
                                        <td></td>
                                        <td>
                                            @php
                                                $divisiones = explode(' / ', $categoria['division']);
                                                $categoriaAdecuada = $categoria['categoria']['division'] ?? '';
                                            @endphp

                                            @foreach ($divisiones as $division)
                                                <span
                                                    style="color: {{ trim($division) === trim($categoriaAdecuada) ? '#EBC010' : '#FFFFFF' }};">
                                                    {{ $division }}
                                                </span>
                                                @if (!$loop->last)
                                                    /
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $categoria['nombre'] }} &nbsp;
                                            @if ($categoria['edad_minima'] == 18)
                                                +18 años
                                            @elseif ($categoria['edad_maxima'] == 10 && $categoria['edad_minima'] == 1)
                                                -10 años
                                            @elseif ($categoria['edad_maxima'] == 6 && $categoria['edad_minima'] == 1)
                                                -6 años
                                            @elseif ($categoria['edad_minima'] == 35)
                                                +35 años
                                            @elseif ($categoria['edad_minima'] == 42)
                                                +42 años
                                            @elseif ($categoria['edad_minima'] == 48)
                                                +48 años
                                            @elseif ($categoria['edad_minima'] == 1 && $categoria['edad_maxima'] == 99)
                                                Todas las edades
                                            @elseif(isset($categoria['edad_minima']) && isset($categoria['edad_maxima']))
                                                {{ $categoria['edad_minima'] }} - {{ $categoria['edad_maxima'] }} años
                                            @else
                                                N/A
                                            @endif
                                            &nbsp;
                                            @if ($categoria['genero'] == 'masculino')
                                                (Varonil)
                                            @elseif ($categoria['genero'] == 'femenino')
                                                (Femenino)
                                            @else
                                                (Mixta)
                                            @endif
                                            &nbsp;
                                            @if (
                                                ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                    $categoria['peso_maximo'] <= 99 &&
                                                    isset($categoria['peso_maximo']))
                                                <b style="color:red;">-</b> {{ $categoria['peso_maximo'] }} Kg
                                            @elseif (
                                                ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                    $categoria['peso_minimo'] <= 10)
                                                <b style="color:green;">Todos los pesos</b>
                                            @elseif (
                                                ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                    $categoria['peso_maximo'] >= 100 &&
                                                    isset($categoria['peso_minimo']))
                                                <b style="color:green;">+</b> {{ $categoria['peso_minimo'] }} Kg
                                            @endif
                                        <td class="text-right" style="color: #EBC010 !important;">área
                                            {{ $categoria['area'] ?? 'N/A' }} - {{ $categoria['horario'] ?? 'N/A' }}
                                        </td>
                                        <td><button type="button" class="btn btn-danger"
                                                wire:click="deseleccionarCategoria({{ $categoriaId }})"><i
                                                    class="bi bi-trash3"></i></button></td>
                                    </tr>
                                </tbody>
                            @endforelse
                        @endforeach
                    </table>
                </div>
                <div class="text-center">
                    <button type="button" wire:click="decrementStep"
                        class="btn btn-registro mb-3 mt-3 justify-center w-40">Anterior</button>
                    <button wire:click="store"
                        class="btn btn-registro mb-3 mt-3 justify-center w-40">Registrarse</button>
                </div>
            </div>
        @elseif ($step == 5)
            <br><br><br><br><br>
            <div class="container">
                <div class="alert alert-success text-center">
                    Gracias por registrarte. Tu registro ha sido procesado con éxito.
                    <br>
                    El costo total de tu registro es: <strong>${{ session('costoTotal') }} MXN</strong>
                </div>
            </div>
            <br><br><br>
    @endif
    </form>
</div>
