<div class="container-fluid mt-3">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">🥋 Histórico sensei</h2><br>
            <div class="input-group mb-3">
                <input type="text" class="form-control buscar" wire:model.live.debounce.150ms="search" placeholder="Buscar sensei..." aria-describedby="button-addon2">
                <button class="btn btn-light" type="button" id="button-addon2">Buscar</button>
            </div>
        </div>
        <div class="text-right">
            <button wire:click="profesores()" class="btn btn-amarillo w-100">
                <span class="bi bi-person-arms-up"> Sensei</span>
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="alert alert-success" id="success-message" x-init="init()">
            {{ session('message') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-dark mt-5 text-center text-white" style="width: 95%;">
            <thead>
                <tr>
                    <th>{{-- Lugar --}}</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Cinta</th>
                    <th>Escuela</th>
                    @role('admin')
                        <th>Status</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach($maestros as $maestro)
                    <tr>
                        <td></td>
                        <td><img src="{{ !$maestro->foto ? asset('libs/images/profile/user-1.png') : asset($maestro->foto) }}" alt="foto_de_perfil" class="rounded-circle foto-perfil" width="35" height="35"></td>
                        <td>{{ $maestro->nombre }}</td>
                        <td>{{ $maestro->apellidos }}</td>
                        <td>{{ $maestro->email }}</td>
                        <td>{{ $maestro->cinta }}</td>
                        <td>{{ $maestro->escuelas->first()->nombre ?? '' }}</td>
                        @role('admin')
                            <td>
                                @if ($maestro->trashed())
                                    <button wire:click="reactivar({{ $maestro->id }})"class="btn btn-success btn-sm mt-2 w-100"><i class="bi bi-recycle"></i> Reactivar</button>
                                @endif
                                <button wire:click="eliminarDefinitivamente({{ $maestro->id }})" class="btn btn-danger btn-sm mt-2 w-100"  wire:confirm="¿Estás seguro de eliminar al sensei?"><i class="bi bi-trash3-fill"></i> Eliminar Definitivamente</button>
                            </td>
                        @endrole
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>