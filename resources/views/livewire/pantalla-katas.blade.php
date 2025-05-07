<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3 d-flex align-items-center justify-content-center text-center">
            <button wire:click="regresar" class="btn btn-primary ">Regresar</button>
        </div>
        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center text-center">
            <div class="col-2 p-3">
                <img src="{{ asset('Img/KARATE.png') }}" class="img-fluid" alt="WKC - KARATE">
            </div>
            @if ($timerIsRunning)
                <div class="col-2 text-yellow" style="text-align: right">
                    <button wire:click="pauseTimer" wire:loading.attr="disabled" class="btn btn-cronometer">
                        <i style="font-size: 4rem;" class="bi bi-pause-fill"></i>
                    </button>
                </div>
            @else
                <div class="col-2 text-yellow" style="text-align: right">
                    <button wire:click="startTimer" wire:loading.attr="disabled" class="btn btn-cronometer">
                        <i style="font-size: 4rem;" class="bi bi-play-fill"></i>
                    </button>
                </div>
            @endif
            <div class="col-4 text-center text-white d-flex align-items-center justify-content-center flex-wrap">
                <h1 style="font-size: 3rem;" id="timer" class="{{ !$timerIsRunning ? 'blinking' : '' }}">
                    {{ gmdate('i:s', $seconds) }}</h1><br>
            </div>
            <div class="col-2 text-left text-yellow">
                <button wire:click="resetTimer" wire:loading.attr="disabled" class="btn btn-cronometer">
                    <h1 style="font-size: 3rem;"><i class="bi bi-clock"></i></h1>
                </button>
            </div>
            <div class="col-2 p-3">
                <img src="{{ asset('Img/KARATE.png') }}" class="img-fluid" alt="WKC - KARATE">
            </div>
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center justify-content-center text-center">
            <button wire:click="finalizarCombate" class="btn btn-danger">Terminar turno</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-3 text-center">
            <img src="{{ $kata->participante->alumno->foto ?? ($kata->participante->maestro->foto ?? asset('libs/images/profile/user-1.png')) }}"
                style="height: 250px;" class="img-combate img-fluid" alt="Imagen del competidor">
        </div>
        <div class="col-12 col-md-9">
            <div class="row ">
                <div class="col-12 text-white ">
                    <div
                        class="col-12 col-md-8 text-center text-white d-flex align-items-center justify-content-center flex-wrap">
                        <select wire:change="redirectToKata" wire:model.lazy="selectedKataId" class="form-select"
                            style="font-size: 3rem;">
                            @foreach ($resultados as $resultado)
                                <option value="{{ $resultado->kata->id }}">
                                    {{ ucwords(strtolower($resultado->participante->nombre)) . ' ' . ucwords(strtolower($resultado->participante->apellidos)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <h1 style="font-size: 2rem;">
                        <b>{{ ucwords(strtolower($resultado->participante->alumno->escuelas[0]->nombre ?? ($resultado->participante->maestro->escuelas[0]->nombre ?? ''))) }}</b>
                        <img src="https://flagcdn.com/h40/{{ $kata->participante->alumno->codigo_bandera ?? ($kata->participante->maestro->codigo_bandera ?? 'unknown') }}.png"
                            alt="{{ $kata->participante->alumno->nacionalidad ?? ($kata->participante->maestro->nacionalidad ?? '') }}">
                    </h1>
                    <h1 style="font-size: 2rem;">
                        <b>{{ $kata->categoria->division . ' - ' . ucwords(strtolower($kata->categoria->nombre)) }}</b>
                    </h1>
                </div>
                <div class="col-6 text-center text-white d-flex justify-content-start ">
                </div>
                <div class="col-6 text-center text-white d-flex justify-content-end ">
                    @if ($mostrarCalificacion)
                        <h1 style="font-size: 3rem;">Calificación: {{ $total_nuevo }}</h1>
                    @endif
                </div>
                <div class="col-12 text-white d-flex justify-content-end mt-2">
                    <h1 style="font-size: 2rem;"> {{ $siguienteParticipante ? 'Siguiente:' : '' }}
                        {{ $siguienteParticipante ? ucwords(strtolower($siguienteParticipante->participante->nombre)) . ' ' . ucwords(strtolower($siguienteParticipante->participante->apellidos)) : '' }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    {{-- Botones de control para el árbitro   --}}
    @if ($controlArbitro)
        <div class="row ">
            @foreach ($calificaciones as $juez => $valor)
                <div class="col-12 col-md-4 text-center">
                    <label class="form-label">{{ $juez }}</label>
                    <select class="form-control input-calificacion" wire:model="$juez"
                        wire:change="actualizarCalificacion('{{ $juez }}', $event.target.value)"
                        style="font-size: 4rem;" onfocus="this.style.boxShadow='0 0 10px 4px yellow'"
                        onblur="this.style.boxShadow='0 0 5px 2px yellow'" autofocus>
                        <option value="9.80">9.80</option>
                        <option value="9.81">9.81</option>
                        <option value="9.82">9.82</option>
                        <option value="9.83">9.83</option>
                        <option value="9.84">9.84</option>
                        <option value="9.85">9.85</option>
                        <option value="9.86">9.86</option>
                        <option value="9.87">9.87</option>
                        <option value="9.88">9.88</option>
                        <option value="9.89">9.89</option>
                        <option value="9.90" selected>9.90</option>
                        <option value="9.91">9.91</option>
                        <option value="9.92">9.92</option>
                        <option value="9.93">9.93</option>
                        <option value="9.94">9.94</option>
                        <option value="9.95">9.95</option>
                        <option value="9.96">9.96</option>
                        <option value="9.97">9.97</option>
                        <option value="9.98">9.98</option>
                        <option value="9.99">9.99</option>
                        <option value="10.00">10.00</option>
                    </select>
                </div>
            @endforeach
        </div>
    @endif

    <div class="container-fluid">
        <div class="table-responsive">
            <table class="table table-striped table-dark text-center">
                <tbody>
                    @foreach ($resultados as $resultado)
                        @if ($resultado->kata->total_nuevo)
                            <tr>
                                <td>
                                    <span>{{ $resultado->posicion }}</span>
                                </td>
                                <td>
                                    <span>{{ ucwords(strtolower($resultado->participante->nombre . ' ' . $resultado->participante->apellidos)) }}</span>
                                    <span>
                                        ({{ ucwords(strtolower($resultado->participante->alumno->escuelas[0]->nombre ?? $resultado->participante->maestro->escuelas[0]->nombre)) }})</span>
                                </td>
                                <td>
                                    @if ($resultado->kata->total_nuevo)
                                        <span>{{ number_format($resultado->kata->calificacion_nueva_1, 2) }}</span>
                                        <del style="color: #c01a1a;">
                                            @if (
                                                $resultado->kata->calificacion_nueva_1 != $resultado->kata->calificacion_1 &&
                                                    $resultado->kata->calificacion_1 !== null)
                                                {{ number_format($resultado->kata->calificacion_1, 2) }}
                                            @endif
                                        </del>&nbsp;&nbsp;
                                        <span>{{ number_format($resultado->kata->calificacion_nueva_2, 2) }}</span>
                                        <del style="color: #c01a1a;">
                                            @if (
                                                $resultado->kata->calificacion_nueva_2 != $resultado->kata->calificacion_2 &&
                                                    $resultado->kata->calificacion_2 !== null)
                                                {{ number_format($resultado->kata->calificacion_2, 2) }}
                                            @endif
                                        </del>&nbsp;&nbsp;
                                        <span>{{ number_format($resultado->kata->calificacion_nueva_3, 2) }}</span>
                                        <del style="color: #c01a1a;">
                                            @if (
                                                $resultado->kata->calificacion_nueva_3 != $resultado->kata->calificacion_3 &&
                                                    $resultado->kata->calificacion_3 !== null)
                                                {{ number_format($resultado->kata->calificacion_3, 2) }}
                                            @endif
                                        </del>
                                    @endif
                                </td>
                                <td>
                                    <span>{{ number_format($resultado->participante->katas[0]->total_nuevo, 2) ?? 'No ha participado' }}</span>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- <audio id="endSound" src="{{ asset('sounds/end.mp3') }}"></audio> --}}
</div>

<script>
    document.addEventListener('keydown', function(event) {
        const selects = document.querySelectorAll('.input-calificacion');
        let activeIndex = -1;

        if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            if (document.activeElement.tagName === 'SELECT') {
                // Prevenir el comportamiento predeterminado (cambiar valor) para flechas izquierda y derecha
                event.preventDefault();
            }
        }

        // Encontrar el índice del select activo
        selects.forEach((select, index) => {
            if (document.activeElement === select) {
                activeIndex = index;
            }
        });

        if (activeIndex !== -1) {
            if (event.key === 'ArrowRight' && activeIndex < selects.length - 1) {
                // Mover al siguiente select con ArrowRight
                selects[activeIndex + 1].focus();
            } else if (event.key === 'ArrowLeft' && activeIndex > 0) {
                // Mover al anterior select con ArrowLeft
                selects[activeIndex - 1].focus();
            }
        }

        if (event.key === ' ' || event.key === 'Spacebar') {
            event.preventDefault();
            Livewire.dispatch('pausarBarraEspacio');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {

        let intervalId = null;
        Livewire.on('startTimer', () => {
            console.log("tiempo iniciado");
            if (intervalId !== null) clearInterval(intervalId);
            intervalId = setInterval(() => {
                Livewire.dispatch('incrementTimer');
            }, 1000);
        });

        Livewire.on('playSound', (type) => {
            console.log('Tipo de sonido recibido:', type);
            if (Array.isArray(type)) {
                type = type[0];
            }
            let sound;
            switch (type) {
                case 1:
                    sound = document.getElementById('endSound');
                    console.log('Se ha encontrado el sonido de fin');
                    break;
                default:
                    console.error('Tipo de sonido no reconocido:', type);
                    return;
            }
            if (sound) {
                console.log('Reproduciendo sonido:', sound.src);
                sound.play().catch(error => console.error('Error al reproducir el sonido:', error));
            } else {
                console.error('Elemento de sonido no encontrado para el tipo:', type);
            }
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        console.log('Livewire cargado');
        Livewire.on('mostrarCalificacion', (data) => {
            console.log('URL recibida:', data);
            setTimeout(() => {
                Livewire.dispatch('redirigir', data);
            }, 2000);
        });
    });
</script>
