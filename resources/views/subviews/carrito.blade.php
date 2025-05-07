<div class="row text-center mb-4 mt-4">
    <div class="col-md-6">
        <button type="button" class="btn boton-carro mx-auto text-center" {{-- wire:click="selectUserType('alumno')" data-tipo="alumno" --}}>
            <p class="mt-3"><img src="{{ asset('Img/registro/mdi_karate.png') }}" alt="KARATE">
            Pase de espectador de 1 día</p><br>
            <p>Lorem ipsum dolor sit amet consectetur. Sit nec nisi suspendisse vel dui tincidunt et.</p>
        </button>
    </div>
    <div class="col-md-6">
        <button type="button" class="btn boton-carro mx-auto {{-- {{ $selectedButton === 'maestro' ? 'seleccionado' : '' }} --}}" {{-- wire:click="selectUserType('maestro')" data-tipo="maestro" --}}>
            <p class="mt-3"><img src="{{ asset('Img/registro/mdi_karate.png') }}" alt="KARATE">
                Pase de espectador de 2 días</p><br>
                <p>Lorem ipsum dolor sit amet consectetur. Sit nec nisi suspendisse vel dui tincidunt et.</p>
        </button>
    </div>
</div>