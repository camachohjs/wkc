<div class="container mb-3">
    <div class="d-flex justify-content-end">
        <a class="btn {{ request()->is('atletas-grid') ? 'btn-grid' : 'btn-negro' }}" href="{{ route('atletas.grid') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Vista en forma cuadrícula">
            <i class="bi bi-grid-3x3-gap"></i>
        </a>&nbsp;
        <a class="btn {{ request()->is('atletas-list') ? 'btn-grid' : 'btn-negro' }}" href="{{ route('atletas.list') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Vista en forma de lista">
            <i class="bi bi-list"></i>
        </a>
    </div>
    <br>
    <div class="container">
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <ul class="list-group" >
                    @foreach ($atletas as $index => $atleta)
                        @if ($index % 2 == 0)
                            <li class="list-group-item" style="min-height: 140px">
                                <img src="{{ $atleta->foto ?: 'libs/images/profile/user-1.png' }}" class="img-fluid img-atleta" alt="Imagen del atleta" style="width: 100px; height: auto; margin-right: 15px;">
                                <span style="font-size: x-large;">{{ $atleta->nombre.' '.$atleta->apellidos }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <!-- Columna derecha -->
            <div class="col-md-6">
                <ul class="list-group">
                    @foreach ($atletas as $index => $atleta)
                        @if ($index % 2 == 1)
                            <li class="list-group-item" style="min-height: 140px">
                                <img src="{{ $atleta->foto ?: 'libs/images/profile/user-1.png' }}" class="img-fluid img-atleta" alt="Imagen del atleta" style="width: 100px; height: auto; margin-right: 15px;">
                                <span style="font-size: x-large;">{{ $atleta->nombre.' '.$atleta->apellidos }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <br>
        <div class="mt-3 d-flex align-items-start justify-content-between">
            <div>
                <form wire:submit.prevent="buscar" class="d-flex">
                    <select class="form-select select-amarillo" id="perPage" wire:change='paginacion' wire:model="perPage">
                        <option value="28">Mostrar 28</option>
                        <option value="50">Mostrar 50</option>
                        <option value="100">Mostrar 100</option>
                    </select>
                </form>
            </div>
            <div>
                {{ $atletas->links('custom') }}
            </div>
        </div>
    </div>
</div>