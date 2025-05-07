<div class="container p-5 text-white">
    <form wire:submit.prevent="register">
        <h2 class="text-center">Registro</h2>

        @if (session()->has('success'))
            <div class="alert alert-success text-center" style="font-size: 20px;">
                {{ session('success') }} <br>
                <a class="btn btn2" href="{{ route('login') }}">
                    Iniciar Sesión
                </a>
            </div>
        @endif

        <div class="row text-center mb-4">
            <div class="col-md-6">
                <button type="button"
                    class="btn boton-maestro mx-auto {{ $selectedButton === 'alumno' ? 'seleccionado' : '' }}"
                    wire:click="selectUserType('alumno')" data-tipo="alumno">
                    <img src="{{ asset('Img/registro/mdi_karate.png') }}" alt="Alumno">
                    <p>Soy Competidor</p>
                </button>
            </div>
            <div class="col-md-6">
                <button type="button"
                    class="btn boton-maestro mx-auto {{ $selectedButton === 'maestro' ? 'seleccionado' : '' }}"
                    wire:click="selectUserType('maestro')" data-tipo="maestro">
                    <img src="{{ asset('Img/registro/icon_karate.png') }}" alt="Sensei">
                    <p>Sensei (Entrenador)</p>
                </button>
            </div>
        </div>

        @error('userType')
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        @enderror

        @if ($userType == 'alumno')
            <!-- Mostrar campos específicos para alumnos -->

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control custom-form-control" wire:model="nombre">
                    @error('nombre')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="apellidos" class="form-label">Apellido</label>
                    <input type="text" class="form-control custom-form-control" wire:model="apellidos">
                    @error('apellidos')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control custom-form-control" wire:model="email">
                    @error('email')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-md-4">
                    <label for="fec" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control custom-form-control" wire:model="fec"
                        max="{{ date('Y-m-d') }}">
                    @error('fec')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

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

                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="number" class="form-control custom-form-control" wire:model="telefono">
                    @error('telefono')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 justify-content-space-between">
                <div class="col-md-2">4
                    <label for="peso" class="form-label">Peso</label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control custom-form-control" wire:model="peso">
                        <span class="input-group-text">kg</span>
                    </div>
                    @error('peso')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-2">
                    {{-- <label for="estatura" class="form-label">Estatura</label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="estatura">
                    <span class="input-group-text">mts</span>
                </div>
                @error('estatura') <span class="text-warning">{{ $message }}</span> @enderror --}}
                </div>

                <div class="col-md-4">
                    <label for="genero" class="form-label">Género</label>
                    <div class="radio-container">
                        <input type="radio" class="genero-radio" id="male" name="genero" value="masculino"
                            wire:model="genero">
                        <label for="masculino">Hombre</label>

                        <input class="ml-50 genero-radio" type="radio" id="female" name="genero"
                            value="femenino" wire:model="genero">
                        <label for="femenino">Mujer</label>
                    </div>
                    @error('genero')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 justify-content-space-between">
                <div class="col-md-4">
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
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                </div>
            </div>

            <div class="row mb-3 justify-content-space-between">
                <div class="col-md-4">
                    <label for="escuela" class="form-label mb-2">Escuela</label>
                    <select id="escuela" name="escuela" wire:model="escuelaSeleccionada" class="form-select"
                        wire:change="cargarMaestros">
                        <option value="">Selecciona una escuela</option>
                        @foreach ($escuelas as $escuela)
                            <option value="{{ $escuela->id }}">{{ $escuela->nombre }}</option>
                        @endforeach
                        {{-- <option value="nueva-escuela">Agregar nueva escuela</option> --}}
                    </select>
                    @error('escuelaSeleccionada')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="maestro" class="form-label mb-2">Maestro</label>
                    <select id="maestro" name="maestro" wire:model="maestroSeleccionado" class="form-select">
                        <option value="">Selecciona un maestro</option>
                        @foreach ($maestros as $maestro)
                            <option value="{{ $maestro->id }}">{{ $maestro->nombre . ' ' . $maestro->apellidos }}
                            </option>
                        @endforeach
                        {{-- <option value="nuevo-maestro"> Agregar nuevo maestro</option> --}}
                    </select>
                    @error('maestroSeleccionado')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="foto" class="drop-container" id="dropcontainer">
                        <span class="drop-title">Suelte su imagen aqui <br>
                            o </span>
                        <input type="file" class="form-control custom-form-control" id="foto"
                            wire:model="foto" accept="image/png, image/jpeg, image/jpg">
                    </label>
                    @error('foto')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 mt-5 justify-content-center align-items-center">
                <div class="col-md-4 mb-3">
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

                <div class="col-md-4 mb-3 flexi">
                    <p class="texto2">Opcional (no recomendado)</p>
                    <ul class="texto2 no-listada">
                        <li>No números consecutivos</li>
                        <li>No letras consecutivas</li>
                        <li>No datos personales</li><br><br>
                    </ul>
                </div>

                <div class="col-md-4 text-center">
                    <img src="{{ asset('Img/registro/contrasena.png') }}" alt="Instrucciones Contraseña">
                </div>
            </div>


            <div class="row mb-5 justify-content-center">
                <div class="col-md-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="{{ $showPassword ? 'text' : 'password' }}"
                            class="form-control custom-form-control" wire:model.lazy="password">
                        <button type="button" class="blanco btn btn-outline-secondary"
                            wire:click="toggleShowPassword">
                            {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                        </button>
                    </div>
                    {{-- <div class="mt-2">
                        <div class="progress">
                            <div class="progress-bar amarillo" role="progressbar"
                                style="width: {{ $passwordStrength * 10 }}%;"
                                aria-valuenow="{{ $passwordStrength }}" aria-valuemin="1" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-1 mt-1 text-center">
                            <span class="float-start">Débil</span>
                            <span>Media</span>
                            <span class="float-end">Fuerte</span>
                        </p>
                    </div> --}}
                    @error('password')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <input type="{{ $showPassword ? 'text' : 'password' }}"
                            class="form-control custom-form-control" wire:model.lazy="password_confirmation">
                        <button type="button" class="blanco btn btn-outline-secondary"
                            wire:click="toggleShowPassword">
                            {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn boton-guardar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path
                            d="M21 7V19C21 19.55 20.804 20.021 20.412 20.413C20.02 20.805 19.5493 21.0007 19 21H5C4.45 21 3.979 20.804 3.587 20.412C3.195 20.02 2.99934 19.5493 3 19V5C3 4.45 3.196 3.979 3.588 3.587C3.98 3.195 4.45067 2.99934 5 3H17L21 7ZM19 7.85L16.15 5H5V19H19V7.85ZM12 18C12.8333 18 13.5417 17.7083 14.125 17.125C14.7083 16.5417 15 15.8333 15 15C15 14.1667 14.7083 13.4583 14.125 12.875C13.5417 12.2917 12.8333 12 12 12C11.1667 12 10.4583 12.2917 9.875 12.875C9.29167 13.4583 9 14.1667 9 15C9 15.8333 9.29167 16.5417 9.875 17.125C10.4583 17.7083 11.1667 18 12 18ZM6 10H15V6H6V10ZM5 7.85V19V5V7.85Z"
                            fill="black" />
                    </svg>
                    Guardar
                </button>
            </div>
        @elseif ($userType == 'maestro')
            <!-- Mostrar campos específicos para maestros -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control custom-form-control" wire:model="nombre">
                    @error('nombre')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="apellidos" class="form-label">Apellido</label>
                    <input type="text" class="form-control custom-form-control" wire:model="apellidos">
                    @error('apellidos')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control custom-form-control" wire:model="email">
                    @error('email')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-md-4">
                    <label for="fec" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control custom-form-control" wire:model="fec"
                        max="{{ date('Y-m-d') }}">
                    @error('fec')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

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

                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="number" class="form-control custom-form-control" wire:model="telefono">
                    @error('telefono')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 justify-content-space-between">
                <div class="col-md-2">
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

                <div class="col-md-2">
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


                <div class="col-md-4">
                    <label for="genero" class="form-label">Género</label>
                    <div class="radio-container">
                        <input type="radio" class="genero-radio" id="male" name="genero" value="masculino"
                            wire:model="genero">
                        <label for="masculino">Hombre</label>

                        <input class="ml-50 genero-radio" type="radio" id="female" name="genero"
                            value="femenino" wire:model="genero">
                        <label for="femenino">Mujer</label>
                    </div>
                    @error('genero')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 justify-content-space-between">
                <div class="col-md-4">
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
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                </div>
            </div>

            <div class="row mb-3 justify-content-space-between">
                <div class="col-md-4">
                    <label for="escuela" class="form-label mb-2">Escuela</label>
                    <select id="escuela" name="escuela" wire:model="escuelaSeleccionada" class="form-select"
                        wire:change="cargarMaestros">
                        <option value="">Selecciona una escuela</option>
                        @foreach ($escuelas as $escuela)
                            <option value="{{ $escuela->id }}">{{ $escuela->nombre }}</option>
                        @endforeach
                    </select>
                    @error('escuelaSeleccionada')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-8">
                    <label for="foto" class="drop-container" id="dropcontainer">
                        <span class="drop-title">Suelte su imagen aqui <br>
                            o </span>
                        <input type="file" class="form-control custom-form-control" id="foto"
                            wire:model="foto" accept="image/png, image/jpeg, image/jpg">
                    </label>
                    @error('foto')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3 mt-5 justify-content-center align-items-center">
                <div class="col-md-4 mb-3">
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

                <div class="col-md-4 mb-3 flexi">
                    <p class="texto2">Opcional (no recomendado)</p>
                    <ul class="texto2 no-listada">
                        <li>No números consecutivos</li>
                        <li>No letras consecutivas</li>
                        <li>No datos personales</li><br><br>
                    </ul>
                </div>

                <div class="col-md-4 text-center">
                    <img src="{{ asset('Img/registro/contrasena.png') }}" alt="Instrucciones Contraseña">
                </div>
            </div>

            <div class="row mb-5 justify-content-center">
                <div class="col-md-4">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <input type="{{ $showPassword ? 'text' : 'password' }}"
                            class="form-control custom-form-control" wire:model.lazy="password">
                        <button type="button" class="blanco btn btn-outline-secondary"
                            wire:click="toggleShowPassword">
                            {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="progress">
                            <div class="progress-bar amarillo" role="progressbar"
                                style="width: {{ $passwordStrength * 10 }}%;"
                                aria-valuenow="{{ $passwordStrength }}" aria-valuemin="1" aria-valuemax="100"></div>
                        </div>
                        <p class="mb-1 mt-1 text-center">
                            <span class="float-start">Débil</span>
                            <span>Media</span>
                            <span class="float-end">Fuerte</span>
                        </p>
                    </div>
                    @error('password')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <div class="input-group">
                        <input type="{{ $showPassword ? 'text' : 'password' }}"
                            class="form-control custom-form-control" wire:model.lazy="password_confirmation">
                        <button type="button" class="blanco btn btn-outline-secondary"
                            wire:click="toggleShowPassword">
                            {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="text-warning">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn boton-guardar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none">
                        <path
                            d="M21 7V19C21 19.55 20.804 20.021 20.412 20.413C20.02 20.805 19.5493 21.0007 19 21H5C4.45 21 3.979 20.804 3.587 20.412C3.195 20.02 2.99934 19.5493 3 19V5C3 4.45 3.196 3.979 3.588 3.587C3.98 3.195 4.45067 2.99934 5 3H17L21 7ZM19 7.85L16.15 5H5V19H19V7.85ZM12 18C12.8333 18 13.5417 17.7083 14.125 17.125C14.7083 16.5417 15 15.8333 15 15C15 14.1667 14.7083 13.4583 14.125 12.875C13.5417 12.2917 12.8333 12 12 12C11.1667 12 10.4583 12.2917 9.875 12.875C9.29167 13.4583 9 14.1667 9 15C9 15.8333 9.29167 16.5417 9.875 17.125C10.4583 17.7083 11.1667 18 12 18ZM6 10H15V6H6V10ZM5 7.85V19V5V7.85Z"
                            fill="black" />
                    </svg>
                    Guardar
                </button>
            </div>
        @endif
    </form>
</div>
