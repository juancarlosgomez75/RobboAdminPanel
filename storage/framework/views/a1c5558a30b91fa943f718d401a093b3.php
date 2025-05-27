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
                        <div class="col-md-5">
                            <input type="text" class="custom-input" placeholder="Filtrar por nombre" wire:model.change="filtroNombre">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 7%;">#</th>
                                <th class="w-40" scope="col">Nombre</th>
                                <th class="w-30" scope="col">Ciudad</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $datosUsar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($dato['Id']); ?></th>
                                    <td><?php echo e($dato["StudyName"] ?? 'Sin Nombre'); ?></td>
                                    <td><?php echo e($dato['City'] ?? 'No especificada'); ?></td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="estudio/<?php echo e($dato['Id']); ?>">Visualizar</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\estudios\listado.blade.php ENDPATH**/ ?>