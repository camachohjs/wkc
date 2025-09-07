<div class="px-5">
    <div class="d-flex align-items-center justify-content-between">
        <div class="text-left">
            <h2 class="text-white">Rankings</h2>
        </div>
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Selecciona un ciclo
            </button>
            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton" style="background: linear-gradient(145deg, #1c1c1c, #000000);">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $ciclos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ciclo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><a class="dropdown-item" href="#" wire:click.prevent="exportarExcel('<?php echo e($ciclo); ?>')"><?php echo e($ciclo); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </ul>
        </div>
    </div>
    <div class="d-flex">
        <div class="w-100 d-flex flex-column">
            <div class="w-100 rounded-lg bg-dark p-4" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                <h5 class="text-white">
                    Filtros
                </h5><br>
                <div class="d-flex flex-column">
                    <div class="form-group w-100 mx-2">
                        <label class="text-white" for="competidor">Competidor</label>
                        <input wire:model.live.debounce.150ms="competidor" type="text" class="form-control" placeholder="Buscar Competidor..." id="competidor">
                    </div>
                    <div class="form-group w-100 mx-2 mt-4">
                        <label class="text-white" for="competencia">Competencia</label>
                        <select wire:model.live="competenciaSelected" wire:change="agregarCompetenciaBusqueda" type="text" class="form-control" id="competencia" style="height: 100%;">
                            <option value="">Selecciona una división</option>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categoriasLista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->division); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </select>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($mostrar): ?>
                <div class="mt-3 row">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $competenciasBusqueda; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $competencia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-3 my-2">
                            <div class="position-relative">
                                <div class="w-100 d-flex justify-content-center align-items-center p-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                    <span class="flex-grow-1 text-center text-white" style="font-size: x-large;">
                                        <?php echo e($competencia->division); ?>

                                    </span>
                                </div>
                                <button class="position-absolute top-0 end-0 btn btn-danger btn-sm m-1" style="border: none;" wire:click="eliminarCompetenciaBusqueda(<?php echo e($competencia->id); ?>)">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <div class="form-group w-100 mx-2 mt-4">
                    <label class="text-white" for="torneo">Torneo</label>
                    <div class="d-flex flex-nowrap overflow-auto">
                        <button wire:click="seleccionarTorneo('')" class="btn mx-2 my-2 <?php echo e($torneoSeleccionado === '' ? 'active' : ''); ?>"
                            style="background: <?php echo e($torneoSeleccionado === '' ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)'); ?>; 
                                    border: 2px solid #EBC010; border-radius: 0.5rem; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease; color: <?php echo e($torneoSeleccionado === '' ? 'black' : 'white'); ?>;">
                            Todos los Torneos
                        </button>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $torneosLista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $torneo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button wire:click="seleccionarTorneo('<?php echo e($torneo->nombre_torneo); ?>')" class="btn mx-2 my-2 <?php echo e($torneoSeleccionado === $torneo->nombre_torneo ? 'active' : ''); ?>"
                                style="background: <?php echo e($torneoSeleccionado === $torneo->nombre_torneo ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)'); ?>; 
                                        border: 2px solid #EBC010; border-radius: 0.5rem; 
                                        transition: transform 0.3s ease, box-shadow 0.3s ease; color: <?php echo e($torneoSeleccionado === $torneo->nombre_torneo ? 'black' : 'white'); ?>;">
                                <?php echo e($torneo->nombre_torneo); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button wire:click="cambiarAgrupamiento('category')" 
                            class="btn mx-2 my-2 <?php echo e($agrupamiento === 'category' ? 'active' : ''); ?>" 
                            style="background: <?php echo e($agrupamiento === 'category' ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)'); ?>; 
                                    border: 2px solid #EBC010; 
                                    border-radius: 0.5rem; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease; 
                                    color: <?php echo e($agrupamiento === 'category' ? 'black' : 'white'); ?>;">
                        Agrupar por categoría
                    </button>
                    <button wire:click="cambiarAgrupamiento('name')" 
                            class="btn mx-2 my-2 <?php echo e($agrupamiento === 'name' ? 'active' : ''); ?>" 
                            style="background: <?php echo e($agrupamiento === 'name' ? '#EBC010' : 'linear-gradient(145deg, #1c1c1c, #000000)'); ?>; 
                                    border: 2px solid #EBC010; 
                                    border-radius: 0.5rem; 
                                    transition: transform 0.3s ease, box-shadow 0.3s ease; 
                                    color: <?php echo e($agrupamiento === 'name' ? 'black' : 'white'); ?>;">
                        Agrupar por nombre
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-3 text-white">
        <!--[if BLOCK]><![endif]--><?php if($agrupamiento === 'category' && $torneoSeleccionado === ''): ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $rankings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoriaId => $participantes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-12 my-2">
                    <div class="w-100 p-3 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="row">
                            <div class="col-11">
                                <span class="text-white h5">
                                    <?php echo e($participantes->first()['division']); ?>

                                </span>
                            </div>
                            <div class="col-1">
                                <i class="bi bi-chevron-down" wire:click="toggleCategoria(<?php echo e($categoriaId); ?>)" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if(in_array($categoriaId, $categoriasAbiertas)): ?>
                            <div class="mt-3">
                                <div class="row text-center text-yellow">
                                    <div class="col-1"><strong>Posición</strong></div>
                                    <div class="col-3"><strong>Foto</strong></div>
                                    <div class="col-4"><strong>Nombre</strong></div>
                                    
                                    <div class="col-2"><strong>Total</strong></div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $participantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mt-2 text-center">
                                        <div class="col-1"><?php echo e($participante['posicion']); ?></div>
                                        <div class="col-3"><img src="<?php echo e($participante['foto']); ?>" alt="foto_de_perfil" class="rounded-circle foto-perfil" style="height: 3rem; width: 3rem;"></div>
                                        <div class="col-4"><?php echo e($participante['nombre']); ?></div>
                                        
                                        <div class="col-2"><?php echo e($participante['puntos']); ?></div>
                                        <div class="col-1 px-2 d-flex flex-column text-center">
                                            <span class="text-white h5 mb-1" wire:click="mostrarDetalles(<?php echo e($participante['persona']->id); ?>)" style="cursor: pointer;">
                                                <i class="bi bi-chevron-down"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php if($participanteSeleccionado && $participanteSeleccionado->id == $participante['persona']->id): ?>
                                        <div class="mt-3 p-3 rounded text-center">
                                            <h5 class="text-yellow">Detalles del Participante</h5>
                                            <p class="text-center"><img src="https://flagcdn.com/h24/<?php echo e($participanteSeleccionado->codigo_bandera ?? 'unknown'); ?>.png" alt="<?php echo e($participanteSeleccionado->nacionalidad ?? ''); ?>"></p>
                                            <p class="text-center"><strong class="text-yellow">Edad:</strong> <span class="text-white"></span><?php echo e($participanteSeleccionado->edad); ?></p>
                                            <p class="text-center"><strong class="text-yellow">Cinta:</strong> <span class="text-white"></span><?php echo e($participanteSeleccionado->cinta); ?></p>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php elseif($agrupamiento === 'category' && $torneoSeleccionado !== ''): ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $rankings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoriaId => $participantes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-12 my-2">
                    <div class="w-100 p-3 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="row">
                            <div class="col-11">
                                <span class="text-white h5">
                                    <?php echo e($participantes->first()['division']); ?>

                                </span>
                            </div>
                            <div class="col-1">
                                <i class="bi bi-chevron-down" wire:click="toggleCategoria(<?php echo e($categoriaId); ?>)" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if(in_array($categoriaId, $categoriasAbiertas)): ?>
                            <div class="mt-3">
                                <div class="row text-center text-yellow">
                                    <div class="col-1"><strong>Posición</strong></div>
                                    <div class="col-3"><strong>Foto</strong></div>
                                    <div class="col-4"><strong>Nombre</strong></div>
                                    
                                    <div class="col-2"><strong>Total</strong></div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $participantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mt-2 text-center">
                                        <div class="col-1"><?php echo e($participante['posicion']); ?></div>
                                        <div class="col-3"><img src="<?php echo e($participante['foto']); ?>" alt="foto_de_perfil" class="rounded-circle foto-perfil" style="height: 3rem; width: 3rem;"></div>
                                        <div class="col-4"><?php echo e($participante['nombre']); ?></div>
                                        
                                        <div class="col-2"><?php echo e($participante['puntos']); ?></div>
                                        <div class="col-1 px-2 d-flex flex-column text-center">
                                            <span class="text-white h5 mb-1" wire:click="mostrarDetalles(<?php echo e($participante['persona']->id); ?>)" style="cursor: pointer;">
                                                <i class="bi bi-chevron-down"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <!--[if BLOCK]><![endif]--><?php if($participanteSeleccionado && $participanteSeleccionado->id == $participante['persona']->id): ?>
                                        <div class="mt-3 p-3 rounded text-center">
                                            <h5 class="text-yellow">Detalles del Participante</h5>
                                            <p class="text-center"><img src="https://flagcdn.com/h24/<?php echo e($participanteSeleccionado->codigo_bandera ?? 'unknown'); ?>.png" alt="<?php echo e($participanteSeleccionado->nacionalidad ?? ''); ?>"></p>
                                            <p class="text-center"><strong class="text-yellow">Edad:</strong> <span class="text-white"></span><?php echo e($participanteSeleccionado->edad); ?></p>
                                            <p class="text-center"><strong class="text-yellow">Cinta:</strong> <span class="text-white"></span><?php echo e($participanteSeleccionado->cinta); ?></p>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php else: ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $rankings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $personaId => $participante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-12 my-2">
                    <div class="w-100 p-3 mt-3" style="background: linear-gradient(145deg, #1c1c1c, #000000); border: 2px solid #EBC010; border-radius: 0.5rem; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="<?php echo e($participante['foto']); ?>" alt="foto_de_perfil" class="rounded-circle foto-perfil" style="height: 3rem; width: 3rem;">
                            <span class="text-white h5">
                                <?php echo e($participante['nombre']); ?> <img src="https://flagcdn.com/h24/<?php echo e($participante['nacionalidad'] ?? 'unknown'); ?>.png" alt="<?php echo e($participante['nacionalidad_nombre'] ?? ''); ?>">
                            </span>
                            <i class="bi bi-chevron-down" wire:click="togglePersona(<?php echo e($personaId); ?>)" style="cursor: pointer;"></i>
                        </div>
                        <!--[if BLOCK]><![endif]--><?php if(in_array($personaId, $personasAbiertas)): ?>
                            <div class="mt-3">
                                <div class="row text-center text-yellow">
                                    <div class="col-2"><strong>Lugar</strong></div>
                                    <div class="col-4"><strong>Torneo</strong></div>
                                    <div class="col-4"><strong>División</strong></div>
                                    <div class="col-2"><strong>Puntos</strong></div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $participante['torneos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $torneo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mt-2 text-center">
                                        <div class="col-2"><?php echo e($torneo['lugar']); ?></div>
                                        <div class="col-4"><?php echo e($torneo['nombre_torneo']); ?></div>
                                        <div class="col-4"><?php echo e($torneo['categoria']); ?></div>
                                        <div class="col-2"><?php echo e($torneo['puntos']); ?></div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div><?php /**PATH C:\laragon\www\wkc\resources\views/livewire/clasificaciones-busqueda.blade.php ENDPATH**/ ?>