<div class="container text-white">
    <div>
        <div>
            <div class="modal-header mb-3">
                <h5 class="modal-title">{{ $escuela_id ? 'Editar Escuela' : 'Añadir Escuela' }}</h5>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="col-md-12 form-group pt-3">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control custom-form-control" id="nombre"
                            wire:model.blur="nombre" style="width: 33%;">
                        @error('nombre')
                            <span class="text-warning">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12 form-group pt-3">
                        <label for="profesor1">Sensei 1</label>
                        <div class="d-flex">
                            <input type="text" class="form-control custom-form-control" id="profesor1"
                                wire:model.lazy="profesor1" style="width: 33%;">
                            @error('profesor1')
                                <span class="text-warning">{{ $message }}</span>
                            @enderror
                            @if (count($profesores) === 0)
                                <button type="button" wire:click="agregarProfesor" class="btn btn-sm"
                                    style="border-radius: 10px; margin-left: 20px; background: #EBC010;">+ Agregar
                                    Sensei</button>
                            @endif
                        </div>
                    </div>
                    @foreach ($profesores as $index => $profesor)
                        <div class="col-md-12 form-group pt-3">
                            <label>Sensei {{ $index + 2 }}</label>
                            <div class="d-flex">
                                <input type="text" class="form-control custom-form-control"
                                    wire:model.lazy="profesores.{{ $index }}" style="width: 33%;">
                                @error("profesores.{$index}")
                                    <span class="text-warning">{{ $message }}</span>
                                @enderror
                                @if ($index === count($profesores) - 1)
                                    <button type="button" wire:click="agregarProfesor" class="btn btn-sm"
                                        style="border-radius: 10px; margin-left: 20px; background: #EBC010;">+ Agregar
                                        sensei</button>
                                @endif
                                <button type="button" wire:click="eliminarProfesor({{ $index }})"
                                    class="btn btn-danger btn-sm" style="margin-left: 20px;">Eliminar</button>
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>
            <br><br>
            <div class="mb-3 text-center mt-5 d-flex justify-content-evenly">
                <button type="button" wire:click="store"
                    class="btn btn-guardar p-8 w-50">{{ $escuela_id ? 'Actualizar' : 'Guardar' }}</button>
            </div>
        </div>
    </div>
</div>
