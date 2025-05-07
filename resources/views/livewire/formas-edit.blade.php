<div class="mt-4"><br><br><br><br>
    <form wire:submit.prevent="store">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control custom-form-control" wire:model="nombre">
                @error('nombre') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4">
                <label for="seccion" class="form-label mb-2">Sección</label>
                <select id="seccion" name="seccion" wire:model="seccionSeleccionada" class="form-select mb-3">
                        <option value="#">Selecciona una opción</option>
                    @foreach($secciones as $seccionItem)
                        <option value="{{ $seccionItem->id }}">{{ $seccionItem->nombre }}</option>
                    @endforeach
                </select>
                @error('seccion') <span class="text-warning">{{ $message }}</span> @enderror
            </div>

            <div class="col-md-4">
                <label for="tipo_forma" class="form-label mb-2">Tipo de Forma</label>
                <select id="tipo_forma" name="tipo_forma" wire:model="tipoFormaSeleccionada" class="form-select mb-3">
                    <option value="#">Selecciona un tipo de forma</option>
                    @foreach($tiposFormas as $tipoForma)
                        <option value="{{ $tipoForma->id }}">{{ $tipoForma->nombre }}</option>
                    @endforeach
                </select>
                @error('tipoFormaSeleccionada') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
            
        </div><br><br>
        <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
            <button type="button" wire:click="store" class="btn btn-guardar p-8 w-50">{{ $id ? 'Actualizar' : 'Guardar' }}</button>
        </div>
    </form>
</div>