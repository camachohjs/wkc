<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="text-white">🏆 Torneos</h2>
        @if ($this->showCreateButton())
            <button class="btn btn-amarillo" wire:click="create">Crear Torneo</button>
        @endif
    </div>

    {{-- Mensaje de éxito --}}
    @if (session()->has('message'))
        <div class="alert alert-success" x-data x-init="setTimeout(() => $el.remove(), 4000)">
            {{ session('message') }}
        </div>
    @endif

    {{-- Barra de búsqueda --}}
    <div class="col-md-5 mb-3">
        <div class="input-group">
            <input type="text" class="form-control buscar" wire:model.live.debounce.300ms="search"
                placeholder="Buscar Torneo..." aria-label="Buscar Torneo">
            <button class="btn btn-light" type="button">Buscar</button>
        </div>
    </div>

    {{-- Tabla de torneos --}}
    <div class="table-responsive">
        <table class="table table-striped table-dark align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Banner</th>
                    <th>Fecha Evento</th>
                    <th>Fecha Registro</th>
                    <th>Dirección</th>
                    <th>Mapa</th>
                    @role('admin')
                        <th>Acciones</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @forelse($torneos as $torneo)
                    <tr>
                        <td>{{ $torneo->nombre }}</td>
                        <td>
                            <img src="{{ $torneo->banner ? asset($torneo->banner) : asset('libs/images/profile/nodisponible.png') }}"
                                alt="Banner del torneo" class="img-fluid img-banner">
                        </td>
                        <td>{{ $torneo->fecha_evento }}</td>
                        <td>{{ $torneo->fecha_registro }}</td>
                        <td>{{ $torneo->direccion }}</td>
                        <td>
                            <iframe width="100%" height="150" frameborder="0" style="border:0; border-radius: 10px;"
                                src="https://www.google.com/maps?q={{ urlencode($torneo->direccion) }}&output=embed"
                                allowfullscreen loading="lazy">
                            </iframe>
                        </td>
                        @role('admin')
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <button wire:click="edit({{ $torneo->id }})" class="btn btn-outline-primary btn-sm"
                                        title="Editar torneo">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button wire:click="delete({{ $torneo->id }})"
                                        onclick="confirm('¿Estás seguro de eliminar el torneo?') || event.stopImmediatePropagation()"
                                        class="btn btn-outline-danger btn-sm" title="Eliminar torneo">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </div>
                            </td>
                        @endrole
                    </tr>

                    {{-- Acciones adicionales --}}
                    <tr>
                        <td colspan="7">
                            <div class="d-flex justify-content-around flex-wrap gap-2 py-2">
                                <button wire:click="inscritos({{ $torneo->id }})" class="btn btn-amarillo">
                                    <i class="bi bi-check-circle-fill"></i> Inscritos
                                </button>

                                <button wire:click="iniciarTorneo({{ $torneo->id }})" class="btn btn-amarillo">
                                    <i class="bi bi-play-circle-fill"></i> Iniciar Torneo
                                </button>

                                <button wire:click="areas({{ $torneo->id }})" class="btn btn-amarillo">
                                    <i class="bi bi-person-add"></i> Generar Usuario
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No se encontraron torneos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación y selección por página --}}
    <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <label for="perPage" class="text-white me-2">Mostrar:</label>
            <select class="form-select select-amarillo" id="perPage" wire:model="perPage" style="width: auto;">
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        <div>
            {{ $torneos->links('custom') }}
        </div>
    </div>
</div>
