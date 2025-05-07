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
    <div class="row">
        @foreach ($atletas as $atleta)
        <div class="col-md-2 mb-4">
            <div class="card">
                <img src="{{ $atleta->foto ?: 'libs/images/profile/user-1.png' }}" class="card-img-top img-atleta" alt="Imagen del atleta">
                <div class="card-body">
                    <h5 class="card-title">{{ $atleta->nombre.' '.$atleta->apellidos }}</h5>
                </div>
            </div>
        </div>
        @endforeach
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