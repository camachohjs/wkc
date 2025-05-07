<div class="container p-5 text-white">
    <div class="text-center">
        <img src="{{ asset('Img/home/password.png') }}" class="logo2" alt="WKC - KARATE">
    </div>
    <br>
    <form wire:submit.prevent="recuperarPassword">
        <div class="row mb-3 justify-content-center align-items-center">
            <h2 class="text-center">Recuperar Contraseña</h2>
        </div>
        @if (session('success'))
            <div class="alert alert-success text-center" style="font-size: 20px;" x-init="init()">
                {{ session('success') }} <br>
            </div>
        @elseif (session('error'))
            <div class="alert alert-warning text-center" x-data="{ show: true }" x-show="show" x-init="init()">
                {{ session('error') }}
            </div>
        @endif
        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3 text-center">
                <input placeholder="Email" class="form-control custom-form-control2 w-100" type="email" wire:model="email">
                @error('email') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>
        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3 text-center">
                <button type="submit" class="btn boton-iniciar">Recuperar Contraseña</button>
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>
    </form>
</div>
