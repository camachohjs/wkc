<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <img src="{{ !$torneo->banner ? asset('libs/images/profile/nodisponible.png') : asset($torneo->banner) }}" alt="{{ $torneo->nombre }}" class="img-torneo" style="border-radius: 10px;">
            {{-- <a type="button" class="btn btn-registro mb-3 mt-3 justify-center" style="width: 100%" href="{{ url('/eventos') }}"><b>Registrarse</b></a> --}}
            <a href="{{ route('registro-alumno', ['torneo_id' => $torneo->id]) }}" class="btn btn-registro mb-3 mt-3 justify-center" style="width: 100%">Registrarse</a>
        </div>
        <div class="col-md-9 text-left">
            <h5 class="titulo1"><span class="text-yellow"> Evento  {{ $torneo->nombre ?? 'Nombre no encontrado' }} ({{ $torneo->fecha_evento ?? 'Fecha no encontrada' }})</span></h5>
            <p class="texto3" style="margin-left: 0px !important">{{ $torneo->descripcion ?? 'Descripción no encontrada' }}</p>
            <div class="row">
                <div class="col-md-7">
                    <h5 class="titulo1"><img src="{{ asset('Img/Vector.png') }}" alt="Mapa"> <span class="text-yellow">Mapa</span></h5>
                    <iframe width="600" height="290" frameborder="0" style="border:0; border-radius: 10px;" src="https://www.google.com/maps?q={{ urlencode($torneo->direccion) }}&output=embed&zoom=25" allowfullscreen></iframe>
                </div>
                <div class="col-md-5 text-white">
                    <h5 class="titulo1"><img src="{{ asset('Img/cup.png') }}" alt="Copa"> <span class="text-yellow">Premios y Ranking</span></h5>
                    <div class="d-flex justify-content-around">
                        @if ($torneo->prems)
                            <div>
                                <ul>
                                    @foreach ($torneo->prems as $premio)
                                        <li>{{ $premio->premio }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    
                        @if ($torneo->ranks)
                            <div>
                                <ul>
                                    @foreach ($torneo->ranks as $ranking)
                                        <li>{{ $ranking->ranking }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3 col-md-3">
            
        </div>
    </div>
</div>