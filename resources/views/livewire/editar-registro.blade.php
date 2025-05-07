<div class="text-white">
    <h2 class="text-yellow">Editar Registro</h2>
    <form wire:submit.prevent="store">
        @if ($step == 1)
            @if ($registro)
                <div class="container-fluid mt-4 text-white">
                    <!-- Formulario de registro -->
                    <div class="row mb-4">
                        <!-- Email -->
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control custom-form-control" wire:model="email" readonly>
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

                    <div class="row mb-4">
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

                    <div class="row mb-4 justify-content-space-between">
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
                                <input type="number" step="0.01" class="form-control custom-form-control" wire:model="estatura">
                                <span class="input-group-text">mts</span>
                            </div>
                            @error('estatura') <span class="text-warning">{{ $message }}</span> @enderror --}}
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

                    <div class="row mb-4 justify-content-space-between">
                        <div class="col-md-3">
                        </div>

                        <div class="col-md-6">
                            <!-- Botón de envío -->
                            <div class="mb-3">
                                <button type="button" wire:click="incrementStep"
                                    class="btn btn-amarillo mb-3 mt-3 justify-center w-100">Siguiente</button>
                            </div>
                        </div>

                        <div class="col-md-3">
                        </div>
                    </div>
                </div>
            @else
                <p>Registro no encontrado.</p>
            @endif
        @elseif ($step == 2)
            {{-- Paso 2: Selección de categorías --}}
            <div class="container">
                <div class="table-responsive">
                    @forelse ($categoriasFiltradas as $item)
                        <table class="table table-dark mt-5">
                            <thead>
                                <tr>
                                    <th colspan="4">{{ $item['tipo'] === 'categoria' ? 'Categoría' : 'Fusión' }} -
                                        {{ $item['nombre'] }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" wire:model="selectedCategories"
                                            value="{{ $item['id'] }}">
                                    </td>
                                    <td>{{ $item['division'] ?? 'N/A' }}</td>
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
                                </tr>
                            </tbody>
                        </table>
                    @empty
                        <br><br><br>
                        <div class="alert alert-warning mt-8 mb-8" id="warning-message">
                            No hay categorías disponibles que coincidan con tu perfil.
                        </div>
                        <div class="text-center">
                            <button type="button" wire:click="cancelar"
                                class="btn btn-warning mb-3 mt-3 justify-center w-40">Cancelar</button>
                        </div>
                    @endforelse
                </div>
                @if (count($categoriasFiltradas) > 0)
                    <div class="text-center">
                        <button type="button" wire:click="decrementStep"
                            class="btn btn-amarillo mb-3 mt-3 justify-center w-40">Anterior</button>
                        &nbsp;<button wire:click="store"
                            class="btn btn-amarillo mb-3 mt-3 justify-center w-40">Registrarse</button>
                    </div>
                @endif
            </div>
        @elseif ($step == 3)
            {{-- Paso 3: Confirmación --}}
            <br><br><br><br><br>
            <div class="container">
                <div class="alert alert-info">
                    Gracias por registrarte. Tu registro ha sido procesado con éxito.
                    <br>
                    Te has registrado en las siguientes categorías:
                    <ul>
                        @foreach (session('selectedCategoriesInfo', collect()) as $categoria)
                            <li>{{ $categoria->nombre }} &nbsp;
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
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <br><br><br>
        @endif
    </form>
</div>
