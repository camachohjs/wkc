<div class="mt-4">
    <form wire:submit.prevent="store">
        <div class="row mb-4">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control custom-form-control" wire:model="nombre">
                @error('nombre') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-6">
                <label for="division" class="form-label">Division</label>
                <input type="text" class="form-control custom-form-control" wire:model="division">
                @error('division') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
        </div><br>

        <div class="row mb-4 mt-4">
            <div class="col-md-4">
                <label for="genero" class="form-label mb-2">Genero</label>
                <select id="genero" name="genero" wire:model="genero" class="form-select mb-3">
                    <option value="">Selecciona un genero</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="mixto">Ambos</option>
                </select>
                @error('genero') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4">
                <label for="forma" class="form-label mb-2">Forma</label>
                <select id="forma" name="forma" wire:model="formaSeleccionada" class="form-select mb-3">
                    <option value="">Selecciona una forma</option>
                    @foreach($formas as $forma)
                        <option value="{{ $forma->id }}">{{ $forma->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label for="cinta" class="form-label mb-2">
                    Grado 
                    <button type="button" class="btn" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top"  data-bs-trigger="hover focus" 
                        data-bs-content="Mantén presionado Ctrl (Windows) o Cmd (Mac) para seleccionar varias opciones." data-bs-custom-class="custom-popover">
                        <i class="bi bi-info-circle text-amarillo"></i>
                    </button>
                </label>
                <select id="cinta" name="cinta[]" wire:model="cinta" class="form-select mb-3" multiple style="height: auto;">
                    <option value="Principiante">Principiante</option>
                    <option value="Novato">Novato</option>
                    <option value="Intermedio">Intermedio</option>
                    <option value="Avanzado">Avanzado</option>
                    <option value="Negra">Cinta negra</option>
                    <option value="P/I/A">Principiante, Intermedio, Avanzado</option>
                </select>
                @error('cinta') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
        </div><br>

        <div class="row mb-4 mt-4 justify-content-space-between">
            <div class="col-md-3">
                <label for="peso_minimo" class="form-label">Peso minimo</label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="peso_minimo">
                    <span class="input-group-text">kg</span>
                </div>
                @error('peso_minimo') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-3">
                <label for="peso_maximo" class="form-label">Peso maximo</label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="peso_maximo">
                    <span class="input-group-text">kg</span>
                </div>
                @error('peso_maximo') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-3">
                <label for="edad_minima" class="form-label">Edad minima</label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="edad_minima">
                    <span class="input-group-text">años</span>
                </div>
                @error('edad_minima') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-3">
                <label for="edad_maxima" class="form-label">Edad maxima</label>
                <div class="input-group">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="edad_maxima">
                    <span class="input-group-text">años</span>
                </div>
                @error('edad_maxima') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
        </div><br><br>
        <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
            <button type="button" wire:click="store" class="btn btn-guardar p-8 w-50">{{ $id ? 'Actualizar' : 'Guardar' }}</button>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

</script>