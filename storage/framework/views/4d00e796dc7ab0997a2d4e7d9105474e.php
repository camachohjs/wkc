<div class="container p-5 text-white">
    <div class="text-center">
        <img src="<?php echo e(asset('Img/KARATE.png')); ?>" class="logo2" alt="WKC - KARATE">
    </div>
    <br>
    <form wire:submit.prevent="login">
        <div class="row mb-3 justify-content-center align-items-center">
            <h2 class="text-center">Inicio de Sesión</h2>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($showError): ?>
            <div class="alert alert-warning text-center" x-data="{ show: true }" x-show="show" x-init="init()">
                <?php echo e($errors->first('error')); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 pt-3 text-center">
            </div>
            <div class="col-md-4 pt-3 text-center">
                <input type="text" placeholder="Email" class="form-control custom-form-control2 w-100" wire:model="email" <?php if($rememberMe): ?> value="<?php echo e(old('email')); ?>" <?php endif; ?>>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-warning"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
                    <input type="<?php echo e($showPassword ? 'text' : 'password'); ?>" placeholder="Contraseña" class="form-control custom-form-control2" wire:model="password" <?php if($rememberMe): ?> value="<?php echo e(old('password')); ?>" <?php endif; ?>>
                    <button type="button" class="blanco btn btn-outline-secondary" wire:click="toggleShowPassword">
                        <?php echo $showPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>'; ?>

                    </button>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-warning"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
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
                <a href="<?php echo e(route('recuperar-password')); ?>" class="text-white texto-login1">Olvidé mi Contraseña</a>
            </div>
            <div class="col-md-4 pt-3 text-center">
            </div>
        </div>

        <div class="row mb-3 align-items-center">
            <div class="col-md-4 text-center pt-3">
            </div>
            <div class="col-md-4 text-center pt-3">
                <span>¿No tienes cuenta?</span><br>
                <a class="btn btn3" href="<?php echo e(route('register')); ?>">
                    Registrarse
                </a>
            </div>
            <div class="col-md-4 text-center pt-3">
            </div>
        </div>
    </form>
</div>
<?php /**PATH C:\laragon\www\wkc\resources\views/livewire/login.blade.php ENDPATH**/ ?>