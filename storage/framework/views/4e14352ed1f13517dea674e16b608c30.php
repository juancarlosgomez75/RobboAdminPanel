
<?php $__env->startSection('title','Visualización de manager'); ?>

</style>
<?php $__env->startSection("contenido"); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("estudios.manager-viewedit",compact("Information","Models","Study"));

$__html = app('livewire')->mount($__name, $__params, 'lw-3334289496-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\estudios\manager-viewedit.blade.php ENDPATH**/ ?>