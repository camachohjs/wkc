<div class="mt-3 container text-white">
    <div class="modal-header justify-content-center">
        <h4>{{ $competidor_id ? 'Editar Competidor' : 'Añadir Competidor' }}</h4>
    </div>
    <div class="modal-body" style="padding: 2rem !important;">
        <form wire:submit.prevent="store">
            @if (session()->has('success'))
                <div class="alert alert-success text-center" role="alert" style="font-size: 20px;">
                    {{ session('success') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger text-center" role="alert" style="font-size: 20px;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="row mb-3">
                @if (Auth::user()->hasRole('admin'))
                    <div class="col-md-4">
                        <label for="tipoIngreso" class="form-label">Selecciona una opción</label>
                        <select class="form-select" wire:model.change="tipoIngreso"
                            @if (Auth::user()->hasRole('supervisor')) disabled @endif>
                            <option value="fecha">Fecha de Nacimiento</option>
                            <option value="edad">Edad</option>
                        </select>
                        @error('tipoIngreso')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                @if ($tipoIngreso == '')
                    <div class="col-md-4">
                    </div>
                @endif

                @if ($tipoIngreso === 'fecha')
                    @if (Auth::user()->hasRole('supervisor'))
                        <div class="col-md-6">
                        @elseif (Auth::user()->hasRole('admin'))
                            <div class="col-md-4">
                    @endif
                    <label for="fec" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control custom-form-control" wire:model.lazy="fec">
                    @error('fec')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
            </div>
            @endif

            @if ($tipoIngreso === 'edad')
                <div class="col-md-4">
                    <label for="edad" class="form-label">Edad</label>
                    <input type="number" class="form-control custom-form-control" wire:model.lazy="edad"
                        min="1">
                    @error('edad')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            @if ($isVisible)
                @if (Auth::user()->hasRole('supervisor'))
                    <div class="col-md-6">
                    @elseif (Auth::user()->hasRole('admin'))
                        <div class="col-md-4">
                @endif
                <label for="email" class="form-label">Email (opcional)</label>
                <input type="text" class="form-control custom-form-control" wire:model="email">
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
    </div>
    @endif
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control custom-form-control" wire:model="nombre">
        @error('nombre')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="apellidos" class="form-label">Apellido</label>
        <input type="text" class="form-control custom-form-control" wire:model="apellidos">
        @error('apellidos')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="cinta" class="form-label mb-2">Grado</label>
        <select id="cinta" name="cinta" wire:model.lazy="cinta" class="form-select mb-3">
            <option value="">Selecciona un grado</option>
            <option value="Principiante">Principiante</option>
            <option value="Novato">Novato</option>
            <option value="Intermedio">Intermedio</option>
            <option value="Avanzado">Avanzado</option>
            <option value="Negra">Cinta negra</option>
        </select>
        @error('cinta')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="telefono" class="form-label">Teléfono (opcional)</label>
        <input type="number" class="form-control custom-form-control" wire:model="telefono">
        @error('telefono')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="row mb-3 justify-content-space-between">
    <div class="col-md-3">
        <label for="peso" class="form-label">Peso (kg)</label>
        <div class="input-group">
            <input type="number" step="0.01" class="form-control custom-form-control" wire:model="peso">
        </div>
        @error('peso')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-3">
        {{-- <label for="estatura" class="form-label">Estatura(mts)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control custom-form-control" wire:model="estatura">
                    </div>
                    @error('estatura') <span class="text-danger">{{ $message }}</span> @enderror --}}
    </div>

    <div class="col-md-6">
        <label for="genero" class="form-label">Género</label>
        <div class="radio-container">
            <input type="radio" class="genero-radio" id="male" name="genero" value="masculino"
                wire:model="genero">
            <label for="masculino">Hombre</label>

            <input class="ml-50 genero-radio" type="radio" id="female" name="genero" value="femenino"
                wire:model="genero">
            <label for="femenino">Mujer</label>
        </div>
        @error('genero')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
<div class="row mb-3 justify-content-space-between">
    <div class="col-md-4">
        <label for="escuela" class="form-label mb-2">Escuela</label>
        <select id="escuela" name="escuela" wire:model="escuelaSeleccionada" class="form-select"
            wire:change="cargarMaestros">
            <option value="">Selecciona una Escuela</option>
            @foreach ($escuelas as $escuela)
                <option value="{{ $escuela->id }}" {{ $escuelaSeleccionada == $escuela->id ? 'selected' : '' }}>
                    {{ $escuela->nombre }}
                </option>
            @endforeach
        </select>
        <br>
        <label for="maestro" class="form-label mb-2">Maestro</label>
        <select id="maestro" name="maestro" wire:model="maestroSeleccionado" class="form-select">
            <option value="">Selecciona un Maestro</option>
            @foreach ($maestros as $maestro)
                <option value="{{ $maestro->id }}" {{ $maestroSeleccionado == $maestro->id ? 'selected' : '' }}>
                    {{ $maestro->nombre . ' ' . $maestro->apellidos }}
                </option>
            @endforeach
        </select>
        <br>
        <label for="nacionalidad" class="form-label mb-2">Nacionalidad</label>
        <select id="nacionalidad" name="nacionalidad" wire:model="nacionalidad" class="form-select">
            <option value="">Selecciona una nacionalidad</option>
            @foreach ($nacionalidades as $nacionalidad)
                <option value="{{ $nacionalidad['nombre'] }}"
                    data-image="https://flagcdn.com/16x12/{{ $nacionalidad['codigo'] }}.png">
                    {{ $nacionalidad['nombre'] }}
                </option>
            @endforeach
        </select>
        @error('nacionalidad')
            <span class="text-warning">{{ $message }}</span>
        @enderror
        <br>
        <div class="form-group d-flex justify-content-between">
            @if ($esVisible)
                <div class="d-flex align-items-center">
                    <input type="checkbox" id="mayor_de_edad" wire:model="mayor_de_edad" class="genero-radio2">
                    <label for="mayor_de_edad" class="form-label mb-2">Habilitar como mayor de 18
                        años</label>
                </div>
            @endif
            @if ($esVisibleCinta)
                <div class=" d-flex align-items-center">
                    <input type="checkbox" id="cinta_negra" wire:model="cinta_negra" class="genero-radio2">
                    <label for="cinta_negra" class="form-label mb-2">Habilitar como cinta negra</label>
                </div>
            @endif
        </div>
        <br>
    </div>
    <div class="col-md-8">
        <div class="form-group pt-3 text-center">
            <label for="foto" style="color: #fff6">Da click para cambiar foto de perfil</label>
            <label for="foto" class="drop-banner" id="dropcontainer"
                style="background-image: url('{{ $foto_actual ? asset($foto_actual) : asset('libs/images/profile/user-1.png') }}');">
                <input type="file" class="form-control custom-form-control" id="foto" wire:model="foto"
                    accept="image/png, image/jpeg, image/jpg">
            </label>
            @error('foto')
                <span class="text-warning">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>

@if ($isVisible)
    @if (!$competidor_id)
        <div class="row mb-3 mt-5 justify-content-center align-items-center">
            <div class="col-md-6 mb-3">
                <h6 class="texto1">Instrucciones de creación de contraseña</h6>
                <ol class="texto1">
                    <p>Necesario</p>
                    <li>Mínimo 15 Caracteres</li>
                    <li>Debe contener 2 números</li>
                    <li>Debe tener al menos 2 letras</li>
                    <li>Debe tener al menos 1 caracter especial<br>
                        como !@#$%^&*()</li>
                </ol>
            </div>

            <div class="col-md-6 mb-3 flexi">
                <p class="texto2">Opcional (no recomendado)</p>
                <ul class="texto2 no-listada">
                    <li>No números consecutivos</li>
                    <li>No letras consecutivas</li>
                    <li>No datos personales</li><br><br>
                </ul>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <label for="password" class="form-label">Contraseña</label>
                <div class="input-group">
                    <input type="{{ $showPassword ? 'text' : 'password' }}" class="form-control custom-form-control"
                        wire:model.lazy="password">
                    <button type="button" class="blanco btn btn-outline-secondary" wire:click="toggleShowPassword">
                        {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                    </button>
                </div>
                {{-- <div class="mt-2">
                                <div class="progress">
                                    <div class="progress-bar amarillo" role="progressbar" style="width: {{ $passwordStrength * 10 }}%;" aria-valuenow="{{ $passwordStrength }}" aria-valuemin="1" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-1 mt-1 text-center">
                                    <span class="float-start">Débil</span>
                                    <span>Media</span>
                                    <span class="float-end">Fuerte</span>
                                </p>
                            </div> --}}
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                <div class="input-group">
                    <input type="{{ $showPassword ? 'text' : 'password' }}" class="form-control custom-form-control"
                        wire:model.lazy="password_confirmation">
                    <button type="button" class="blanco btn btn-outline-secondary" wire:click="toggleShowPassword">
                        {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
    @endif
@endif
<div class="mt-3 text-center">
    <button class="btn btn-guardar p-8 w-50" type="submit">Guardar</button>
</div>
</form>
</div>
</div>
