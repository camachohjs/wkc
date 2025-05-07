<div class="container-fluid mt-4">
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
            <input type="date" class="form-control custom-form-control" wire:model="fec">
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

                <input class="ml-50 genero-radio" type="radio" id="female" name="genero" value="femenino"
                    wire:model="genero">
                <label for="femenino">Mujer</label>
            </div>
            @error('genero')
                <span class="text-warning">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
