<div>
    
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de máquinas</h5>
                    
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 <?php if(!$filtroOn): ?> hide <?php endif; ?>" id="Filtros">

                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por número" wire:model.change="filtroHardware">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por estudio" wire:model.change="filtroEstudio">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" wire:model.change="filtroEstadoEstudio">
                                <option value="-1">Todos estudios</option>
                                <option value="0">Estudios inactivos</option>
                                <option value="1">Estudios activos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 5%;">#</th>
                                <th scope="col" style="width: 5%;">Id</th>
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('hardware')">
                                    <!--[if BLOCK]><![endif]--><?php if($ordenarPor=="hardware"): ?>
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
                                    Hardware
                                </th>
                                <th scope="col">Tipo</th>
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('city')">
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
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('study')">
                                    <!--[if BLOCK]><![endif]--><?php if($ordenarPor=="study"): ?>
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
                                    Estudio
                                </th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php if(!empty($Machines)): ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $Machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $Maquina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($index); ?></th>
                                    <th scope="row"><?php echo e($Maquina['ID']); ?></th>
                                    <td><?php echo e($Maquina["FirmwareID"] ?? 'N/R'); ?></td>
                                    <td><?php echo e($Maquina['Tipo'] ?? 'N/R'); ?></td>
                                    <td><?php echo e($Maquina['Location'] ?? 'No especificada'); ?></td>
                                    <td><?php echo e($Maquina['StudyData']["StudyName"] ?? 'No especificada'); ?></td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="maquina/<?php echo e($Maquina['ID']); ?>">Visualizar</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/maquinas/index.blade.php ENDPATH**/ ?>