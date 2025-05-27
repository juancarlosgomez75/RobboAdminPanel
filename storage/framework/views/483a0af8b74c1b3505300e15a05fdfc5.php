
<?php $__env->startSection('title','Visualización de log'); ?>

<?php $__env->startSection("contenido"); ?>

<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Información de log</h5>
                <p class="card-text">Esta es la información almacenada actualmente para este log</p>
            </div>
            <div class="col-md-12 pt-3">
                <table class="table">
                    <tr>
                        <th scope="row">Menú</th>
                        <td><?php echo e($log->menu); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Sección</th>
                        <td><?php echo e($log->section); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Acción</th>
                        <td><?php echo e($log->action); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Detalles</th>
                        <td><?php echo e($log->details); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Autor</th>
                        <td><?php echo e($log->author_info->name); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Ip</th>
                        <td><?php echo e($log->ip_address); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Resultado</th>
                        <td>
                            <?php if($log->result): ?>
                            <span style="color: green;">Exitoso</span>
                            <?php else: ?>
                            <span style="color: red;">Fallido</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Fecha</th>
                        <td><?php echo e($log->created_at); ?></td>
                    </tr>
                  </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\admin\log.blade.php ENDPATH**/ ?>