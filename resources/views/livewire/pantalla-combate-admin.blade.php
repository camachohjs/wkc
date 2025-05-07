{{-- Vista del combate --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-3 d-flex align-items-center justify-content-center text-center">
            <button wire:click="regresar" class="btn btn-primary mt-3">Regresar</button>
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
                <h1 id="timer" class="{{ !$timerIsRunning ? 'blinking' : '' }} tiempo">
                    {{ gmdate('i:s', $seconds) }}</h1><br>
            </div>
            <div class="col-2 text-yellow" style="text-align: left">
                <button class="btn btn-cronometer" data-bs-toggle="modal" data-bs-target="#timerSettingsModal">
                    <h1 style="font-size: 2.5rem;"><i class="bi bi-clock"></i></h1>
                </button>
            </div>
            <div class="col-2 p-3">
                <img src="{{ asset('Img/KARATE.png') }}" class="img-fluid" alt="WKC - KARATE">
            </div>
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center justify-content-center text-center">
            @if ($combate->descripcion == 'final')
                @if (!$finalRoundCompletado)
                    <button wire:click="finalizarRound"
                        class="btn btn-warning mt-3 @if ($isButtonDisabled) disabled @endif ">
                        Finalizar round
                    </button>
                @else
                    <button wire:click="finalizarCombate"
                        class="btn btn-danger mt-3 @if ($isButtonDisabled) disabled @endif ">
                        Finalizar combate
                    </button>
                @endif
            @else
                <button wire:click="finalizarCombate"
                    class="btn btn-danger mt-3 @if ($isButtonDisabled) disabled @endif ">
                    Finalizar combate
                </button>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="timerSettingsModal" tabindex="-1" aria-labelledby="timerSettingsModalLabel"
        aria-hidden="true" data-backdrop="static" wire:ignore>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="timerSettingsModalLabel">Configuración del cronómetro</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"><i
                            class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body row">
                    <!-- Controles para seleccionar minutos y segundos -->
                    <div class="d-flex flex-column mb-3 col-6">
                        <span class="mb-2">Ingresa el tiempo restante por favor</span>

                        <div class="d-flex justify-content-center">
                            <!-- Select de minutos -->
                            <div class="me-3">
                                <label for="timerMinutes" class="form-label">Minutos</label>
                                <select class="form-select custom-form-control" wire:model.defer="minutes"
                                    id="timerMinutes">
                                    @for ($i = 0; $i <= 59; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Select de segundos -->
                            <div>
                                <label for="timerSeconds" class="form-label">Segundos</label>
                                <select class="form-select custom-form-control" wire:model.defer="seconds"
                                    id="timerSeconds">
                                    @for ($i = 0; $i <= 59; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <!-- Botones de ajustes rápidos -->
                        <div class="d-flex justify-content-between mb-3 flex-wrap">
                            <button type="button" class="btn btn-outline-light mb-2" wire:click="addTimeSeconds(60)">1
                                min</button>
                            <button type="button" class="btn btn-outline-light mb-2 mx-2"
                                wire:click="addTimeSeconds(50)">50
                                seg</button>
                            <button type="button" class="btn btn-outline-light mb-2" wire:click="addTimeSeconds(120)">2
                                min</button>
                            <button type="button" class="btn btn-outline-light mb-2" wire:click="addTimeSeconds(300)">5
                                min</button>
                            <button type="button" class="btn btn-outline-light mb-2"
                                wire:click="addTimeSeconds(600)">10
                                min</button>
                            <button type="button" class="btn btn-outline-light mb-2"
                                wire:click="addTimeSeconds(1200)">20
                                min</button>
                        </div>

                        <!-- Botones para agregar tiempo -->
                        <div class="d-flex justify-content-between mb-3">
                            <button type="button" class="btn btn-outline-light" wire:click="addTimeSeconds(5)">+5
                                sec</button>
                            <button type="button" class="btn btn-outline-light" wire:click="addTimeSeconds(10)">+10
                                sec</button>
                        </div>
                    </div>
                </div>

                <!-- Botones de aceptar y cancelar -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-warning w-100" wire:click="saveTimerSettings"
                        data-bs-dismiss="modal"><i class="bi bi-check2"></i> OK</button>
                </div>
            </div>
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-12 col-md-3 text-center">
            @if ($invertirParticipantes)
                <img src="https://flagcdn.com/h40/{{ $combate->participante2->alumno->codigo_bandera ?? ($combate->participante2->maestro->codigo_bandera ?? 'unknown') }}.png"
                    alt="{{ $combate->participante2->alumno->nacionalidad ?? ($combate->participante2->maestro->nacionalidad ?? '') }}"
                    class="img-flag-1">
                <img src="{{ $combate->participante2->alumno->foto ?? ($combate->participante2->maestro->foto ?? asset('libs/images/profile/user-1.png')) }}"
                    class="img-combate" alt="Imagen del competidor" style="border: 1pt solid #0049b6;">
            @else
                <img src="https://flagcdn.com/h40/{{ $combate->participante1->alumno->codigo_bandera ?? ($combate->participante1->maestro->codigo_bandera ?? 'unknown') }}.png"
                    alt="{{ $combate->participante1->alumno->nacionalidad ?? ($combate->participante1->maestro->nacionalidad ?? '') }}"
                    class="img-flag-1">
                <img src="{{ $combate->participante1->alumno->foto ?? ($combate->participante1->maestro->foto ?? asset('libs/images/profile/user-1.png')) }}"
                    class="img-combate" alt="Imagen del competidor" style="border: 1pt solid #0049b6;">
            @endif
        </div>
        @if ($invertirParticipantes)
            <div class="col-12 col-md-3 text-center cursor-pointer">
                <div style="color: #0049b6; background: linear-gradient(145deg, #1c1c1c, #000000); border-radius:5px;"
                    wire:click="sumarPunto(2)">
                    <h1 class="puntos">{{ $puntosParticipante2 }}</h1>
                </div>
                <button type="button" class="btn w-100" wire:click="openCalculator(2)"
                    style="color: #0049b6; background: linear-gradient(145deg, #0e0e0e, #000000); border-radius:5px;">
                    <i class="bi bi-calculator"></i>
                </button>
            </div>
            <div class="col-12 col-md-3 text-center cursor-pointer">
                <div style="color: #c83737; background: linear-gradient(145deg, #1c1c1c, #000000); border-radius:5px;"
                    wire:click="sumarPunto(1)">
                    <h1 class="puntos">{{ $puntosParticipante1 }}</h1>
                </div>
                <button type="button" class="btn w-100" wire:click="openCalculator(1)"
                    style="color: #c83737; background: linear-gradient(145deg, #0e0e0e, #000000); border-radius:5px;">
                    <i class="bi bi-calculator"></i>
                </button>
            </div>
        @else
            <div class="col-12 col-md-3 text-center cursor-pointer">
                <div style="color: #0049b6; background: linear-gradient(145deg, #1c1c1c, #000000); border-radius:5px;"
                    wire:click="sumarPunto(1)">
                    <h1 class="puntos">{{ $puntosParticipante1 }}</h1>
                </div>
                <button type="button" class="btn w-100" wire:click="openCalculator(1)"
                    style="color: #0049b6; background: linear-gradient(145deg, #0e0e0e, #000000); border-radius:5px;">
                    <i class="bi bi-calculator"></i>
                </button>
            </div>
            <div class="col-12 col-md-3 text-center cursor-pointer">
                <div style="color: #c83737; background: linear-gradient(145deg, #1c1c1c, #000000); border-radius:5px;"
                    wire:click="sumarPunto(2)">
                    <h1 class="puntos">{{ $puntosParticipante2 }}</h1>
                </div>
                <button type="button" class="btn w-100" wire:click="openCalculator(2)"
                    style="color: #c83737; background: linear-gradient(145deg, #0e0e0e, #000000); border-radius:5px;">
                    <i class="bi bi-calculator"></i>
                </button>
            </div>
        @endif

        <!-- Modal para la calculadora -->
        <div class="modal" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel"
            aria-hidden="true" wire:ignore.self data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-content-2 align-items-center">
                    <div class="modal-body fondo-calculadora" style="padding: 0;">
                        <!-- Calculadora -->
                        <div id="calculator" class="calculator">
                            <input type="number" class="calculator-screen z-depth-1"
                                wire:model.lazy="puntosCalculadora" readonly placeholder="0">
                            <div class="calculator-keys">
                                <!-- Fila 1 -->
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(1)">1</button>
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(2)">2</button>
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(3)">3</button>

                                <!-- Fila 2 -->
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(4)">4</button>
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(5)">5</button>
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(6)">6</button>

                                <!-- Fila 3 -->
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(7)">7</button>
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(8)">8</button>
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(9)">9</button>

                                <!-- Fila 4 -->
                                <button class="button-calculator btn btn-linear waves-effect"
                                    wire:click="addToPoints(0)">0</button>
                                <button class="button-calculator btn btn-danger wide waves-effect"
                                    wire:click="deleteLastDigit"><i class="bi bi-arrow-left"></i></button>

                                <!-- Fila 5 -->
                                <button class="button-calculator btn btn-warning wide waves-effect"
                                    wire:click="updatePoints"><i class="bi bi-check2-square"></i></button>
                                <button class="button-calculator btn btn-success  waves-effect"
                                    wire:click="sumPoints"><i class="bi bi-plus-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3 text-center">
            @if ($invertirParticipantes)
                <div class="text-center">
                    <img src="https://flagcdn.com/h40/{{ $combate->participante1->alumno->codigo_bandera ?? ($combate->participante1->maestro->codigo_bandera ?? 'unknown') }}.png"
                        alt="{{ $combate->participante1->alumno->nacionalidad ?? ($combate->participante1->maestro->nacionalidad ?? '') }}"
                        class="img-flag-1">
                    <img src="{{ $combate->participante1->alumno->foto ?? ($combate->participante1->maestro->foto ?? asset('libs/images/profile/user-1.png')) }}"
                        class="img-combate" alt="Imagen del competidor" style="border: 1pt solid #c83737;">
                </div>
            @else
                <div class="text-center">
                    <img src="https://flagcdn.com/h40/{{ $combate->participante2->alumno->codigo_bandera ?? ($combate->participante2->maestro->codigo_bandera ?? 'unknown') }}.png"
                        alt="{{ $combate->participante2->alumno->nacionalidad ?? ($combate->participante2->maestro->nacionalidad ?? '') }}"
                        class="img-flag-1">
                    <img src="{{ $combate->participante2->alumno->foto ?? ($combate->participante2->maestro->foto ?? asset('libs/images/profile/user-1.png')) }}"
                        class="img-combate" alt="Imagen del competidor" style="border: 1pt solid #c83737;">
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-4 text-center" style="color: #0049b6;">
            <h1>
                <b>
                    @if ($invertirParticipantes)
                        {{ ucwords(strtolower($combate->participante2->nombre)) . ' ' . ucwords(strtolower($combate->participante2->apellidos)) }}<br>
                        {{ ucwords(strtolower($combate->participante2->alumno->escuelas[0]->nombre ?? ($combate->participante2->maestro->escuelas[0]->nombre ?? ''))) }}
                    @else
                        {{ ucwords(strtolower($combate->participante1->nombre)) . ' ' . ucwords(strtolower($combate->participante1->apellidos)) }}<br>
                        {{ ucwords(strtolower($combate->participante1->alumno->escuelas[0]->nombre ?? ($combate->participante1->maestro->escuelas[0]->nombre ?? ''))) }}
                    @endif
                </b>
            </h1>
        </div>
        <div class="col-4 text-center text-white">
            <button wire:click="toggleParticipantes" class="btn btn-dark" style="background-color: #00000000;">
                @if ($invertirParticipantes)
                    <a class="control-combate-1">
                        <i class="bi bi-arrow-left-right"></i>
                    </a>
                @else
                    <a class="control-combate-2">
                        <i class="bi bi-arrow-left-right"></i>
                    </a>
                @endif
            </button>

            <h1> vs.</h1>

        </div>
        <div class="col-4 text-center" style="color: #c83737;">
            <h1>
                <b>
                    @if ($invertirParticipantes)
                        {{ ucwords(strtolower($combate->participante1->nombre)) . ' ' . ucwords(strtolower($combate->participante1->apellidos)) }}<br>
                        {{ ucwords(strtolower($combate->participante1->alumno->escuelas[0]->nombre ?? ($combate->participante1->maestro->escuelas[0]->nombre ?? ''))) }}
                    @else
                        {{ ucwords(strtolower($combate->participante2->nombre)) . ' ' . ucwords(strtolower($combate->participante2->apellidos)) }}<br>
                        {{ ucwords(strtolower($combate->participante2->alumno->escuelas[0]->nombre ?? ($combate->participante2->maestro->escuelas[0]->nombre ?? ''))) }}
                    @endif
                </b>
            </h1>
        </div>
    </div>

    <div class="row text-amarillo">
        <div class="col-12 col-md-4 text-center d-flex align-items-center justify-content-center">
            <h2>División en curso: {{ $combate->categoria->division }}</h2>
        </div>

        <div class="col-12 col-md-4 text-center">
            <button wire:click="toggleControles" class="btn btn-dark" style="background-color: #00000000;">
                @if ($invertirControles)
                    <a class="control-combate-1">
                        <i class="bi bi-dpad-fill"></i>
                    </a>
                @else
                    <a class="control-combate-2">
                        <i class="bi bi-dpad-fill"></i>
                    </a>
                @endif
            </button>
            @if ($showWarning)
                <div class="alert alert-danger text-center blinking-alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ $warningMessage }}
                </div>
            @endif
        </div>

        <div class="col-12 col-md-4 text-center">
            @if ($siguienteCombate)
                <h3 class="text-white">
                    Próximo combate:
                    @if ($siguienteCombate->descripcion == 'final')
                        Final 🥇
                    @elseif ($siguienteCombate->descripcion == 'tercer lugar')
                        Tercer lugar 🥉
                    @endif
                </h3>

                <h3 class="text-center">
                    <span class="{{ isset($siguienteCombate->participante1) ? 'text-yellow' : 'text-white' }}">
                        {{ ucwords(strtolower($siguienteCombate->participante1->nombre ?? '?')) . ' ' . ucwords(strtolower($siguienteCombate->participante1->apellidos ?? '')) }}
                    </span>
                    <span class="text-white">vs.</span>
                    <span class="{{ isset($siguienteCombate->participante2) ? 'text-yellow' : 'text-white' }}">
                        {{ ucwords(strtolower($siguienteCombate->participante2->nombre ?? '?')) . ' ' . ucwords(strtolower($siguienteCombate->participante2->apellidos ?? '')) }}
                    </span>
                </h3>
            @else
                <h3 class="text-white">
                    Gran final 🥇
                </h3>
            @endif
        </div>
    </div>

    {{-- Botón para mostrar la vista pública --}}
    <audio id="warningSound" src="{{ asset('sounds/warning.mp3') }}"></audio>
    <audio id="endSound" src="{{ asset('sounds/end.mp3') }}"></audio>
</div>

<script>
    document.addEventListener('keydown', function(event) {
        let isModalOpen = $("#timerSettingsModal").hasClass('show');
        console.log("Modal estado:", isModalOpen)
        if (isModalOpen) {
            console.log("Modal abierto")
            return;
        }
        let invertir = @this.invertirControles;
        let invertirCompetidores = @this.invertirParticipantes;

        if (!invertirCompetidores) {
            if (!invertir) {
                if (event.key === 'ArrowRight') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 1
                    });
                } else if (event.key === 'ArrowLeft') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 2
                    });
                } else if (event.key === ' ' || event.key === 'Spacebar') {
                    event.preventDefault();
                    Livewire.dispatch('pausarBarraEspacio');
                }
            } else {
                // Invertir controles
                if (event.key === 'ArrowRight') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 2
                    });
                } else if (event.key === 'ArrowLeft') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 1
                    });
                } else if (event.key === ' ' || event.key === 'Spacebar') {
                    event.preventDefault();
                    Livewire.dispatch('pausarBarraEspacio');
                }
            }
        } else {
            if (!invertir) {
                if (event.key === 'ArrowRight') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 2
                    });
                } else if (event.key === 'ArrowLeft') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 1
                    });
                } else if (event.key === ' ' || event.key === 'Spacebar') {
                    event.preventDefault();
                    Livewire.dispatch('pausarBarraEspacio');
                }
            } else {
                // Invertir controles
                if (event.key === 'ArrowRight') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 1
                    });
                } else if (event.key === 'ArrowLeft') {
                    Livewire.dispatch('sumarPunto', {
                        participante: 2
                    });
                } else if (event.key === ' ' || event.key === 'Spacebar') {
                    event.preventDefault();
                    Livewire.dispatch('pausarBarraEspacio');
                }
            }
        }
    });

    let intervalId = null;

    document.addEventListener('DOMContentLoaded', () => {
        Livewire.on('startTimer', () => {
            console.log("tiempo iniciado");
            if (intervalId !== null) clearInterval(intervalId);
            intervalId = setInterval(() => {
                Livewire.dispatch('decrementTimer');
            }, 1000);
        });

        Livewire.on('refreshTimerDisplay', (seconds) => {
            console.log("refreshTimerDisplay");
            document.getElementById('timer').textContent = formatTime(seconds);
        });

        function formatTime(seconds) {
            let minutes = Math.floor(seconds / 60);
            let secs = seconds % 60;
            return `${pad(minutes)}:${pad(secs)}`;
        }

        function pad(value) {
            return String(value).padStart(2, '0');
        }

        Livewire.on('playSound', (type) => {
            console.log('Tipo de sonido recibido:', type);
            if (Array.isArray(type)) {
                type = type[0];
            }
            let sound;
            switch (type) {
                case 1:
                    sound = document.getElementById('warningSound');
                    console.log('Se ha encontrado el sonido de advertencia');
                    break;
                case 2:
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
        window.addEventListener('openCalculatorModal', () => {
            $('#calculatorModal').modal('show');
        });

        window.addEventListener('closeCalculatorModal', () => {
            $('#calculatorModal').modal('hide');
        });
    });
</script>
