<div class="container">
    <div class="row text-white mt-4 mb-5">
        <div class="col-md-12 mt-2 mb-2">
            <h4 class="margin-left">Crear competidor</h4>
        </div>
    </div><br>
    <form wire:submit.prevent="store">
        <div class="row mb-5">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control custom-form-control mb-5" wire:model="nombre"
                    style="border: .5px solid #fff;">
                @error('nombre')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="apellidos" class="form-label">Apellido</label>
                <input type="text" class="form-control custom-form-control" wire:model="apellidos"
                    style="border: .5px solid #fff;">
                @error('apellidos')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="foto" class="drop-container" id="dropcontainer">
                    <span class="subir-img">Suelte imagen del competidor</span>
                    <input type="file" class="form-control" id="foto" wire:model="foto"
                        accept="image/png, image/jpeg, image/jpg">
                </label>
                @error('foto')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-4">
                <label for="fec" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control custom-form-control" wire:model="fec"
                    style="border: .5px solid #fff;">
                @error('fec')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="cinta" class="form-label mb-2">Grado</label>
                <select id="cinta" name="cinta" wire:model="cinta" class="form-select mb-5"
                    style="border: .5px solid #fff;">
                    <option value="">Selecciona un grado</option>
                    <option value="Principiante">Principiante</option>

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
                <input type="number" class="form-control custom-form-control" wire:model="telefono"
                    style="border: .5px solid #fff;">
                @error('telefono')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>
        </div><br>

        <div class="row mb-5 justify-content-space-between">
            <div class="col-md-4">
                <label for="peso" class="form-label">Peso</label>
                <div class="input-group" style="width: 50%;">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="peso"
                        style="border: .5px solid #fff;">
                    <span class="input-group-text" style="border: .5px solid #fff;">kg</span>
                </div>
                @error('peso')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-4 text-left">
                {{-- <label for="estatura" class="form-label">Estatura</label>
                <div class="input-group" style="width: 50%;">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="estatura" style="border: .5px solid #fff;">
                    <span class="input-group-text" style="border: .5px solid #fff;">mts</span>
                </div>
                @error('estatura') <span class="text-warning">{{ $message }}</span> @enderror --}}
            </div>

            <div class="col-md-4 text-white">
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
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-5 justify-content-space-between">
            <div class="col-md-6">
                <label for="escuela" class="form-label mb-2">Escuela</label>
                <select wire:model="escuelaSeleccionada" class="form-select" id="escuela" required>
                    @if ($escuelaDelMaestro)
                        <option value="{{ $escuelaDelMaestro->id }}" selected>{{ $escuelaDelMaestro->nombre }}</option>
                    @else
                        <option value="">No hay escuela asignada</option>
                    @endif
                </select>
            </div>

            <div class="col-md-6">
                <label for="maestro" class="form-label mb-2">Maestro</label>
                <input type="text" class="form-control custom-form-control" style="border: .5px solid #fff;"
                    id="maestro" value="{{ $nombreMaestro }}" readonly>
            </div>
        </div>

        <div class="mt-5  text-center">
            <button class="btn btn-guardar p-8 w-50" type="submit">Guardar</button>
        </div>
    </form>
</div>
