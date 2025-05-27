<div>
    <style>
        .container {
            width: 100%;
        }
    
        .progressbar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10vw; /* Espacio entre los elementos */
            padding: 20px 0; /* Espaciado superior e inferior */
            position: relative;
        }
    
        /* Estilo de los elementos de la barra de progreso */
        .progressbar li {
            list-style: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.5em;
            position: relative;
        }
    
        /* Círculo alrededor del ícono */
        .icon-circle {
            width: 50px;
            height: 50px;
            background-color: #007bff; /* Color de fondo */
            border-radius: 50%; /* Hace el div completamente circular */
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1; /* Asegura que esté encima de la línea */
        }
    
        /* Ícono dentro del círculo */
        .progressbar i {
            font-size: clamp(16px, 1.7vw, 18px);
            color: white; /* Color del ícono */
        }
    
        /* Línea de conexión entre los círculos */
        .progressbar li::after {
            content: "";
            position: absolute;
            top: 25px;
            width: 15.2vw; /* Ajusta el tamaño de la línea */
            height: 5px; /* Grosor de la línea */
            background-color: #c0c0c0; /* Color de la línea */
            left: 50%;
            z-index: 0; /* Detrás de los círculos */
        }
    
        /* Elimina la línea del último elemento */
        .progressbar li:last-child::after {
            content: none;
        }
    
        /* Estilos para los pasos completados */
        .progressbar li.current ~ li .icon-circle {
            background-color: #c0c0c0; /* Pasos después del actual en gris */
        }
    
        .progressbar li.current .icon-circle {
            background-color: #D2665A; /* Paso actual en amarillo */
        }

        .progressbar li.current span {
            color: #D2665A !important; /* Paso actual en amarillo */
        }

        .progressbar li:not(.current) ~ li span {
            color: #d8a6a1; /* Restaura el color original en los pasos después del actual */
        }

        .progressbar li:not(.current) .icon-circle {
            background-color: #d8a6a1;
        }

        .progressbar li:not(.current)::after {
            background-color: #d8a6a1; /* Línea verde hasta el paso actual */
        }


        .progressbar li:not(.current) span {
            color: #d8a6a1; /* Cambia el color solo en los pasos antes del actual */
        }
    
        /* Línea de conexión verde hasta el paso actual */
        .progressbar li.current ~ li::after {
            background-color: #c0c0c0; /* Mantiene la línea gris después del paso actual */
        }

        .progressbar li.current ~ li span {
            color: #c0c0c0; /* Asegura que los pasos después del actual no se vean afectados */
        }


        /* Si la clase 'cancelled' está presente, todo se vuelve rojo */
        .progressbar.cancelled li::after {
            background-color: #c0c0c0 !important; /* Línea roja */
        }

        .progressbar.cancelled li .icon-circle {
            background-color: #c0c0c0 !important; /* Círculos rojos */
        }

        .progressbar.cancelled i {
            color: white !important; /* Íconos blancos para contraste */
        }

        .progressbar.cancelled li span {
            color: #c0c0c0 !important; /* Texto rojo para coherencia */
        }

        /* Si la clase 'cancelled' está presente, todo se vuelve rojo */
        .progressbar.completed li::after {
            background-color: #d8a6a1 !important; /* Línea roja */
        }

        .progressbar.completed li .icon-circle {
            background-color: #d8a6a1 !important; /* Círculos rojos */
        }

        .progressbar.completed i {
            color: white !important; /* Íconos blancos para contraste */
        }

        .progressbar.completed li span {
            color: #d8a6a1 !important; /* Texto rojo para coherencia */
        }
    </style>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Información de el pedido</h4>
                    <p class="card-text">Esta es la información que está almacenada para este producto.</p>
                </div>
                <div class="col-md-12 pt-4">
                    <ul class="progressbar <?php if($pedido->status=="canceled"): ?> cancelled <?php endif; ?>">
                        <li>
                            <div class="icon-circle">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span>Creado</span>
                        </li>
                        <li <?php if($pedido->status=="created" || $pedido->status=="partial delivery"): ?> class="current" <?php endif; ?>>
                            <div class="icon-circle">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span>
                                <!--[if BLOCK]><![endif]--><?php if($pedido->status=="created"): ?>
                                Esperando entrega
                                <?php elseif($pedido->status=="partial delivery"): ?>
                                Entrega parcial
                                <?php else: ?>
                                Preparado
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                        </li>
                        <li <?php if($pedido->status=="delivered"): ?> class="current" <?php endif; ?>>
                            <div class="icon-circle">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span>
                                <!--[if BLOCK]><![endif]--><?php if($pedido->status=="delivered"): ?>
                                Completado
                                <?php else: ?>
                                Pendiente
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                        </li>
                    </ul>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($pedido->status=="canceled"): ?>
                <div class="col-md-12 pb-1 text-center">
                    <h5 style="color:red">Pedido cancelado</h5>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de creación</h5>
                    <p class="card-text">Esta es la información relacionada con la creación del pedido.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre de empresa</th>
                                <td><?php echo e($pedido->enterprise); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Ciudad</th>
                                <td><?php echo e(($pedido->city!="")?$pedido->city:"No registra"); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Dirección</th>
                                <td><?php echo e(($pedido->address!="")?$pedido->address:"No registra"); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Encargado</th>
                                <td><?php echo e(($pedido->name!="")?$pedido->name:"No registra"); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Teléfono</th>
                                <td><?php echo e(($pedido->phone!="")?$pedido->phone:"No registra"); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha de creación</th>
                                <td><?php echo e($pedido->created_at); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha tentativa de entrega</th>
                                <td><?php echo e($pedido->tentative_delivery_date); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Creador</th>
                                <td><?php echo e($pedido->creator_info->name." (".$pedido->creator_info->id." - ".$pedido->creator_info->username.")"); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Comentarios de creación</th>
                                <td><?php echo e($pedido->creation_notes); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($pedido->status=="canceled"): ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de cancelación</h5>
                    <p class="card-text">Esta es la información relacionada con la cancelación de el pedido</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de cancelación</th>
                                <td><?php echo e($pedido->cancel_date??"No reporta"); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién canceló el pedido?</th>
                                <td><?php echo e((!is_null($pedido->canceled_by))? $pedido->canceler_info->name." (".$pedido->canceler_info->id." - ".$pedido->canceler_info->username.")":"No reporta"); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Razón de cancelación</th>
                                <td><?php echo e($pedido->cancellation_reason??"No reporta"); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if(!is_null($deliveryList)): ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de entregas</h5>
                    <p class="card-text">Este es el listado de comentarios de las entregas reportadas.</p>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $deliveryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fecha=>$contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p>
                        <b><?php echo e($fecha); ?></b> - 
                        <!--[if BLOCK]><![endif]--><?php if($contenido["inventoried"]): ?>
                        <span class="text-success"><?php echo e("Reportado en inventario el: ".($contenido["inventoried_date"]??"N/F")." por usuario con id #".$contenido["inventoried_by"]??"N/R"); ?></span>
                        <?php else: ?> 
                        <span class="text-primary">Sin reportar en inventario</span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <br>
                        <?php echo e((is_null($contenido["details"]) || $contenido["details"]=="")?"Sin comentarios":$contenido["details"]); ?>

                    </p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de productos</h5>
                    <p class="card-text">Estos son los productos que deben ser entrgados</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">¿Registrado?</th>
                                <!--[if BLOCK]><![endif]--><?php if($entregaActive): ?>
                                <th scope="col">Reporte de entrega</th>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if(!is_null($deliveryList)): ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $deliveryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fecha=>$contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th scope="col">
                                    <?php echo e(\Carbon\Carbon::parse($fecha)->toDateString()); ?>

                                    <!--[if BLOCK]><![endif]--><?php if($contenido["inventoried"]): ?>
                                    <i class="fa-solid fa-check ms-2 text-success"></i>
                                    <?php else: ?>
                                    <i class="fa-solid fa-hourglass-start ms-2 text-primary"></i>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <th scope="col">Pendiente</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = json_decode($pedido->creation_list); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <?php echo e($index+1); ?>

                                    </td>
                                    <td>
                                        <?php echo e($pd->name); ?>

                                    </td>
                                    <td>
                                        <?php echo e($pd->amount); ?>

                                    </td>
                                    <td>
                                        <!--[if BLOCK]><![endif]--><?php if($pd->internal): ?>
                                        <a href="<?php echo e(route("inventario.viewedit",$pd->id)); ?>">#<?php echo e($pd->id); ?></a>
                                        <?php else: ?> 
                                        No
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </td>
                                    <!--[if BLOCK]><![endif]--><?php if($entregaActive): ?>
                                    <td>
                                        <input class="form-control form-control-sm" type="number" min="0" wire:model="entregando.<?php echo e($index); ?>">
                                    </td>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <!--[if BLOCK]><![endif]--><?php if(!is_null($deliveryList)): ?>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $deliveryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fecha=>$contenido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td scope="col"><?php echo e($contenido["products"][$index]); ?></td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <td>
                                        <?php echo e($pendientes[$index]); ?>

                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>

                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="d-flex gap-3 justify-content-center w-100" style="max-width: 36rem;">
                        <!--[if BLOCK]><![endif]--><?php if($pedido->status!="delivered" && $pedido->status!="canceled"): ?>
                            <!--[if BLOCK]><![endif]--><?php if(!$entregaActive): ?>
                            <button type="button" class="btn btn-outline-secondary" wire:click="iniciarEntrega()">
                                Reportar entrega
                            </button>
                            <?php else: ?>
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Completar entrega
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelarPedido">
                                Cancelar pedido
                            </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
                        <!--[if BLOCK]><![endif]--><?php if($pendienteInventariar): ?>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reportarInventario">
                                Reportar inventario
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de reporte de entrega</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar, aceptas que toda la información aquí descrita está bien. Por seguridad, la información no podrá ser editada manualmente.
                </p>
                <div class="mb-3">
                    <label class="form-label">Observaciones de entrega</label>
                    <textarea class="form-control" rows="3" wire:model="observaciones"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="completarEntrega()" >Crear pedido</button>
            </div>
        </div>
        </div>
    </div>
    <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
    <!-- Modal -->
    <div class="modal fade" id="cancelarPedido" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cancelarPedidoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cancelarPedidoLabel">Cancelar el pedido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al reportar la cancelación de el pedido, esta se cerrará inmediatamente y no permitirá ingresar el resto de valores. Por favor reporta la razón de por qué se canceló el pedido.
                </p>
                <div class="mb-3">
                    <label class="form-label">Motivo de cancelación</label>
                    <textarea class="form-control" rows="3" wire:model="reasonCancel"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="cancelarPedido()">Cancelar orden</button>
            </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
    <!-- Modal -->
    <div class="modal fade" id="reportarInventario" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reportarInventarioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="reportarInventarioLabel">Reportar inventario</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al reportar inventario, enviarás todas las entregas disponibles (y que aún no han sido reportadas) al inventario del sistema. Los productos que fueron marcados como <b>No registrados</b> no serán inventariados.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="reportarInventario()">Reportar inventario</button>
            </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/inventario/request-view.blade.php ENDPATH**/ ?>