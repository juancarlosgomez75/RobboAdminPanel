<div style="position: fixed; top: 0; right: 20px; z-index: 1050;">
    
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $toasts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $toast): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    
        <div class="toast show mt-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="<?php echo e(asset('img/bb.jpg')); ?>" class="rounded me-2" alt="Icono" width="20">
                <strong class="me-auto"><?php echo e($toast['Title'] ?? 'Notificación'); ?></strong>
                <small>Hace un momento</small>
                <button type="button" class="btn-close" wire:click="removeToast(<?php echo e($index); ?>)" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <?php echo e($toast["Message"]); ?>

            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('closeToast', event => {
                setTimeout(() => {
                    window.Livewire.find('<?php echo e($_instance->getId()); ?>').removeToast(0);
                }, event.detail);
            });
        });
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/alerts.blade.php ENDPATH**/ ?>