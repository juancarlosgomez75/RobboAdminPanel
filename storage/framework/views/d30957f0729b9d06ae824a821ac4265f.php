<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de estudios</h5>
                    
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="estudios/crear" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 <?php if(!$filtroOn): ?> hide <?php endif; ?>" id="Filtros">

                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por nombre" wire:model.change="filtroNombre">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" wire:model.change="filtroEstado">
                                <option value="-1">Todos</option>
                                <option value="0">Inactivos</option>
                                <option value="1">Activos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 5%;">#</th>
                                <th class="w-10" scope="col" style="cursor: pointer;" wire:click="ordenarBy('id')" style="width: 5%;">
                                    <!--[if BLOCK]><![endif]--><?php if($ordenarPor=="id"): ?>
                                        <!--[if BLOCK]><![endif]--><?php if($ordenarDesc): ?>
                                            <a  class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-down me-2"></i>
                                            </a>
                                        <?php else: ?>
                                            <a class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-up me-2"></i>
                                            </a>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    Id
                                </th>
                                <th class="w-40" scope="col" style="cursor: pointer;" wire:click="ordenarBy('name')" >
                                    <!--[if BLOCK]><![endif]--><?php if($ordenarPor=="name"): ?>
                                        <!--[if BLOCK]><![endif]--><?php if($ordenarDesc): ?>
                                            <a  class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-down me-2"></i>
                                            </a>
                                        <?php else: ?>
                                            <a class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-up me-2"></i>
                                            </a>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    Nombre 
                                </th>
                                <th class="w-30" scope="col" style="cursor: pointer;" wire:click="ordenarBy('city')">
                                    <!--[if BLOCK]><![endif]--><?php if($ordenarPor=="city"): ?>
                                        <!--[if BLOCK]><![endif]--><?php if($ordenarDesc): ?>
                                            <a  class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-down me-2"></i>
                                            </a>
                                        <?php else: ?>
                                            <a class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-up me-2"></i>
                                            </a>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    Ciudad
                                </th>
                                <th></th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php if(!empty($datosUsar)): ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $datosUsar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($index+1); ?></th>
                                    <th scope="row"><?php echo e($dato['Id']); ?></th>
                                    <td><?php echo e($dato["StudyName"] ?? 'Sin Nombre'); ?></td>
                                    <td><?php echo e($dato['City'] ?? 'No especificada'); ?></td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($dato["Active"] ): ?>
                                        <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                        <?php else: ?>
                                        <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="estudio/<?php echo e($dato['Id']); ?>">Visualizar</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <tr class="text-center">
                                <td colspan="5">
                                    No se encontraron estudios
                                </td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/estudios/listado.blade.php ENDPATH**/ ?>