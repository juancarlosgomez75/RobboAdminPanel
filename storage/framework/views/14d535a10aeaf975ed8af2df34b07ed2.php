
<?php $__env->startSection('title','Listado de estudios'); ?>

<style>
.custom-input {
    display: flex;
    align-items: center;
    padding: 4px 10px; /* Reduce la altura */
    border: 1px solid #ddd; /* Borde gris claro */
    border-radius: 6px;
    width: 100%;
    font-size: 14px;
    margin-bottom:0.5rem;
    height: 30px; /* Controla la altura */
}

.custom-input:focus {
    outline: none;
    box-shadow: none; /* Elimina borde azul en focus */
    border-color: #ccc; /* Borde ligeramente más oscuro al enfocar */
}

.hide{
    display: none;
}

</style>
<?php $__env->startSection("contenido"); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("estudios.listado", ['datos' => $information]);

$__html = app('livewire')->mount($__name, $__params, 'lw-897587588-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/estudios/index.blade.php ENDPATH**/ ?>