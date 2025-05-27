
<?php $__env->startSection('title','Productos'); ?>
<?php $__env->startSection("contenido"); ?>
<?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('alerts');

$__html = app('livewire')->mount($__name, $__params, 'lw-2411348606-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Categorías</h5>
                <p class="card-text">Las categorías sirven para agrupar ciertos productos, esto con el fin de organizarlos y facilitar su búsqueda.</p>
            </div>
            <div class="col-md-12 pt-3">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("inventario.categorias");

$__html = app('livewire')->mount($__name, $__params, 'lw-2411348606-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
            <div class="col-md-12">
                <h5 class="card-title">Productos</h5>
                <p class="card-text">Los productos son todos aquellos elementos que dispones en tu inventario y con los cuales comercias.</p>
            </div>
            <div class="col-md-12 pt-3">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("inventario.productos");

$__html = app('livewire')->mount($__name, $__params, 'lw-2411348606-2', $__slots ?? [], get_defined_vars());

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

<?php echo $__env->make('paneltemplate', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/inventario/index.blade.php ENDPATH**/ ?>