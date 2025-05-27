<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Categoría</th>
                <th scope="col">Estado</th>
                <th scope="col">Disponibles</th>
                <th scope="col" style="width: 6%"></th>
                <th scope="col" style="width: 15%"></th>
            </tr>
        </thead>
        <tbody class="align-center">
            <?php if(!$productos->isEmpty()): ?>
            <?php $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <th scope="row"><?php echo e($producto->id); ?></th>
                <td><?php echo e($producto->name); ?></td>
                <td>
                    <?php echo e($producto->category_info->name??"Sin categoría"); ?>

                </td>
                <td>
                    <?php if($producto->available): ?>
                    <span style="color:green">Activo</span>
                    <?php else: ?>
                    <span style="color:red">Oculto</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($producto->inventory->stock_available); ?></td>
                <td>
                    <?php if($producto->inventory->stock_available>=$producto->inventory->stock_min): ?>
                    <i class="fa-solid fa-circle-check" style="color:green"></i>
                    <?php elseif($producto->inventory->stock_available>0): ?>
                    <i class="fa-solid fa-circle-exclamation" style="color:orange"></i>
                    <?php else: ?>
                    <i class="fa-solid fa-circle-xmark" style="color:red"></i>
                    <?php endif; ?>
                </td>
                <td>
                    <a type="button" class="btn btn-outline-secondary btn-sm" href="<?php echo e(route("inventario.viewedit",$producto->id)); ?>">Ver detalles</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php else: ?>
            <tr class="text-center">
                <td colspan="7">Sin productos registrados aún</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <center><a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createProduct">Crear nuevo producto</a></center>

    <!-- Modal -->
    <div class="modal fade" id="createProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createProductLabel">Creación de nuevo producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Por favor ingresa la siguiente información para registrar un nuevo producto en el sistema. Los productos inician con el inventario en cero, es por ello que, si deseas añadir stock, podrás hacerlo en la página de cada producto.
                </p>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="name">
                    </div>
                    <div class="col-md-12 mb-3">
                        <labelclass="form-label">Descripción</label>
                        <textarea class="form-control" rows="3" placeholder="Aquí describe de forma corta qué es este producto, esta información es de uso personal para referenciación u otros." wire:model="description"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" wire:model="category">
                            <option selected disabled value="-1">Seleccionar una categoría</option>
                            <option value="0">Ninguna</option>
                            <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                        </select>
                    </div>                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" placeholder="Ejemplo: 001RG" wire:model="ref">
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardar()">Crear producto</button>
            </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\inventario\productos.blade.php ENDPATH**/ ?>