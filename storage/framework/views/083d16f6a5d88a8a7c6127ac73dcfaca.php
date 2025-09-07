<div class="container">
    <div class="row mb-5 align-items-center">
        <div class="col-md-4">
            
            <div class="form-group pt-3">
                <label for="foto" style="color: #fff6">Da click para cambiar tu foto de perfil</label>
                <label for="foto" class="drop-banner" id="dropcontainer"
                    <?php if(!$foto_actual): ?> style="background-image: url('libs/images/profile/user-1.png')" <?php else: ?> style="background-image: url('<?php echo e($foto_actual); ?>')" <?php endif; ?>>
                    <input type="file" class="form-control custom-form-control" id="foto" wire:model="foto"
                        accept="image/png, image/jpeg, image/jpg">
                </label>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="col-md-8">
            <h1 class="text-white">Hola, <?php echo e(ucfirst($nombre) . ' ' . ucfirst($apellidos)); ?></h1>



        </div>
    </div>
    <form wire:submit.prevent="store">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control custom-form-control" wire:model="nombre"
                    style="border: .5px solid #fff;">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-md-4">
                <label for="apellidos" class="form-label">Apellido</label>
                <input type="text" class="form-control custom-form-control" wire:model="apellidos"
                    style="border: .5px solid #fff;">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['apellidos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input type="label" class="form-control custom-form-control"  wire:model="email"
                    style="border: .5px solid #fff;">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-4">
                <label for="fec" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control custom-form-control" wire:model="fec"
                    style="border: .5px solid #fff;">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['fec'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-md-4">
                <label for="cinta" class="form-label mb-2">Grado</label>
                <select id="cinta" name="cinta" wire:model="cinta" class="form-select mb-3"
                    style="border: .5px solid #fff;">
                    <option value="">Selecciona un grado</option>
                    <option value="Principiante">Principiante</option>

                    <option value="Intermedio">Intermedio</option>
                    <option value="Avanzado">Avanzado</option>
                    <option value="Negra">Cinta negra</option>
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['cinta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->

            </div>

            <div class="col-md-4">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="number" class="form-control custom-form-control" wire:model="telefono"
                    style="border: .5px solid #fff;">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['telefono'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="row mb-3 justify-content-space-between">
            <div class="col-md-4">
                <label for="peso" class="form-label">Peso</label>
                <div class="input-group" style="width: 50%;">
                    <input type="number" step="0.01" class="form-control custom-form-control" wire:model="peso"
                        style="border: .5px solid #fff;">
                    <span class="input-group-text" style="border: .5px solid #fff;">kg</span>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['peso'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <div class="col-md-4 text-left">
                
            </div>

            <div class="col-md-4 text-white">
                <label for="genero" class="form-label">Género</label>
                <div class="radio-container">
                    <input type="radio" class="genero-radio" id="male" name="genero" value="masculino"
                        wire:model="genero">
                    <label for="masculino">Hombre</label>

                    <input class="ml-50 genero-radio" type="radio" id="female" name="genero" value="femenino"
                        wire:model="genero">
                    <label for="femenino">Mujer</label>
                </div>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['genero'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-warning"><?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="mt-3 text-center">
            <button class="btn btn-guardar p-8 w-50" type="submit">Actualizar</button>
        </div>
    </form>
</div>
<?php /**PATH C:\laragon\www\wkc\resources\views/livewire/panel.blade.php ENDPATH**/ ?>