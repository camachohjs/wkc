<div class="container p-5 text-white">
    <div class="text-center">
        <img src="{{ asset('Img/KARATE.png') }}" class="logo2" alt="WKC - KARATE">
    </div>
    <br>
    <form wire:submit.prevent="login">
        <div class="row mb-3 justify-content-center align-items-center">
            <h2 class="text-center">Inicio de Sesión</h2>
        </div>

        @if ($showError)
            <div class="alert alert-warning text-center" x-data="{ show: true }" x-show="show" x-init="init()">
                {{ $errors->first('error') }}
            </div>
        @endif

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3 text-center">
                <input type="text" placeholder="Email" class="form-control custom-form-control2 w-100" wire:model="email" @if($rememberMe) value="{{ old('email') }}" @endif>
                @error('email') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
                <div class="input-group" >
                </div>
            </div>
            <div class="col-md-4 pt-3 text-center">
                <div class="input-group">
                    <input type="{{ $showPassword ? 'text' : 'password' }}" placeholder="Contraseña" class="form-control custom-form-control2" wire:model="password" @if($rememberMe) value="{{ old('password') }}" @endif>
                    <button type="button" class="blanco btn btn-outline-secondary" wire:click="toggleShowPassword">
                        {!! $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>' !!}
                    </button>
                </div>
                @error('password') <span class="text-warning">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-4 pt-3 text-center">
                <div class="input-group">
                </div>
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3 text-center">
                <button type="submit" class="btn boton-iniciar">Iniciar Sesión</button>
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3">
                <input type="checkbox" class="contraseña-radio form-check-input" wire:model="rememberMe">
                <label class="texto-login">Recuérdame</label>
                <a href="{{ route('recuperar-password') }}" class="text-white texto-login1">Olvidé mi Contraseña</a>
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 text-center pt-3">
            </div>
            <div class="col-md-4 text-center pt-3">
                <span>¿No tienes cuenta?</span><br>
                <a class="btn btn3" href="{{ route('register') }}">
                    Registrarse
                </a>
            </div>
            <div class="col-md-4 text-center pt-3">
            </div>
        </div>
    </form>
</div>
