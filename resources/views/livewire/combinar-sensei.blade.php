<div class="container text-white">
    <div class="modal-header mb-5 mt-6">
        <h2 class="text-white">Combinar sensei</h2>
    </div>
    <div class="row mb-3 justify-content-space-between mt-6">
        <div class="col-md-6 form-group pt-3">
            <label for="idEliminar" class="form-label mb-2">Seleccione al sensei que se combinará</label>
            <select wire:change="setIdEliminar($event.target.value)" class="form-select" id="idEliminar" name="idEliminar">
                <option value="">Selecciona un sensei</option>
                @foreach ($maestros as $maestro)
                    <option value="{{ $maestro->id }}">{{ $maestro->nombre }} {{ $maestro->apellidos }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 form-group pt-3">
            <label for="idMantener" class="form-label mb-2">Seleccione al sensei que se guardará</label>
            <select wire:change="setIdMantener($event.target.value)" class="form-select" id="idMantener" name="idMantener">
                <option value="">Selecciona un sensei</option>
                @foreach ($maestros as $maestro)
                    <option value="{{ $maestro->id }}">{{ $maestro->nombre }} {{ $maestro->apellidos }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <br><br>
    <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
        <button wire:click="combinarMaestros({{ $idEliminar }}, {{ $idMantener }})" class="btn btn-guardar p-8 w-50">Combinar Maestros</button>
    </div>
</div>
