<div>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade show" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">{{ $torneo_id ? 'Editar Torneo' : 'Añadir Torneo' }}</h5>
                    <button wire:click="closeModal" id="createModalLabel" type="button" class="close btn absoluto text-white" data-dismiss="modal" aria-label="Close">
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
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control custom-form-control" id="descripcion" wire:model="descripcion"></textarea>
                            @error('descripcion') <span class="text-warning">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="fecha_evento">Fecha de Evento</label>
                            <input type="date" class="form-control custom-form-control" id="fecha_evento" wire:model="fecha_evento">
                            @error('fecha_evento') <span class="text-warning">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control custom-form-control" id="direccion" wire:model="direccion">
                            @error('direccion') <span class="text-warning">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="banner">Banner</label>
                            <input type="file" class="form-control custom-form-control" id="banner" wire:model="banner" accept="image/png, image/jpeg, image/jpg">
                            @error('banner') <span class="text-warning">{{ $message }}</span> @enderror

                            @if($banner /* && Storage::disk('public')->exists($banner) */)
                                <div class="text-center mt-2 mb-2">
                                    <img src="{{ asset($banner) }}" alt="Banner actual" width="100">
                                    <a wire:click="deleteBanner" type="button" class="btn btn-outline-danger btn-sm mt-2"><i class="bi bi-trash3-fill"></i></a>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="ranking">Ranking</label>
                            <input type="number" class="form-control custom-form-control" id="ranking" wire:model="ranking">
                            @error('ranking') <span class="text-warning">{{ $message }}</span> @enderror
                        </div>
                    </form>
                </div>
                <div class="mb-3 text-center">
                    <button type="button" wire:click="store" class="btn btn-outline-success">{{ $torneo_id ? 'Actualizar' : 'Guardar' }}</button>
                    <button type="button" id="createModalLabel" class="btn btn-outline-danger" data-dismiss="modal" wire:click="closeModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
