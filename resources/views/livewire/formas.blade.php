<div class="container-fluid mt-3">
    @if(session()->has('message'))
        <div class="alert alert-success w-100" id="success-message" x-init="init()">
            {{ session('message') }}
        </div>
    @endif
    <div class="d-flex align-categorias-center justify-content-between">
        <h2 class="text-white">Categorias - Formas</h2>
        <div class="text-right">
            <button class="btn btn-amarillo" wire:click="create">Crear forma</button>
        </div>
    </div>
    <div class="d-flex align-categorias-center justify-content-between mt-3">
        <div class="text-left col-md-5">
            <div class="input-group">
                <input type="text" class="form-control buscar w-100" wire:model.lazy="search" placeholder="Buscar forma..." aria-describedby="button-addon2">
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="mitabla" class="table table-dark mt-5">
            <tbody>
                @foreach ($formas as $forma)
                    <tr>
                        <td>{{ $forma->nombre }}</td>
                        <td>
                            <button wire:click="edit({{ $forma->id }})" class="btn btn-outline-primary btn-sm mt-2" data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar forma"><i class="bi bi-pencil"></i></button>
                            <button wire:click="delete({{ $forma->id }})" class="btn btn-outline-danger btn-sm mt-2"  wire:confirm="¿Estás seguro de eliminar la forma?" data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar forma"><i class="bi bi-trash3-fill"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>