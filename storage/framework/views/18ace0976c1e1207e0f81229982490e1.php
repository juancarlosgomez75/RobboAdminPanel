<div>
    
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Creación de órdenes</h4>
                    <p class="card-text">Para poder crear una orden por favor completa la información de cada una de las secciones.</p>
                </div>
                <div class="col-md-12 pt-4">
                    <h5 class="card-title">Información de destinatario</h5>
                    <p class="card-text">Esta es la información respecto de hacia donde irá dirigido el paquete.</p>
                </div>
                <div class="col-md-12 pt-3">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">¿Es un estudio?</label>
                                    <select class="form-control" wire:model.change="toStudy">
                                        <option value="-1" disabled selected>Seleccione</option>
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                </div>

                                <?php if($toStudy=="1"): ?>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ingrese nombre a buscar</label>
                                    <input type="text" class="form-control" placeholder="Ejemplo: Coolcam" wire:model="study_search" required >
                                </div>
                                <div class="col-md-2 mb-3 pt-2 d-grid">
                                    <br>
                                    <button type="button" class="btn btn-outline-primary" wire:click="buscarEstudio()">Buscar</button>
                                </div>
                                <?php if($studyFind=="1"): ?>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estudio seleccionado</label>
                                    <input type="text" class="form-control" wire:model="study_name" disabled>
                                </div>
                                <?php endif; ?>

                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if($activeBasic): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Calle 98 #69-12" wire:model="address" <?php if($studyFind=="1"): ?> disabled <?php endif; ?>>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Medellín, Colombia" wire:model="city" <?php if($studyFind=="1"): ?> disabled <?php endif; ?> >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de quién recibe</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Juan Carlos" wire:model="receiver" <?php if($studyFind=="1"): ?> disabled <?php endif; ?> >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono de contacto</label>
                            <input type="tel" class="form-control" placeholder="Ejemplo: +573002000000" wire:model="phone" <?php if($studyFind=="1"): ?> disabled <?php endif; ?> >
                        </div>
                        <?php endif; ?>
                    </div> 
                </div>
                <div class="col-md-12 pt-3">
                    <h5 class="card-title">Listado de productos</h5>
                    <p class="card-text">Este es el listado de elementos que se deberán enviar en esta orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($listProducts)): ?>
                            <?php $__currentLoopData = $listProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index+1); ?></td>
                                    <td><?php echo e($pd["name"]); ?></td>
                                    <td><?php echo e($pd["amount"]); ?></td>
                                    <td>
                                        <a class="btn btn-outline-danger btn-sm" wire:click="removeProduct(<?php echo e($index); ?>)">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Aún no se han añadido productos</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <?php if(!$addingProduct): ?>
                    <div class="text-center">
                        <a class="btn btn-outline-secondary btn-sm" wire:click="startAdding()">Añadir item</a>
                    </div>    
                    <?php else: ?>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label">Nombre producto</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Neutro" wire:model="product_name" >
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Cantidad</label>
                            <input type="number" min="0" class="form-control" placeholder="Ejemplo: 2" wire:model="product_amount" >
                        </div>
                        <div class="col-md-1 mb-3 pt-2 d-grid text-center">
                            <br>
                            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addProduct()">Añadir</button>
                        </div>
                        <div class="col-md-1 mb-3 pt-2 d-grid text-center">
                            <br>
                            <button type="button" class="btn btn-outline-danger btn-sm" wire:click="cancelAdding()">Cancelar</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-12 pt-3">
                    <h5 class="card-title">Observaciones</h5>
                    <p class="card-text">Aquí podrás añadir las observaciones que consideres importantes para esta orden.</p>
                    
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" wire:model="details"></textarea>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="text-center">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Registrar pedido en sistema</a>
                    </div>   
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de creación de pedido</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Al presionar en confirmar, aceptas que toda la información aquí descrita está bien. Por seguridad, la información no podrá ser editada manualmente.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="crear()" >Crear pedido</button>
                </div>
            </div>
            </div>
        </div>


    </div>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\inventario\order.blade.php ENDPATH**/ ?>