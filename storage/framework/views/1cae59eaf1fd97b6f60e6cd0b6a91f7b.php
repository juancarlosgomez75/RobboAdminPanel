<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de máquinas</h5>
                    
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="maquinas/crear" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 <?php if(!$filtroOn): ?> hide <?php endif; ?>" id="Filtros">

                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por número" wire:model.change="filtroHardware">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por estudio" wire:model.change="filtroEstudio">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 7%;">#</th>
                                <th scope="col">Hardware</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Ciudad</th>
                                <th scope="col">Estudio</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($Machines)): ?>
                            <?php $__currentLoopData = $Machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $Maquina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($Maquina['ID']); ?></th>
                                    <td><?php echo e($Maquina["FirmwareID"] ?? 'N/R'); ?></td>
                                    <td><?php echo e($Maquina['Tipo'] ?? 'N/R'); ?></td>
                                    <td><?php echo e($Maquina['Location'] ?? 'No especificada'); ?></td>
                                    <td><?php echo e($Maquina['StudyData']["StudyName"] ?? 'No especificada'); ?></td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="maquina/<?php echo e($Maquina['ID']); ?>">Visualizar</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\maquinas\index.blade.php ENDPATH**/ ?>