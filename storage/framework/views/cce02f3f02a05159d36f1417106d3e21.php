
<?php $__env->startSection('title','Reporte de estudio'); ?>

</style>
<?php $__env->startSection("contenido"); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("estudios.reporte",compact("information"));

$__html = app('livewire')->mount($__name, $__params, 'lw-2594826017-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/estudios/reporte.blade.php ENDPATH**/ ?>