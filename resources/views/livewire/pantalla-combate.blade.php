{{-- Vista del combate --}}
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-4 text-center">
            <button wire:click="regresar" class="btn btn-primary mt-3">Regresar</button>
        </div>
        <div class=" col-4 brand-logo d-flex align-items-center justify-content-center text-center">
            <div class="text-nowrap logo-img text-center mt-2">
                <img src="{{ asset('Img/KARATE.png') }}" style="width: 20%;" alt="WKC - KARATE">
            </div>
        </div>
        <div class="col-4 text-center"></div>
    </div>
    <div class="row">
        <div class="col-4 text-center">
            <img src="{{ $combate->participante1->alumno->foto ?? $combate->participante1->maestro->foto ?? asset('libs/images/profile/user-1.png') }}" class="img-combate" alt="Imagen del competidor">
        </div>
        <div class="col-4">
            <div class="row">
                <div class="col-4" style="text-align: right; color: #0049b6;"><h1 style="font-size: 8rem;">{{ $puntosParticipante1 }} </h1></div>
                <div class="col-4 text-center text-yellow d-flex align-items-center justify-content-center"><h2> Puntos</h2></div>
                <div class="col-4 text-left" style="color: #c83737;"><h1 style="font-size: 8rem;">{{ $puntosParticipante2 }}</h1></div>
            </div>
            <div class="row">
                <div class="col-4 text-yellow" style="text-align: right"></div>
                <div class="col-4 text-center text-white d-flex align-items-center justify-content-center"><h1 style="font-size: 3rem;" id="timer" {{-- class="{{ !$timerIsRunning ? 'blinking' : '' }}" --}}>{{ gmdate('i:s', $seconds) }}</h1></div>
                <div class="col-4 text-left text-yellow"></div>
            </div>
        </div>

        <div class="col-4 text-center">
            <img src="{{ $combate->participante2->alumno->foto ?? $combate->participante2->maestro->foto ?? asset('libs/images/profile/user-1.png') }}" class="img-combate" alt="Imagen del competidor">
        </div>
    </div>
    <div class="row">
        <div class="col-4 text-center" style="color: #0049b6;"><h1><b>{{ ucwords(strtolower($combate->participante1->nombre)) . ' ' . ucwords(strtolower($combate->participante1->apellidos)) }}<br>({{ ucwords(strtolower( $combate->participante1->alumno->escuelas[0]->nombre ?? $combate->participante1->maestro->escuelas[0]->nombre)) }})</b></h1></div>
        <div class="col-4 text-center text-white"><h1> vs.</h1>
            {{-- Sección de advertencia --}}
            {{-- @if($showWarning)
                <div class="alert alert-danger text-center blinking-alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ $warningMessage }}
                </div>
            @endif --}}
        </div>
        <div class="col-4 text-center" style="color: #c83737;"><h1><b>{{ ucwords(strtolower($combate->participante2->nombre)).' '.ucwords(strtolower($combate->participante2->apellidos)) }}<br>({{ ucwords(strtolower( $combate->participante2->alumno->escuelas[0]->nombre ?? $combate->participante2->maestro->escuelas[0]->nombre)) }})</b></h1></div>
    </div>

    <audio id="warningSound" src="{{ asset('sounds/warning.mp3') }}"></audio>
    <audio id="endSound" src="{{ asset('sounds/end.mp3') }}"></audio>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('actualizarTiempo', seconds => {
            console.log('hola', seconds);
            document.getElementById('timer').textContent = new Date(seconds * 1000).toISOString().substr(14, 5);
        });

        Livewire.on('actualizarPuntaje', (puntosParticipante1, puntosParticipante2) => {
            document.querySelector('.puntos-participante1').textContent = puntosParticipante1;
            document.querySelector('.puntos-participante2').textContent = puntosParticipante2;
        });
    });
</script>