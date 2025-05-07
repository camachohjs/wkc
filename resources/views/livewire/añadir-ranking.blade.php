<div class="px-5">
    <h2 class="text-white mb-9">Añadir Participante</h2><br />

    <form wire:submit.prevent="guardarRanking">
        <div class="row ">
            <!-- Columna 1 -->
            <div class="col-md-6 mt-9">
                <!-- Torneo -->
                <div class="mb-3">
                    <label for="torneo" class="form-label">Torneo</label>
                    <select id="torneo" wire:model="torneoId" class="form-select">
                        <option value="">Selecciona un torneo</option>
                        @foreach ($torneos as $torneo)
                            <option value="{{ $torneo->id }}">{{ $torneo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Maestro -->
                <div class="mb-3">
                    <label for="maestro" class="form-label"
                        @if ($alumnoId) style="visibility: collapse;" @endif>Maestro</label>
                    <select id="maestro" wire:model="maestroId" wire:change="limpiarAlumno" class="form-select"
                        @if ($alumnoId) style="visibility: collapse;" @endif>
                        <option value="">Selecciona un maestro</option>
                        @foreach ($maestros as $maestro)
                            <option value="{{ $maestro->id }}">{{ $maestro->nombre . ' ' . $maestro->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Puntos -->
                <div class="mb-3 col-md-2">
                    <label for="puntos" class="form-label">Puntos</label>
                    <input id="puntos" type="number" wire:model="puntos" class="form-control custom-form-control"
                        placeholder="0">
                </div>
            </div>

            <!-- Columna 2 -->
            <div class="col-md-6 mt-9">
                <!-- Categoría -->
                <div class="mb-3">
                    <label for="categoria" class="form-label">Categoría</label>
                    <select id="categoria" wire:model="categoriaId" class="form-select">
                        <option value="">Selecciona una categoría</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->division }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Alumno -->
                <div class="mb-3 ">
                    <label for="alumno" class="form-label"
                        @if ($maestroId) style="visibility: collapse;" @endif>Alumno</label>
                    <select id="alumno" wire:model="alumnoId" wire:change="limpiarMaestro" class="form-select"
                        @if ($maestroId) style="visibility: collapse;" @endif>
                        <option value="">Selecciona un alumno</option>
                        @foreach ($alumnos as $alumno)
                            <option value="{{ $alumno->id }}">{{ $alumno->nombre . ' ' . $alumno->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3 mt-3 text-center">
                <button type="submit" class="btn btn-guardar w-100">Guardar</button>
            </div>
        </div>
    </form>
</div>
