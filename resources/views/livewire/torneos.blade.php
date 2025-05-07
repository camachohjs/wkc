
<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">🏆 Torneos</h2>
        </div>
        @if($this->showCreateButton())
            <div class="text-right">
                <button class="btn btn-amarillo" wire:click="create">Crear Torneo</button>
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
            <input type="text" class="form-control buscar" wire:model.live.debounce.150ms="search" placeholder="Buscar Torneo..." aria-describedby="button-addon2">
            <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-dark mt-3">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Banner</th>
                    <th>Fecha de Evento</th>
                    <th>Fecha de Registro</th>
                    <th>Dirección</th>
                    <th>Mapa</th>
                    @role('admin')
                        <th>Acciones</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach($torneos as $torneo)
                    <tr>
                        <td>{{ $torneo->nombre }}</td>
                        <td><img src="{{ !$torneo->banner ? asset('libs/images/profile/nodisponible.png') : asset($torneo->banner) }}" alt="imagen" class="img-banner"></td>
                        <td>{{ $torneo->fecha_evento }}</td>
                        <td>{{ $torneo->fecha_registro }}</td>
                        <td>{{ $torneo->direccion }}</td>
                        <td><iframe width="auto" height="300" frameborder="0" style="border:0; border-radius: 10px;" src="https://www.google.com/maps?q={{ urlencode($torneo->direccion) }}&output=embed&zoom=25" allowfullscreen></iframe></td>
                        @role('admin')
                            <td>
                                <button wire:click="edit({{ $torneo->id }})" class="btn btn-outline-primary btn-sm mt-2" data-bs-custom-class="edit-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar torneo"><i class="bi bi-pencil"></i></button>
                                <button wire:click="delete({{ $torneo->id }})" class="btn btn-outline-danger btn-sm mt-2"  wire:confirm="¿Estás seguro de eliminar el torneo?" data-bs-custom-class="delete-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar torneo"><i class="bi bi-trash3-fill"></i></button>
                            </td>
                        @endrole
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="button-container mt-6 text-center">
                                <button wire:click="inscritos({{ $torneo->id }})" class="btn btn-amarillo" style="width: 80%;">
                                    <span class="bi bi-check-circle-fill"> Inscritos</span>
                                </button>
                            </div>
                        </td>
                        <td colspan="3">
                            <div class="button-container mt-6 text-center">
                                <button wire:click="iniciarTorneo({{ $torneo->id }})" class="btn btn-amarillo" style="width: 80%;">
                                    <span class="bi bi-play-circle-fill"> Iniciar Torneo</span>
                                </button>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="button-container mt-6 text-center">
                                <button wire:click="areas({{ $torneo->id }})" class="btn btn-amarillo" style="width: 80%;">
                                    <span class="bi bi bi-person-add"> Generar usuario</span>
                                </button>
                            </div>
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
            {{ $torneos->links('custom') }}
        </div>
    </div>
</div>
