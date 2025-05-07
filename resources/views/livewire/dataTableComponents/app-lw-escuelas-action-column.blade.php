<div class="d-flex justify-content-around">

    <button wire:click="edit({{ $escuela->id }})" class="btn btn-outline-primary btn-sm mt-2" data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar escuela"><i class="bi bi-pencil"></i></button>
    <button wire:click="delete({{ $escuela->id }})" wire:confirm="¿Estás seguro de eliminar la escuela?" class="btn btn-outline-danger btn-sm mt-2" data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar escuela"><i class="bi bi-trash3-fill"></i></button>

</div>