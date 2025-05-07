<div>
    <div wire:ignore.self class="modal fade show" id="createSchoolModal" tabindex="-1" role="dialog" aria-labelledby="createSchoolModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createSchoolModalLabel">{{ $escuela_id ? 'Editar Escuela' : 'Añadir Escuela' }}</h5>
                    <button wire:click="closeModal" id="createSchoolModalLabel" type="button" class="close btn absoluto text-white" data-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control custom-form-control" id="nombre" wire:model="nombre">
                            @error('nombre') <span class="text-warning">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <textarea class="form-control custom-form-control" id="direccion" wire:model="direccion"></textarea>
                            @error('direccion') <span class="text-warning">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="mb-3 text-center">
                    <button type="button" wire:click="store" class="btn btn-outline-success">{{ $escuela_id ? 'Actualizar' : 'Guardar' }}</button>
                    <button type="button" id="createSchoolModalLabel" class="btn btn-outline-danger" data-dismiss="modal" wire:click="closeModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
