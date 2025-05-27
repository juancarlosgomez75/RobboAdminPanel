<div class="position-fixed top-0 end-0 p-3" style="z-index: 1050; width: auto;">
    <div id="toast-container">
        <?php $__currentLoopData = $toasts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $toast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="toast show fade mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="toast-header">
                    <img src="<?php echo e(asset('img/bb.jpg')); ?>" class="rounded me-2" alt="Icono" width="20">
                    <strong class="me-auto"><?php echo e($toast['titulo'] ?? 'Notificación'); ?></strong>
                    <small>Hace un momento</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"
                        wire:click="eliminarToast"></button>
                </div>
                <div class="toast-body">
                    <?php echo e($toast['mensaje']); ?>

                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\alerts.blade.php ENDPATH**/ ?>