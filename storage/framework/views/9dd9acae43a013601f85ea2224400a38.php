<div>
    <?php if($stock>=$stockmin && $activo): ?>
    <div class="alert alert-success" role="alert">
        El stock disponible de este producto se encuentra por encima del stock recomendable
    </div>
    <?php elseif($stock>0 && $activo): ?>
    <div class="alert alert-warning" role="alert">
        El stock disponible de este producto está por debajo del stock recomendable
    </div>
    <?php elseif($stock==0 && $activo): ?>
    <div class="alert alert-danger" role="alert">
        Este producto no tiene stock disponible
    </div>
    <?php endif; ?>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Información básica</h5>
                    <p class="card-text">Esta es la información que está almacenada para este producto.</p>
                </div>
                <div class="col-md-12 pt-3">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="name" required <?php if(!$editing): ?> disabled <?php endif; ?>>
                        </div>
                        <div class="col-md-12 mb-3">
                            <labelclass="form-label">Descripción</label>
                            <textarea class="form-control" rows="3" placeholder="Aquí describe de forma corta qué es este producto, esta información es de uso personal para referenciación u otros." wire:model="description" required <?php if(!$editing): ?> disabled <?php endif; ?>></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" wire:model="category" required <?php if(!$editing): ?> disabled <?php endif; ?>>
                                <option selected disabled value="-1">Seleccionar una categoría</option>
                                <option value="0">Ninguna</option>
                                <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                            </select>
                        </div>                    
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Referencia</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: 001RG" wire:model="ref" required <?php if(!$editing): ?> disabled <?php endif; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock disponible</label>
                            <input type="number" class="form-control" wire:model="stock" disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock mínimo (Para alerta)</label>
                            <input type="number" class="form-control" wire:model="stockmin" min="0" step="1" required <?php if(!$editing): ?> disabled <?php endif; ?>>
                        </div>
                        <div class="col-md-12 text-center">
                            <?php if(!$editing): ?>
                            <button type="button" class="btn btn-outline-primary" wire:click="activarEdicion">
                                Editar información básica
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Guardar cambios
                            </button>
                            <?php endif; ?>

                            <?php if($activo): ?>
                            <button type="button" class="btn btn-outline-danger ms-2" wire:click="desactivar()">
                                Desactivar producto
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-outline-success ms-2" wire:click="activar()">
                                Activar producto
                            </button>
                            <?php endif; ?>

                        </div>
                    </div> 
                </div>
                <div class="col-md-8 pt-3">
                    <h5 class="card-title">Historial de movimientos</h5>
                    <p class="card-text">Estos son los últimos movimientos que se han efectuado para este producto.</p>
                </div>
                <div class="col-md-4 pt-4 text-end">
                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo e(route("inventario.movimiento",$inventario->id)); ?>">
                        Crear un movimiento
                    </a>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">Fecha</th>
                                <th scope="col">Razón</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Stock antes</th>
                                <th scope="col">Stock despues</th>
                                <th scope="col">Autor</th>
                                <th scope="col"># Orden</th>
                            </tr>
                        </thead>
                        <tbody class="align-center">
                            <?php if(!$Movimientos->isempty()): ?>
                            <?php $__currentLoopData = $Movimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td scope="row"><?php echo e($movimiento->created_at); ?></td>
                                <td scope="row"><?php echo e($movimiento->reason); ?></td>
                                <td scope="row">
                                    <?php if($movimiento->type=="expense"): ?>
                                    <span style="color:#be0000">-<?php echo e($movimiento->amount); ?></span>
                                    <?php else: ?>
                                    <span style="color:#33a100"><?php echo e($movimiento->amount); ?></span>
                                    <?php endif; ?>
                                    
                                </td>
                                <td scope="row"><?php echo e($movimiento->stock_before); ?></td>
                                <td scope="row"><?php echo e($movimiento->stock_after); ?></td>
                                <td scope="row"><?php echo e($movimiento->author_info->username); ?></td>
                                <td>
                                    <?php if($movimiento->order_id): ?>
                                    <a href="<?php echo e(route("orden.ver",$movimiento->order_id)); ?>"><?php echo e($movimiento->order_id); ?></a>
                                    <?php else: ?>
                                    No
                                    <?php endif; ?>
                                </td>
                            </tr>
                            
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7">No hay movimientos registrados aún</td>
                            </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <?php echo e($Movimientos->links()); ?>

                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de modificación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Al presionar en confirmar edición, confirma que la información aquí contenida es correcta.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardarEdicion" >Confirmar edición</button>
                </div>
            </div>
            </div>
        </div>


    </div>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\inventario\viewedit.blade.php ENDPATH**/ ?>