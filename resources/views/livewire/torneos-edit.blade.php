<div class="container text-white">
    <div>
        <div>
            <div class="modal-header mb-3">
                <h5 class="modal-title">{{-- {{ $torneo_id ? 'Editar Torneo' : 'Añadir Torneo' }} --}} <svg xmlns="http://www.w3.org/2000/svg" width="20"
                        height="18" viewBox="0 0 20 18" fill="none">
                        <path
                            d="M11 2H19M11 6H16M11 12H19M11 16H16M1 2C1 1.73478 1.10536 1.48043 1.29289 1.29289C1.48043 1.10536 1.73478 1 2 1H6C6.26522 1 6.51957 1.10536 6.70711 1.29289C6.89464 1.48043 7 1.73478 7 2V6C7 6.26522 6.89464 6.51957 6.70711 6.70711C6.51957 6.89464 6.26522 7 6 7H2C1.73478 7 1.48043 6.89464 1.29289 6.70711C1.10536 6.51957 1 6.26522 1 6V2ZM1 12C1 11.7348 1.10536 11.4804 1.29289 11.2929C1.48043 11.1054 1.73478 11 2 11H6C6.26522 11 6.51957 11.1054 6.70711 11.2929C6.89464 11.4804 7 11.7348 7 12V16C7 16.2652 6.89464 16.5196 6.70711 16.7071C6.51957 16.8946 6.26522 17 6 17H2C1.73478 17 1.48043 16.8946 1.29289 16.7071C1.10536 16.5196 1 16.2652 1 16V12Z"
                            stroke="#EBC010" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    @if ($step == 1 || $step == 2)
                        Detalles del torneo
                    @elseif ($step == 3)
                        Categorías
                    @elseif ($step == 4)
                        Asignar área y horario a categorías
                    @elseif ($step == 5)
                        Asignar costos
                    @endif
                </h5>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="store" enctype="multipart/form-data" autocomplete="off">
                    @if ($step == 1)
                        {{-- Contenido del paso 1 --}}
                        <div class="row">
                            <div class="col-md-9">
                                <div class="form-group pt-3">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control custom-form-control" id="nombre"
                                        wire:model="nombre">
                                    @error('nombre')
                                        <span class="text-warning">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group pt-3">
                                    <label for="descripcion">Descripción</label>
                                    <textarea class="form-control custom-form-control" style="height: 200px;" id="descripcion" wire:model="descripcion"></textarea>
                                    @error('descripcion')
                                        <span class="text-warning">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group pt-3">
                                    <label for="banner">Banner</label>
                                    <label for="banner" class="drop-banner" id="dropcontainer"
                                        @if ($bannerPreview || $banner_actual) style="background-image: url('{{ $bannerPreview ?? $banner_actual }}');" @endif>
                                        @if (!$banner_actual && !$bannerPreview)
                                            <span class="drop-title">+</span>
                                        @endif
                                        <input type="file" class="form-control custom-form-control" id="banner"
                                            wire:model="banner" accept="image/png, image/jpeg, image/jpg">
                                    </label>
                                    @error('banner')
                                        <span class="text-warning">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <h5><svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21"
                                fill="none">
                                <path
                                    d="M10.117 1.99898C9.32135 1.99898 8.55829 2.31505 7.99568 2.87766C7.43307 3.44027 7.117 4.20333 7.117 4.99898C7.117 5.79463 7.43307 6.55769 7.99568 7.1203C8.55829 7.68291 9.32135 7.99898 10.117 7.99898C10.9126 7.99898 11.6757 7.68291 12.2383 7.1203C12.8009 6.55769 13.117 5.79463 13.117 4.99898C13.117 4.20333 12.8009 3.44027 12.2383 2.87766C11.6757 2.31505 10.9126 1.99898 10.117 1.99898ZM5.117 4.99898C5.11719 4.05295 5.38577 3.12639 5.89154 2.32691C6.3973 1.52742 7.1195 0.887828 7.97426 0.482409C8.82901 0.076989 9.78125 -0.077619 10.7204 0.0365414C11.6595 0.150702 12.5469 0.528945 13.2797 1.12734C14.0124 1.72574 14.5603 2.51973 14.8598 3.4171C15.1593 4.31448 15.1981 5.2784 14.9716 6.19692C14.7452 7.11545 14.2628 7.95088 13.5804 8.60618C12.8981 9.26149 12.0439 9.70978 11.117 9.89898V15.999H9.117V9.89898C7.98781 9.6681 6.97299 9.05436 6.24414 8.16152C5.5153 7.26868 5.11714 6.15154 5.117 4.99898ZM1.222 9.99898H6.117V11.999H3.012L2.234 18.999H18L17.222 11.999H14.117V9.99898H19.012L20.234 20.999H0L1.222 9.99898Z"
                                    fill="#EBC010" />
                            </svg> Detalles de la ubicación</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <iframe width="100%" height="290" frameborder="0"
                                    style="border:0; border-radius: 10px;"
                                    src="https://www.google.com/maps?q={{ urlencode($direccion) }}&output=embed&zoom=25"
                                    allowfullscreen></iframe>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group pt-3">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control custom-form-control" id="direccion"
                                        wire:model="direccion">
                                    @error('direccion')
                                        <span class="text-warning">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                            <button type="button" wire:click="incrementStep"
                                class="btn btn-guardar p-8 w-50">Siguiente</button>
                        </div>
                    @elseif ($step == 2)
                        {{-- Contenido del paso 2 --}}
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="col-md-3 form-group pt-3">
                                <label for="fecha_evento">Fecha de Evento</label>
                                <input type="datetime-local" class="form-control custom-form-control" id="fecha_evento"
                                    wire:model="fecha_evento" min="{{ $fecha_actual }}">
                                @error('fecha_evento')
                                    <span class="text-warning">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group pt-3">
                            </div>
                            <div class="col-md-3 form-group pt-3">
                                <label for="fecha_registro">Fecha de Registro</label>
                                <input type="datetime-local" class="form-control custom-form-control"
                                    id="fecha_registro" wire:model="fecha_registro" min="{{ $fecha_actual }}">
                                @error('fecha_registro')
                                    <span class="text-warning">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3 form-group pt-3">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group pt-3"> Rankings
                                @foreach ($rankings as $index => $ranking)
                                    <div class="d-flex">
                                        <input type="text" class="form-control custom-form-control mb-3"
                                            wire:model="rankings.{{ $index }}"
                                            placeholder="Ranking {{ $index + 1 }}">
                                        @error("rankings.{$index}")
                                            <span class="text-warning">{{ $message }}</span>
                                        @enderror
                                        <button type="button" wire:click="eliminarRanking({{ $index }})"
                                            class="btn btn-danger btn-sm ml-2 mb-3">Eliminar</button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="agregarRanking" class="btn btn-sm btn-add mb-3"
                                    style="border-radius: 10px; background: #EBC010;">+ Añadir</button>
                            </div>
                            <div class="col-md-6 form-group pt-3"> Premios
                                @foreach ($premios as $index => $premio)
                                    <div class="d-flex">
                                        <input type="text" class="form-control custom-form-control mb-3"
                                            wire:model="premios.{{ $index }}"
                                            placeholder="Premio {{ $index + 1 }}">
                                        @error("premios.{$index}")
                                            <span class="text-warning">{{ $message }}</span>
                                        @enderror
                                        <button type="button" wire:click="eliminarPremio({{ $index }})"
                                            class="btn btn-danger btn-sm ml-2 mb-3">Eliminar</button>
                                    </div>
                                @endforeach
                                <button type="button" wire:click="agregarPremio" class="btn btn-sm btn-add mb-3"
                                    style="border-radius: 10px; background: #EBC010;">+ Añadir</button>
                            </div>
                        </div>
                        <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                            <button type="button" wire:click="decrementStep"
                                class="btn btn-guardar p-8 w-40">Anterior</button>
                            <button type="button" wire:click="incrementStep"
                                class="btn btn-guardar p-8 w-40">Siguiente</button>
                        </div>
                    @elseif ($step == 3)
                        <div class="input-group">
                            <input type="text" wire:model.live="buscar" placeholder="Buscar categorias"
                                class="buscar-torneo" style="width: 40%; border: none !important;">
                            <span class="input-group-text"
                                style="border-radius: 0 10px 10px 0px !important; border: none !important; background: #323131 !important; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.05) !important;"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                    viewBox="0 0 21 21" fill="none">
                                    <path
                                        d="M20 20L15.514 15.506M18 9.5C18 11.7543 17.1045 13.9163 15.5104 15.5104C13.9163 17.1045 11.7543 18 9.5 18C7.24566 18 5.08365 17.1045 3.48959 15.5104C1.89553 13.9163 1 11.7543 1 9.5C1 7.24566 1.89553 5.08365 3.48959 3.48959C5.08365 1.89553 7.24566 1 9.5 1C11.7543 1 13.9163 1.89553 15.5104 3.48959C17.1045 5.08365 18 7.24566 18 9.5V9.5Z"
                                        stroke="#F9F9F9" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </span>
                        </div>
                        @if (session()->has('message'))
                            <br>
                            <div class="alert alert-success text-center" style="font-size: 20px;">
                                {{ session('message') }}
                            </div>
                        @endif
                        @if (session()->has('error'))
                            <br>
                            <div class="alert alert-warning text-center" style="font-size: 20px;">
                                {{ session('error') }}
                            </div>
                        @endif
                        {{-- Contenido del paso 3 --}}
                        <div class="table-responsive">
                            @foreach ($secciones as $seccion)
                                <h2 class="text-yellow"><br>{{ $seccion->nombre }}<br></h2>
                                @foreach ($seccion->formas as $forma)
                                    <table id="mitabla" class="table table-dark mt-5">
                                        <thead>
                                            <tr>
                                                <th colspan="2">{{ $forma->nombre }}</th>
                                                <th colspan="2">
                                                    <div class="text-end">
                                                        <!-- Botón Combinar -->
                                                        @if (!$mostrarBotonConfirmar)
                                                            <button type="button"
                                                                wire:click="mostrarBotonConfirmacion"
                                                                class="btn btn-guardar p-8 w-40">Combinar</button>
                                                        @endif

                                                        <!-- Botón Confirmar Combinación, que se muestra solo después de presionar Combinar -->
                                                        @if ($mostrarBotonConfirmar)
                                                            <button type="button" wire:click="combinarCategorias"
                                                                class="btn btn-guardar p-8 w-40">Confirmar
                                                                combinación</button>
                                                        @endif

                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($forma->categorias as $categoria)
                                                <tr
                                                    @if (array_key_exists($categoria->id, $this->categoriasYaFusionadas)) style="opacity: 0.6; pointer-events: none;" @endif>
                                                    <td><input type="checkbox" wire:model.live="selectedCategories"
                                                            value="{{ $categoria->id }}"
                                                            @if (array_key_exists($categoria->id, $this->categoriasYaFusionadas)) disabled @endif></td>
                                                    <td>{{ $categoria->division }}
                                                        @if (array_key_exists($categoria->id, $this->categoriasYaFusionadas))
                                                            <span class="badge bg-warning text-dark">Ya combinada en:
                                                                {{ $this->categoriasYaFusionadas[$categoria->id] }}</span>
                                                        @endif
                                                    </td>
                                                    <td> {{ $categoria->nombre }} &nbsp;
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
                                                            {{ $categoria['edad_minima'] }} -
                                                            {{ $categoria['edad_maxima'] }} años
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
                                                            <b style="color:red;">-</b>
                                                            {{ $categoria['peso_maximo'] }} Kg
                                                        @elseif (
                                                            ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                                $categoria['peso_minimo'] <= 10)
                                                            <b style="color:green;">Todos los pesos</b>
                                                        @elseif (
                                                            ($categoria['forma_id'] == '12' || $categoria['forma_id'] == '13' || $categoria['forma_id'] == '14') &&
                                                                $categoria['peso_maximo'] >= 100 &&
                                                                isset($categoria['peso_minimo']))
                                                            <b style="color:green;">+</b>
                                                            {{ $categoria['peso_minimo'] }}
                                                            Kg
                                                        @endif
                                                    </td>
                                                    @if ($mostrarBotonConfirmar)
                                                        <td><input type="checkbox"
                                                                wire:model="seleccionarCategoriasCombinadas"
                                                                value="{{ $categoria->id }}"></td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            @endforeach
                        </div>
                        <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                            <button type="button" wire:click="decrementStep"
                                class="btn btn-guardar p-8 w-40">Anterior</button>
                            <button type="button" wire:click="incrementStep"
                                class="btn btn-guardar p-8 w-50">Siguiente</button>
                        </div>
                    @elseif ($step == 4)
                        <div>
                            <div class="table-responsive">
                                <table class="table table-dark mt-5">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Tipo</th>
                                            <th colspan="2">Nombre</th>
                                            <th>División</th>
                                            <th>Área</th>
                                            <th>Horario</th>
                                            <th colspan="2">
                                                <div class="text-center p-3">
                                                    <!-- Botón Combinar -->
                                                    @if (!$mostrarBotonCombinacionAreas)
                                                        <button type="button"
                                                            wire:click="mostrarBotonCombinacionHorarios"
                                                            class="btn btn-guardar p-8 w-100">Combinar areas y
                                                            horarios</button>
                                                    @endif

                                                    <!-- Botón Confirmar Combinación, que se muestra solo después de presionar Combinar -->
                                                    @if ($mostrarBotonCombinacionAreas)
                                                        <input type="text" class="form-control custom-form-control"
                                                            wire:model="areaCompartida"
                                                            placeholder="Área compartida">&nbsp;
                                                        <input type="datetime-local"
                                                            class="form-control custom-form-control"
                                                            wire:model="horarioCompartido"
                                                            placeholder="Horario compartido"
                                                            min="{{ $fecha_evento }}"><br>
                                                        <button type="button" class="btn btn-guardar p-8"
                                                            wire:click="aplicarAreaHorarioCompartidos">Aplicar a
                                                            seleccionados</button>
                                                    @endif
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datosCombinados as $dato)
                                            <tr>
                                                @if ($mostrarBotonCombinacionAreas)
                                                    <td>
                                                        <input type="checkbox" wire:model="combinacionareasHorarios"
                                                            value="{{ $dato['id'] }}">
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                                <td>{{ $dato['tipo'] === 'categoria' ? 'Categoría' : 'Combinación' }}
                                                </td>
                                                <td colspan="2">{{ $dato['nombre'] }}</td>
                                                <td>{{ $dato['division'] }}</td>
                                                @if (!in_array($dato['id'], $this->combinacionareasHorarios))
                                                    <td>
                                                        <input type="number" class="form-control custom-form-control"
                                                            wire:model="areas.{{ $dato['id'] }}"
                                                            placeholder="Área">
                                                        @error('areas.' . $dato['id'])
                                                            <span class="text-warning">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="datetime-local"
                                                            class="form-control custom-form-control"
                                                            wire:model="horarios.{{ $dato['id'] }}"
                                                            placeholder="Horario"
                                                            min="{{ \Carbon\Carbon::parse($fecha_evento)->format('Y-m-d\TH:i') }}">
                                                        @error('horarios.' . $dato['id'])
                                                            <span class="text-warning">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td></td>
                                                    <td><button type="button" class="btn btn-danger"
                                                            wire:click="deseleccionarCategoria({{ $dato['id'] }}, '{{ $dato['tipo'] }}')"><i
                                                                class="bi bi-trash3"></i></button></td>
                                                @else
                                                    <td><input type="number" class="form-control custom-form-control"
                                                            wire:model="areas.{{ $dato['id'] }}"
                                                            placeholder="{{ $this->areas[$dato['id']] ?? 'No definido' }}">
                                                    </td>
                                                    <td><input type="datetime-local"
                                                            class="form-control custom-form-control"
                                                            wire:model="horarios.{{ $dato['id'] }}"
                                                            placeholder="{{ $this->horarios[$dato['id']] ? $this->horarios[$dato['id']] : 'No definido' }}"
                                                            min="{{ \Carbon\Carbon::parse($fecha_evento)->format('Y-m-d\TH:i') }}">
                                                    </td>
                                                    <td></td>
                                                    <td><button type="button" class="btn btn-danger"
                                                            wire:click="deseleccionarCategoria({{ $dato['id'] }}, '{{ $dato['tipo'] }}')"><i
                                                                class="bi bi-trash3"></i></button></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                                <button type="button" wire:click="decrementStep"
                                    class="btn btn-guardar p-8 w-40">Anterior</button>
                                <button type="button" wire:click="incrementStep"
                                    class="btn btn-guardar p-8 w-50">Siguiente</button>
                            </div>
                        </div>
                    @elseif ($step == 5)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-dark mt-1">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Fecha</th>
                                                <th>Pre registro</th>
                                                <th>Registro</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($torneosPrecios as $index => $torneoPrecio)
                                                <tr>
                                                    <td>{{ $torneoPrecio['nombre_tipo_formas'] }}</td>
                                                    <td>
                                                        <input type="date" class="form-control custom-form-control"
                                                            value="{{ $torneoPrecio['fecha'] }}"
                                                            wire:model="torneosPrecios.{{ $index }}.fecha">
                                                        @error('torneosPrecios.' . $index . '.fecha')
                                                            <span class="text-warning">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control custom-form-control"
                                                            value="{{ $torneoPrecio['costo_pre_registro'] }}"
                                                            wire:model="torneosPrecios.{{ $index }}.costo_pre_registro"
                                                            placeholder="Pre registro">
                                                        @error('torneosPrecios.' . $index . '.costo_pre_registro')
                                                            <span class="text-warning">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control custom-form-control"
                                                            value="{{ $torneoPrecio['costo_registro'] }}"
                                                            wire:model="torneosPrecios.{{ $index }}.costo_registro"
                                                            placeholder="Registro">
                                                        @error('torneosPrecios.' . $index . '.costo_registro')
                                                            <span class="text-warning">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            </div>
            <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                <button type="button" wire:click="decrementStep" class="btn btn-guardar p-8 w-40">Anterior</button>
                <button type="button" wire:click="store"
                    class="btn btn-guardar p-8 w-40">{{ $torneo_id ? 'Actualizar' : 'Guardar' }}</button>
            </div>
            @endif
            </form>
        </div>
    </div>
</div>
</div>
