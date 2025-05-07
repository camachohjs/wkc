<div class="px-5 text-white">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">Mi puntuación</h2>
        </div>
    </div><br>
    <div class="d-flex">
        <div class="w-50 d-flex flex-column">
            <div class="w-100 rounded-lg bg-dark p-5 mt-3" style="background-color: rgba(var(--bs-dark-rgb),0.5)!important;">
                <h5 class="text-white">
                    Filtros
                </h5><br>
                <div class="d-flex flex-column">
                    <div class="form-group w-100 mx-2">
                        
                    </div>
                    <div class="form-group w-100 mx-2 mt-4">
                        <label class="text-white" for="competencia">Competencia</label>
                        <select wire:model.live="competenciaSelected" wire:change="agregarCompetenciaBusqueda" type="text" class="form-control" id="competencia" style="height: 100%;">
                            <option value="">Selecciona una competencia</option>
                            @foreach ($categoriasLista as $item)
                                <option value="{{$item->id}}">{{$item->division}} / {{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-5 row">
                    @foreach ($competenciasBusqueda as $competencia)
                        <div class="col-3 my-2">
                            <div class="btn btn-outline-success d-flex">
                                <div class="w-75 d-flex justify-content-center align-items-center">
                                    <span>
                                        {{ $competencia->division }}
                                    </span>
                                </div>
                                <div class="w-25">
                                    <button class="mx-2 btn btn-outline-danger btn-sm" wire:click="eliminarCompetenciaBusqueda({{ $competencia->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5 text-white">
        @foreach ($resultados as $categoriaId => $categoriaData)
            <div class="col-lg-4 col-md-6 col-sm-12 my-2">
                <div class="w-100 rounded-lg bg-dark p-5 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="text-center text-white">
                            {{ $categoriaData['division'] }}
                        </span>
                    </div>
                    <div class="mt-5 d-flex flex-column w-100">
                        @foreach ($categoriaData['competidores'] as $competidorId => $competidorData)
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="px-2 d-flex flex-column justify-content-center align-items-center" style="width: 20%">
                                    <img src="{{ $competidorData['foto'] }}" alt="foto_de_perfil" class="rounded-circle" style="height: 3rem; width: 3rem;">
                                    
                                </div>
                                <div class="px-2 d-flex flex-column" style="width: 40%">
                                    <p class="text-white h5">
                                        {{ $competidorData['nombre_competidor'] }} {{ $competidorData['apellido_competidor'] }}
                                    </p>
                                </div>
                                <div class="px-2 d-flex flex-column" style="width: 40%">
                                    <p class="text-white h6">
                                        {{ $competidorData['total_puntos'] }}
                                    </p>
                                    <p>
                                        Puntos
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        $(document).ready(function() {
           // $('#competencia').select2();
        });
    </script>
</div>
