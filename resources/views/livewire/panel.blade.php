<div class="container">
    <div class="row mb-5 align-items-center">
        <div class="col-md-4">
            {{-- <img src="{{ !$foto_actual ? asset('libs/images/profile/user-1.png') : asset($foto_actual) }}"class="img-fluid" style="width: 350px; border-radius: 10px;" alt="foto_de_perfil"> --}}
            <div class="form-group pt-3">
                <label for="foto" style="color: #fff6">Da click para cambiar tu foto de perfil</label>
                <label for="foto" class="drop-banner" id="dropcontainer"
                    @if (!$foto_actual) style="background-image: url('libs/images/profile/user-1.png')" @else style="background-image: url('{{ $foto_actual }}')" @endif>
                    <input type="file" class="form-control custom-form-control" id="foto" wire:model="foto"
                        accept="image/png, image/jpeg, image/jpg">
                </label>
                @error('foto')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-8">
            <h1 class="text-white">Hola, {{ ucfirst($nombre) . ' ' . ucfirst($apellidos) }}</h1>
        </div>
    </div>
    <form wire:submit.prevent="store">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control custom-form-control" wire:model="nombre"
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
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control custom-form-control" wire:model="email"
                    style="border: .5px solid #fff;">
                @error('email')
                    <span class="text-warning">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">

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
                <select id="cinta" name="cinta" wire:model="cinta" class="form-select mb-3"
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
        </div>

        <div class="row mb-3 justify-content-space-between">
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

        <div class="mt-3 text-center">
            <button class="btn btn-guardar p-8 w-50" type="submit">Actualizar</button>
        </div>
    </form>
</div>
