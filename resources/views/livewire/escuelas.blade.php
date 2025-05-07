
<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">🏫 Escuelas</h2>
        </div>
        @if($this->showCreateSchoolButton())
            <div class="text-right">
                <button class="btn btn-amarillo" wire:click="create">Crear Escuela</button>
            </div>
        @endif
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success" id="success-message" x-init="init()">
            {{ session('message') }}
        </div>
    @endif

    <div class="col-md-5 mt-3">
        <div class="input-group mb-3">
            <input type="text" class="form-control buscar"wire:model.live.debounce.150ms="search" placeholder="Buscar Escuela..." aria-describedby="button-addon2">
            <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-dark mt-5">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Sensei</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($escuelas as $escuela)
                    <tr>
                        <td>{{ $escuela->nombre }}</td>
                        <td>
                            @foreach ($escuela->maestros as $maestro)
                                <li>{{ $maestro->nombre }} {{ $maestro->apellidos }}</li>
                            @endforeach
                        </td>
                        <td>
                            <button wire:click="fusionarMaestros({{ $escuela->id }})" class="btn btn-guardar btn-sm mt-2"><i class="bi bi-repeat"></i> Combinar sensei</button>&nbsp;
                            <button wire:click="edit({{ $escuela->id }})" class="btn btn-primary btn-sm mt-2" data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar escuela"><i class="bi bi-pencil"></i> Editar escuela</button>&nbsp;
                            <button wire:click="delete({{ $escuela->id }})" class="btn btn-danger btn-sm mt-2"  wire:confirm="¿Estás seguro de eliminar la escuela?" data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar escuela"><i class="bi bi-trash3-fill"></i> Eliminar escuela</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex align-items-start justify-content-between">
        <div>
            <form wire:submit.prevent="buscar" class="d-flex">
                <select class="form-select select-amarillo" id="perPage" wire:change='update'  wire:model="perPage">
                    <option value="25">Mostrar 25</option>
                    <option value="50">Mostrar 50</option>
                    <option value="100">Mostrar 100</option>
                </select>
            </form>
        </div>
        <div>
            {{ $escuelas->links('custom') }}
        </div>
    </div>
</div>
