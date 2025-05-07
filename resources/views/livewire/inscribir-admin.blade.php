<div class="container-fluid">
    <div class="row text-white mt-4">
        <div class="col-12 col-md-3 mt-2 mb-2">
            <h4 class="margin-left">Próximos Eventos</h4>
        </div>
        <div class="col-12 col-md-4 mt-2 mb-2">
            <div class="input-group">
                <input type="text" wire:model.live="buscar" placeholder="Buscar eventos" class="buscar-torneo form-control text-white" style="border-radius: 10px 0 0 10px; border: none;">
                <span class="input-group-text" style="border-radius: 0 10px 10px 0; border: none; background: #323131; box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.05);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path d="M20 20L15.514 15.506M18 9.5C18 11.7543 17.1045 13.9163 15.5104 15.5104C13.9163 17.1045 11.7543 18 9.5 18C7.24566 18 5.08365 17.1045 3.48959 15.5104C1.89553 13.9163 1 11.7543 1 9.5C1 7.24566 1.89553 5.08365 3.48959 3.48959C5.08365 1.89553 7.24566 1 9.5 1C11.7543 1 13.9163 1.89553 15.5104 3.48959C17.1045 5.08365 18 7.24566 18 9.5V9.5Z" stroke="#F9F9F9" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </span>
            </div>
        </div>
        <div class="col-12 col-md-5 mt-2 mb-2 d-flex justify-content-between">
            <input type="date" class="form-control custom-form-control3" wire:model="fechaInicio" wire:change="updateFechas" placeholder="Fecha inicio">
            <input type="date" class="form-control custom-form-control3" wire:model="fechaFin" wire:change="updateFechas" placeholder="Fecha fin">
        </div>
    </div>

    <div class="row mt-4">
        @foreach($torneos->take(4) as $key => $torneo)
            <div class="col-12 mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-md-3">
                        <img src="{{ !$torneo->banner ? asset('libs/images/profile/nodisponible.png') : asset($torneo->banner) }}" alt="{{ $torneo->nombre }}" class="img-fluid img-torneo">
                    </div>
                    <div class="col-12 col-md-9 text-center text-md-start">
                        <h5 class="titulo1 text-white"> {{ $torneo->nombre }} ({{ $torneo->fecha_evento }})</h5>
                        <div class="row">
                            <div class="col-12 col-md-6 text-center">
                                <p class="texto3 ">{{ $torneo->descripcion }}</p><br>
                                {{-- @if ($torneo->categorias->isNotEmpty())
                                    <h6 class="text-white">Divisiones</h6>
                                    <div id="carouselExample{{ $key }}" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                        <div class="carousel-inner">
                                            @foreach($torneo->categorias->chunk(3) as $chunkIndex => $chunk)
                                                <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                                                    <div class="d-flex justify-content-center">
                                                        <ul>
                                                            @foreach($chunk as $categoria)
                                                                <li class="text-yellow">{{ $categoria->nombre }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample{{ $key }}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample{{ $key }}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                @endif --}}
                            </div>
                            <div class="col-12 col-md-6">
                                <div style="max-width: 400px;">
                                    <iframe width="100%" height="200" frameborder="0" style="border:0; border-radius: 10px;" src="https://www.google.com/maps?q={{ urlencode($torneo->direccion) }}&output=embed&zoom=15" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around text-white">
                            @if ($torneo->prems)
                                <div>
                                    <h6 class="text-yellow">Premios</h6>
                                    <ul>
                                        @foreach ($torneo->prems as $premio)
                                            <li>{{ $premio->premio }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        
                            @if ($torneo->ranks)
                                <div>
                                    <h6 class="text-yellow">Rankings</h6>
                                    <ul>
                                        @foreach ($torneo->ranks as $ranking)
                                            <li>{{ $ranking->ranking }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex mt-2 justify-content-center justify-content-md-end">
                            <a href="{{ route('registrar-admin', ['torneo_id' => $torneo->id, 'id_participante' => request()->route('id')]) }}" class="btn btn-registro w-100"><b>Registrar</b></a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>