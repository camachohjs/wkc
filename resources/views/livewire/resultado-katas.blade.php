{{-- Vista del combate --}}
<div class="container-fluid">
    <style>
        .input-calificacion,
        .form-control:disabled {
            font-size: 2rem;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            background: #0F1012;
            box-shadow: 0 0 5px #EBC010;
            border: none;
            color: white;
        }

        @media (max-width: 768px) {
            .input-calificacion {
                font-size: 1.5rem;
            }
        }

        .img-combate {
            width: 100%;
            max-width: 250px;
        }

        @media (max-width: 768px) {
            .img-combate {
                max-width: 150px;
            }
        }

        .text-yellow {
            color: #EBC010;
        }
    </style>

    <div class="row mb-4">
        <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
            <button wire:click="regresar" class="btn btn-guardar mt-3 ">Regresar</button>
        </div>
        <div class="col-12 col-md-4 text-center mb-3 mb-md-0">
            <img src="{{ asset('Img/KARATE.png') }}" class="img-fluid" style="width: 20%;" alt="WKC - KARATE">
        </div>
        <div class="col-12 col-md-4"></div>
    </div>

    <div class="row mb-4">
        <div class="col-12 col-md-3 text-center">
            <img src="{{ $kata->participante->alumno->foto ?? ($combate->participante->maestro->foto ?? asset('libs/images/profile/user-1.png')) }}"
                class="img-combate" alt="Imagen del competidor">
        </div>
        <div class="col-12 col-md-9">
            <h1 class="text-yellow"><b>Ganador:</b></h1>
            <h1 class="text-white">
                <b>{{ ucwords(strtolower($kata->participante->nombre)) . ' ' . ucwords(strtolower($kata->participante->apellidos)) }}&nbsp;&nbsp;({{ ucwords(strtolower($kata->participante->alumno->escuelas[0]->nombre ?? $kata->participante->maestro->escuelas[0]->nombre)) }})</b>
            </h1>
            <h1 class="text-white">
                <b>{{ $kata->categoria->division . ' - ' . ucwords(strtolower($kata->categoria->nombre)) }}</b>
                <img src="https://flagcdn.com/h40/{{ $kata->participante->alumno->codigo_bandera ?? ($kata->participante->maestro->codigo_bandera ?? 'unknown') }}.png"
                    alt="{{ $kata->participante->alumno->nacionalidad ?? ($kata->participante->maestro->nacionalidad ?? '') }}">
            </h1>
            <div class="text-white d-flex align-items-center justify-content-center mt-3">
                <h1 style="font-size: 3rem;"><b>Calificación: {{ number_format($kata->total_nuevo, 2) }} </b>
                    {{-- @if ($kata->total_nuevo != $kata->total)
                        <del style="color: #c01a1a; font-size: 2rem;">
                            <b>{{ number_format($kata->total,2) }}</b>
                        </del>
                    @endif --}}
                </h1>
            </div>
        </div>
    </div>

    @if ($controlArbitro)
        <div class="row">
            <div class="col-12 col-md-4 text-center">
                <label class="form-label">Juez 1</label>
                <div class="input-calificacion">
                    <span>{{ number_format($kata->calificacion_nueva_1, 2) }}</span>
                    <del style="color: #c01a1a;">
                        @if ($kata->calificacion_nueva_1 != $kata->calificacion_1)
                            {{ number_format($kata->calificacion_1, 2) }}
                        @endif
                    </del>
                </div>
            </div>
            <div class="col-12 col-md-4 text-center">
                <label class="form-label">Juez 2</label>
                <div class="input-calificacion">
                    <span>{{ number_format($kata->calificacion_nueva_2, 2) }}</span>
                    <del style="color: #c01a1a;">
                        @if ($kata->calificacion_nueva_2 != $kata->calificacion_2)
                            {{ number_format($kata->calificacion_2, 2) }}
                        @endif
                    </del>
                </div>
            </div>
            <div class="col-12 col-md-4 text-center">
                <label class="form-label">Juez 3</label>
                <div class="input-calificacion">
                    <span>{{ number_format($kata->calificacion_nueva_3, 2) }}</span>
                    <del style="color: #c01a1a;">
                        @if ($kata->calificacion_nueva_3 != $kata->calificacion_3)
                            {{ number_format($kata->calificacion_3, 2) }}
                        @endif
                    </del>
                </div>
            </div>
        </div>
    @endif
</div>

@foreach ($calificaciones as $juez => $valor)
    <div class="col-4 text-center">
        <div>
            <del>{{ $kata->{'calificacion_' . substr($juez, -1)} }}</del>
            <span>{{ $kata->{'calificacion_nueva_' . substr($juez, -1)} }}</span>
        </div>
        <input type="number" step="0.01" min="0" max="10" value="{{ $valor }}"
            class="form-control input-calificacion" wire:model="calificaciones.{{ $juez }}"
            onfocus="this.style.boxShadow='0 0 10px 4px yellow'" onblur="this.style.boxShadow='0 0 5px 2px yellow'">
    </div>
@endforeach
