<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de órdenes</h5>
                    
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
                    <a href="<?php echo e(route("ordenes.create")); ?>" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 <?php if(!$filtrosActivos): ?> hide <?php endif; ?>" id="Filtros">

                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por fecha" wire:model.change="filtroFecha">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por destinatario" wire:model.change="filtroNombre">
                        </div>
                        <div class="col-md-2">
                            <select class="custom-input" wire:model.change="filtroTipo">
                                <option value="0" selected>Todas</option>
                                <option value="-1">Envío</option>
                                <option value="1">Recogida</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="custom-input" wire:model.change="filtroEstado">
                                <option value="all">Todas</option>
                                <option value="availables">Disponibles</option>
                                <option value="created">Creado</option>
                                <option value="prepared">Preparado</option>
                                <option value="waiting">Esperando recogida</option>
                                <option value="sended">Enviado</option>
                                <option value="collected">Recibido</option>
                                <option value="canceled">Cancelado</option>
                                <option value="finished">Cerradas</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 7%;">#</th>
                                <th scope="col">Fecha creación</th>
                                <th scope="col">Ciudad</th>
                                <th scope="col">Destinatario</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estado</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            <!--[if BLOCK]><![endif]--><?php if(!$pedidos->isEmpty()): ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $pedidos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th scope="row"><?php echo e($pedido->id); ?></th>
                                    <td><?php echo e($pedido->created_at); ?></td>
                                    <td><?php echo e($pedido->city); ?></td>
                                    <td><?php echo e($pedido->name); ?></td>
                                    <td><?php echo e(($pedido->type=="shipping") ? "Envío":"Recogida"); ?></td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if(!$pedido->finished): ?>
                                            <!--[if BLOCK]><![endif]--><?php if($pedido->status=="created"): ?>
                                            <span style="color:#e47d08">Creado</span>
                                            <?php elseif($pedido->status=="prepared"): ?>
                                            <span style="color:#004a8f">Preparado</span>
                                            <?php elseif($pedido->status=="waiting"): ?>
                                            <span style="color:#004a8f">Esperando recogida</span>
                                            <?php elseif($pedido->status=="sended"): ?>
                                            <span style="color:#0ea800">Enviado</span>
                                            <?php elseif($pedido->status=="collected"): ?>
                                            <span style="color:#0ea800">Recibido</span>
                                            <?php elseif($pedido->status=="canceled"): ?>
                                            <span style="color:#8f0000">Cancelado</span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php else: ?>
                                            <span style="color:#9413cf">Cerrada</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-secondary btn-sm" href="<?php echo e(route("orden.ver",$pedido->id)); ?>">Ver detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <tr>
                                <td class="text-center" colspan="7">No se han encontrado órdenes</td>
                            </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            
                        </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12">
                    <?php echo e($pedidos->links()); ?>

                </div>
            </div>
        </div>
    </div>
    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/inventario/order-list.blade.php ENDPATH**/ ?>