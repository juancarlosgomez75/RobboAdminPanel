
<?php $__env->startSection('title','Dashboard'); ?>

<?php $__env->startSection("contenido"); ?>

<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 pt-1">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("admin.dashboard");

$__html = app('livewire')->mount($__name, $__params, 'lw-1171318859-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>