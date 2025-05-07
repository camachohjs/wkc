<div class="container p-5 text-white">
    <div class="text-center">
        <img src="{{ asset('Img/home/password.png') }}" class="logo2" alt="WKC - KARATE">
    </div>
    <br>

    @if (session('success'))
            <div class="alert alert-success text-center" style="font-size: 20px;">
                {{ session('success') }} <br>
            </div>
    @elseif (session('error'))
        <div class="alert alert-warning text-center" x-data="{ show: true }" x-show="show">
            {{ session('error') }}
        </div>
    @endif

    <form wire:submit.prevent="resetPassword">
        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3 text-center">
                <div class="input-group">
                    <input class="form-control custom-form-control2" type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password" placeholder="Nueva Contraseña">
                    <button type="button" class="blanco btn btn-outline-secondary" wire:click="toggleShowPassword">
                        {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                    </button>
                </div>
                @error('password') <span class="text-warning">{{ $message }}</span> @enderror
                <input class="form-control custom-form-control2 w-100 mb-4 mt-4" type="{{ $showPassword ? 'text' : 'password' }}" wire:model="password_confirmation" placeholder="Confirmar Contraseña">
                @error('passwordConfirmation') <span class="text-warning">{{ $message }}</span> @enderror
                <button  class="btn boton-iniciar mt-3" type="submit">Restablecer Contraseña</button>
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>
    </form>
</div>