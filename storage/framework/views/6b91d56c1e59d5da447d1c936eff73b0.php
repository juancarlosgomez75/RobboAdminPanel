<div>
    
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="<?php echo e(route("estudios.index")); ?>" class="text-secondary">Estudios</a></li>
            <li class="breadcrumb-item "><a href="<?php echo e(route("estudio.ver",$estudioactual)); ?>" class="text-secondary">Estudio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modelo</li>
        </ol>
    </nav>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Información del modelo</h5>
                    <p class="card-text">Esta es la información almacenada actualmente para este modelo</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-5">
                            <label for="manname" class="form-label">Nombre de usuario</label>
                            <input type="text" class="form-control" id="manname" aria-describedby="mannameHelp" placeholder="Ejemplo: labtest" wire:model="drivername" <?php if(!$editing): ?> disabled <?php endif; ?>>
                            <div id="mannameHelp" class="form-text">Con el que se loguea en el driver</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="customname" class="form-label">Mensaje personalizado</label>
                            <input type="text" class="form-control" id="customname" aria-describedby="customnameHelp" placeholder="Ejemplo: Labtesito" wire:model="customname" <?php if(!$editing): ?> disabled <?php endif; ?>>
                            <div id="customnameHelp" class="form-text">Con el que el driver saluda si decides usarlo.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">¿Usarlo?</label>
                            <select class="form-select" <?php if(!$editing): ?> disabled <?php endif; ?> wire:model.live="usecustomname">
                                <option disabled value="-1">Seleccionar una opción</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>

                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estudio actual</label>
                            <select class="form-select" <?php if(!$editing): ?> disabled <?php endif; ?> wire:model.live="estudioactual">
                                
                                <!--[if BLOCK]><![endif]--><?php if(!empty($listadoestudios)): ?>
                                <option disabled value="0">Seleccionar un estudio</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $listadoestudios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estudio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <!--[if BLOCK]><![endif]--><?php if($estudio["Active"]): ?>
                                <option value="<?php echo e($estudio["Id"]); ?>"><?php echo e($estudio["FullName"]); ?></option>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php else: ?>
                                <option disabled value="0"></option>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <br>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Manager actual</label>
                            <select class="form-select" <?php if(!$editing): ?> disabled <?php endif; ?> wire:model="manageractual">
                                
                                <!--[if BLOCK]><![endif]--><?php if(!empty($listadomanagers)): ?>
                                <option disabled value="0">Seleccionar un manager</option>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $listadomanagers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($manager["Id"]); ?>"><?php echo e($manager["Name"]); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php else: ?>
                                <option disabled value="0"></option>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </select>
                            <br>
                        </div>
                        
                    </div>
                    
                </div>

                <div class="col-md-9">
                    <h5 class="card-title">Páginas que usa</h5>
                    <p class="card-text">Estas son las páginas que el modelo indica que usa</p><br>
                </div>
                
                <div class="col-md-3 text-end pe-4">
                    <br><br>
                    <!--[if BLOCK]><![endif]--><?php if($editing): ?>
                    <button type="button" class="btn btn-outline-secondary" wire:click="nuevaPagina()">
                        Añadir página
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-md-12">
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 7%;">#</th>
                                <th class="w-40" scope="col">Página</th>
                                <th class="w-30" scope="col">Nickname en página</th>
                                <th scope="col" style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            <!--[if BLOCK]><![endif]--><?php if(!empty($paginas)): ?>
                            <?php
                                $paginasUsadas = [];

                                foreach($paginas as $indice => $Page){
                                    $paginasUsadas[] = $Page['NickPage'];
                                }
                            ?>

                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paginas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indice => $Page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                     $seleccionActual = $Page['NickPage'] ?? '-1';
                                    
                                ?>

                                <tr>
                                    <td><?php echo e($indice + 1); ?></td>
                                    <td>
                                        <select class="form-select" wire:model.live="paginas.<?php echo e($indice); ?>.NickPage" <?php if(!$editing): ?> disabled <?php endif; ?>>
                                            <option disabled value="-1">Selecciona una página</option>
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $paginasDisponibles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <!--[if BLOCK]><![endif]--><?php if(!in_array($pag, $paginasUsadas) || $pag === $seleccionActual): ?>
                                                    <option value="<?php echo e($pag); ?>"><?php echo e($pag); ?></option>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="paginas.<?php echo e($indice); ?>.NickName" placeholder="Ejemplo: usuario.chatb" <?php if(!$editing): ?> disabled <?php endif; ?>>
                                    </td>
                                    <td>
                                        <a class="btn btn-outline-danger btn-sm" wire:click="eliminarPagina(<?php echo e($indice); ?>)">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">
                                    Sin páginas registradas
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


                        </tbody>
                    </table>
                    
                    
                </div>
                <div class="col-md-12 text-center">
                    <!--[if BLOCK]><![endif]--><?php if(!$editing): ?>
                    <button type="button" class="btn btn-outline-primary" wire:click="activarEdicion">
                        Editar información
                    </button>
                    <?php else: ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Guardar cambios
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if($active): ?>
                    <button type="button" class="btn btn-outline-danger ms-2" wire:click="desactivarUsuario()">
                        Desactivar modelo
                    </button>
                    <?php else: ?>
                    <button type="button" class="btn btn-outline-success ms-2" wire:click="activarUsuario()">
                        Activar modelo
                    </button>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de modificación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar cambios, confirma que la información aquí contenida es correcta.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardarEdicion" >Confirmar cambios</button>
            </div>
        </div>
        </div>
    </div>

  <script>
    const myModal = document.getElementById('myModal')
    const myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', () => {
    myInput.focus()
    })
</script>
    <br>


</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/modelos/viewedit.blade.php ENDPATH**/ ?>