
<?php $__env->startSection('title','Crear pedido'); ?>
<?php $__env->startSection("contenido"); ?>

<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("inventario.request");

$__html = app('livewire')->mount($__name, $__params, 'lw-1470739273-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/inventario/request.blade.php ENDPATH**/ ?>