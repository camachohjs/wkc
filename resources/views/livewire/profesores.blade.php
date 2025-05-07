<div class="container-fluid mt-3">
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres realizar esta acción?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="tuMetodoConfirmado" data-bs-dismiss="modal">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
        <div class="text-start mb-2 mb-md-0">
            <h2 class="text-white">🥋 Sensei</h2>
            <div class="input-group mb-3">
                <input type="text" class="form-control buscar" wire:model.live.debounce.150ms="search" placeholder="Buscar sensei..." aria-describedby="button-addon2">
                <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
            </div>
        </div>
        <div class="text-start text-md-end">
            @if($this->showCreateButton())
                <button class="btn btn-amarillo mb-2 mb-md-3 w-100" wire:click="create"><i class="bi bi-person-plus-fill"></i> Crear sensei</button>
            @endif
            <button wire:click="historico()" class="btn btn-amarillo w-100 mt-2 mt-md-0">
                <span class="bi bi-clock-history"> Histórico</span>
            </button>
        </div>
    </div>

    @if($modalFormVisible)
        @include('livewire.profesores-modal')
    @endif

    @if(session()->has('message'))
        <div class="alert alert-success" id="success-message" x-init="init()">
            {{ session('message') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-dark mt-5 text-center text-white" style="width: 100%;">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Nacionalidad</th>
                    <th>Escuela</th>
                    <th>Correo</th>
                    <th>Cinta</th>
                    @role('admin')
                        <th>Status</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach($profesores as $profesor)
                    <tr>
                        <td>
                            <img src="{{ !$profesor->foto ? asset('libs/images/profile/user-1.png') : asset($profesor->foto) }}" alt="foto_de_perfil" class="rounded-circle foto-perfil" width="35" height="35">
                        </td>
                        <td>{{ $profesor->nombre }}</td>
                        <td>{{ $profesor->apellidos }}</td>
                        <td>
                            <img src="https://flagcdn.com/h24/{{ $profesor->codigo_bandera ?? 'unknown' }}.png" alt="{{ $profesor->nacionalidad ?? '' }}">
                        </td>
                        <td>{{ $profesor->escuelas->first()->nombre ?? '' }}</td>
                        <td>{{ $profesor->email }}</td>
                        <td>{{ $profesor->cinta }}</td>
                        @role('admin')
                            <td>
                                <div class="d-flex flex-column flex-md-row justify-content-around">
                                    <button wire:click="edit({{ $profesor->id }})" class="btn btn-outline-primary btn-sm mt-2 mt-md-0" data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar sensei"><i class="bi bi-pencil"></i></button>
                                    <button wire:click="delete({{ $profesor->id }})" class="btn btn-outline-danger btn-sm mt-2 mt-md-0" wire:confirm="¿Estás seguro de eliminar al sensei?" data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar sensei"><i class="bi bi-trash3-fill"></i></button>
                                </div>
                            </td>
                        @endrole
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3 d-flex flex-column flex-md-row align-items-start justify-content-between">
        <div class="mb-3 mb-md-0">
            <form wire:submit.prevent="buscar" class="d-flex">
                <select wire:model="perPage" class="form-select select-amarillo">
                    <option value="25">Mostrar 25</option>
                    <option value="50">Mostrar 50</option>
                    <option value="100">Mostrar 100</option>
                </select>
            </form>
        </div>
        <div>
            {{ $profesores->links('custom') }}
        </div>
    </div>
</div>
