<!-- resources/views/livewire/ganador.blade.php -->
<div class="container-fluid text-center mt-5">
    <style>
        .btn-guardar, .btn-guardar:hover{
            background: #EBC010;
            color: #000;
            font-weight: 700;
        }

        .ganador-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: auto;
        }
        .ganador-card {
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s;
        }
        .ganador-card:hover{
            transform: scale(1.1);
            z-index: 9;
            box-shadow: 0px 5px 5px 0px #ffc107;
        }
        .ganador-img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #ffc107;
            margin-bottom: 20px;
        }
        .ganador-name {
            font-size: 2.5rem;
            font-weight: bold;
            color: #EBC010;
        }
        .ganador-label {
            font-size: 2rem;
            color: #fff;
            margin-bottom: 10px;
        }
    </style>
    <div class="row mt-4">
        <div class="col-4">
            <button wire:click="regresar" class="btn btn-guardar mt-3">Regresar</button>
        </div>
        <div class=" col-4 brand-logo d-flex align-items-center justify-content-center text-center">
            <div class="text-nowrap logo-img text-center mt-2">
                <img src="{{ asset('Img/KARATE.png') }}" style="width: 20%;" alt="WKC - KARATE">
            </div>
        </div>
        <div class="col-4 text-center"></div>
    </div>
    <div class="mt-4">
        @if($ganador)
            @if(optional($ganador->alumno)->genero == 'femenino' || optional($ganador->maestro)->genero == 'femenino')
                <div class="ganador-label">La ganadora es:</div>
            @else
                <div class="ganador-label">El ganador es:</div>
            @endif
            <div class="ganador-container">
                <div class="ganador-card">
                    <div class="ganador-name">{{ ucwords(strtolower($ganador->nombre)) }} {{ ucwords(strtolower($ganador->apellidos)) }}</div>
                        <img src="{{ $ganador->alumno->foto ?? $ganador->maestro->foto ?? asset('libs/images/profile/user-1.png') }}" alt="Foto del ganador" class="ganador-img">
                    <div class="ganador-name">{{ ucwords(strtolower( $ganador->alumno->escuelas[0]->nombre ?? $ganador->maestro->escuelas[0]->nombre)) }}</div>
                </div>
            </div>
        @else
            <h2>El combate terminó en empate.</h2>
        @endif
    </div>
</div>
