<div class="container text-white text-center py-4 fs-4">
    <div class="row">
        <div class="col-12 pt-3">
            <h2><strong class="text-yellow">Check-In de pago </strong></h2>
        </div>
        <div class="col-md-4 col-12 pt-3">
            <p><strong class="text-yellow">Torneo:</strong> {{ $registroTorneo->torneo->nombre }}</p>
        </div>
        <div class="col-md-4 col-12 pt-3">
            <p><strong class="text-yellow">Nombre:</strong> {{ $registroTorneo->nombre }}
                {{ $registroTorneo->apellidos }}</p>
        </div>
        <div class="col-md-4 col-12 pt-3">
            <p><strong class="text-yellow">Peso:</strong> {{ $registroTorneo->peso }} Kg</p>
        </div>
        <div class="col-12 pt-3">
            <p><strong class="text-yellow">Categorías:</strong></p>
            <ul>
                <li>{{ $registroTorneo->categoria->division . ' - ' . $registroTorneo->categoria->nombre ?? 'Categoría no definida' }}
                    <input type="checkbox" class="genero-radio2" wire:model="checkPago" id="checkPago">
                </li>
            </ul>
        </div>
    </div>

    <div class="mb-3 text-center mt-5 ">
        <button class="btn btn-amarillo px-4 py-2" type="button" wire:click="actualizarPago" style="font-size: 20px">
            <i class="bi bi-check-lg"></i> Check-In
        </button>
    </div>
</div>
