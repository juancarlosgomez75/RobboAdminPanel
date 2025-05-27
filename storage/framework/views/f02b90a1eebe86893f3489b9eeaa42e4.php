<div>
    <?php if($alerta): ?>
        <?php if($alerta_sucess!=""): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($alerta_sucess); ?>

        </div>
        <?php elseif($alerta_error!=""): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo e($alerta_error); ?>

        </div>
        <?php elseif($alerta_warning!=""): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo e($alerta_warning); ?>

        </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Historial de acciones realizadas</h5>
                    
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>

                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <i class="fa-solid fa-trash"></i> Borrar logs
                    </a>
                </div>
                <div class="col-md-12 <?php if(!$filtrosActivos): ?> hide <?php endif; ?>" id="Filtros">

                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por fecha" wire:model.change="filtroFecha">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por acción" wire:model.change="filtroAccion">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por autor" wire:model.change="filtroAutor">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Fecha y hora</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Sección</th>
                                <th scope="col">Acción</th>
                                <th scope="col">Autor</th>
                                <th scope="col"></th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($logs)): ?>
                            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($log->created_at); ?></td>
                                    <td><?php echo e($log->menu); ?></td>
                                    <td><?php echo e($log->section); ?></td>
                                    <td><?php echo e($log->action); ?></td>
                                    <td><?php echo e($log->author_info->username); ?></td>
                                    <td>
                                        <?php if($log->result): ?>
                                        <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                        <?php else: ?>
                                        <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-secondary btn-sm" href="<?php echo e(route("admin.log",$log->id)); ?>">Ver detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12">
                    <?php echo e($logs->links()); ?>

                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmar acción</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
         ¿Confirma que desea eliminar el log de acciones?, esta operación es destructiva e irrevertible.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="deleteLogs()">Confirmar y borrar</button>
        </div>
      </div>
    </div>
  </div>

    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\admin\logs.blade.php ENDPATH**/ ?>