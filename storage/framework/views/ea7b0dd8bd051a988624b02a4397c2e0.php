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
                    <h4 class="card-title">Información de la orden</h4>
                    <p class="card-text">Esta es la información que está almacenada para este producto.</p>
                </div>
                <div class="col-md-12 pt-4">
                    <ul class="progressbar <?php if($orden->status=="canceled"): ?> cancelled <?php endif; ?>">
                        <li>
                            <div class="icon-circle">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span>Creada</span>
                        </li>
                        <li <?php if($orden->status=="created"): ?> class="current" <?php endif; ?>>
                            <div class="icon-circle">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span>
                                <?php if($orden->status=="created"): ?>
                                Preparando
                                <?php else: ?>
                                Preparado
                                <?php endif; ?>
                            </span>
                        </li>
                        <li <?php if($orden->status=="prepared"): ?> class="current" <?php endif; ?>>
                            <div class="icon-circle">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <span>
                                <?php if($orden->status=="prepared"): ?>
                                Esperando guía
                                <?php elseif($orden->status!="created"): ?>
                                Guía generada
                                <?php else: ?>
                                Generación de guía
                                <?php endif; ?>
                            </span>
                        </li>
                        <li <?php if($orden->status=="waiting"): ?> class="current" <?php endif; ?>>
                            <div class="icon-circle">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <span>
                                <?php if($orden->status=="waiting"): ?>
                                Enviando
                                <?php elseif($orden->status=="sended"): ?>
                                Enviado
                                <?php else: ?>
                                Envío
                                <?php endif; ?>
                            </span>
                        </li>
                    </ul>
                </div>
                <?php if($orden->status=="canceled"): ?>
                <div class="col-md-12 pb-1 text-center">
                    <h5 style="color:red">Orden cancelada</h5>
                </div>
                <?php endif; ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de creación</h5>
                    <p class="card-text">Esta es la información relacionada con la creación de la orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre de destinatario</th>
                                <td><?php echo e($orden->name); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Teléfono</th>
                                <td><?php echo e($orden->phone); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Ciudad</th>
                                <td><?php echo e($orden->city); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Dirección</th>
                                <td><?php echo e($orden->address); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">¿Asociado a estudio?</th>
                                <td>
                                    <?php if($orden->study_id): ?>
                                    <a href="<?php echo e(route("estudio.ver",$orden->study_id)); ?>">Ver estudio</a>
                                    <?php else: ?>
                                    No
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha de creación</th>
                                <td><?php echo e($orden->created_at); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Creador</th>
                                <td><?php echo e($orden->creator_info->name." (".$orden->creator_info->id." - ".$orden->creator_info->username.")"); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Comentarios</th>
                                <td><?php echo e($orden->creation_notes); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <p class="card-text">Esta estos son los productos que se deben enviar</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = json_decode($orden->creation_list); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index+1); ?></td>
                                    <td><?php echo e($pd->name); ?></td>
                                    <td><?php echo e($pd->amount); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($orden->status=="created"): ?>
                    <?php if(!$preparando): ?>
                    <div class="col-md-12 pt-2">
                        <div class="text-center">
                            <a class="btn btn-outline-primary" wire:click="iniciarAlistamiento()">Reportar alistamiento de orden</a>
                        </div>   
                    </div>
                    <?php else: ?>
                    <div class="col-md-12 pt-2">
                        <h5 class="card-title">Información de alistamiento</h5>
                        <p class="card-text">Por favor completa toda la información para reportar el alistamiento</p>
                    </div>
                    <div class="col-md-12 pt-2">
                        <table class="table text-center" >
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Firmware</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($preparacion_list)): ?>
                                <?php $__currentLoopData = $preparacion_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>a</td>
                                    <td><?php echo e($prod["name"]); ?></td>
                                    <td><?php echo e($prod["amount"]); ?></td>
                                    <td><?php echo e($prod["firmware"]??"No"); ?></td>
                                    <td>
                                        <a class="btn btn-outline-danger btn-sm" wire:click="removeProduct(<?php echo e($index); ?>)">Eliminar</a>
                                    </td>
                                </tr>
                                
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5">
                                        Aún no has alistado productos
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 pt-2">
                        <div class="text-center">
                            <a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addProduct">Añadir producto al listado</a>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="addProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h1 class="modal-title fs-5" id="addProductLabel">Añadir producto al alistamiento</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Por favor completa los campos para registrar el alistamiento:</p>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label class="form-label">Producto para alistar</label>
                                            <select class="form-select" wire:model="preparacion_product">
                                                <option selected disabled value="0">Seleccionar un producto</option>
                                                <?php $__currentLoopData = json_decode($orden->creation_list); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($pd->id); ?>"><?php echo e($pd->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Cantidad</label>
                                            <input class="form-control" type="number" min="1" wire:model="preparacion_amount">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Firmware Id</label>
                                            <input class="form-control" type="text" wire:model="preparacion_firmware">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="preparacionAdd()">Añadir</button>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pt-2">
                        <label class="form-label">Observaciones de alistamiento</label>
                        <textarea class="form-control" rows="3" wire:model="details"></textarea>
                    </div>
                    <div class="col-md-12 pt-4">
                        <div class="text-center">
                            <a class="btn btn-outline-primary" wire:click="completarAlistamiento()">Guardar alistamiento</a>
                        </div>   
                    </div>


                    <?php endif; ?>
                <?php elseif($orden->status!="canceled"): ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de alistamiento</h5>
                    <p class="card-text">Esta es la información relacionada con la creación de la orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de alistamiento</th>
                                <td><?php echo e($orden->preparation_date); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién alistó?</th>
                                <td><?php echo e($orden->preparator_info->name." (".$orden->preparator_info->id." - ".$orden->preparator_info->username.")"); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Comentarios</th>
                                <td><?php echo e($orden->preparation_notes); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <p class="card-text">Esta estos son los productos que fueron enviados</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Firmware</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = json_decode($orden->preparation_list); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$pd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index+1); ?></td>
                                    <td><?php echo e($pd->name); ?></td>
                                    <td><?php echo e($pd->amount); ?></td>
                                    <td><?php echo e($pd->firmware??"No"); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <?php if($orden->status=="prepared"): ?>
                    <?php if(!$enviando): ?>
                    <div class="col-md-12 pt-2">
                        <div class="text-center">
                            <a class="btn btn-outline-primary" wire:click="iniciarEnvio()">Reportar guía de envío</a>
                        </div>   
                    </div>
                    <?php else: ?>
                    <div class="col-md-12 pt-2">
                        <h5 class="card-title">Información de guía de envío</h5>
                        <p class="card-text">Por favor completa toda la información para reportar la recogida</p>
                    </div>
                    <div class="col-md-12 pt-2">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Empresa</label>
                                <select class="form-select" wire:model="courier_enterprise">
                                    <option selected disabled value="0">Seleccionar un producto</option>
                                    <?php $__currentLoopData = $mensajerias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mensajeria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($mensajeria->id); ?>"><?php echo e($mensajeria->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Guía de seguimiento</label>
                                <input class="form-control" type="text" wire:model="tracking_code">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pt-3">
                        <div class="text-center">
                            <a class="btn btn-primary" wire:click="reportarGuia()">Guardar información</a>
                        </div>   
                    </div>
                    <?php endif; ?>
                <?php elseif($orden->status!="prepared" && $orden->status!="created" && $orden->status!="canceled"): ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de reporte de guía</h5>
                    <p class="card-text">Esta es la información relacionada con la generación de la guía de seguimiento.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de generación</th>
                                <td><?php echo e($orden->enlist_date); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién reportó la guía?</th>
                                <td><?php echo e($orden->enlister_info->name." (".$orden->enlister_info->id." - ".$orden->enlister_info->username.")"); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Empresa</th>
                                <td><?php echo e($orden->courier_info->name); ?></td>
                            </tr>


                            <tr>
                                <th scope="row">Guía de seguimiento</th>
                                <td><a href="#"><?php echo e($orden->tracking); ?></a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <?php if($orden->status=="waiting"): ?>
                <div class="col-md-12 pt-2">
                    <div class="text-center">
                        <a class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reportRecogida">Reportar recogida del paquete</a>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="reportRecogida" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="reportRecogidaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="reportRecogidaLabel">Reportar recogida del paquete</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Al reportar la recogida, el paquete se reporta como completado y la orden se cierra.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="completarEnvio()">Completar envío</button>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                <?php elseif($orden->status!="prepared" && $orden->status!="created" && $orden->status!="waiting" && $orden->status!="canceled"): ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de envío</h5>
                    <p class="card-text">Esta es la información relacionada con el envío del paquete</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de generación</th>
                                <td><?php echo e($orden->send_date); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién reportó el envío?</th>
                                <td><?php echo e($orden->sender_info->name." (".$orden->sender_info->id." - ".$orden->sender_info->username.")"); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <?php if(($orden->status=="prepared" || $orden->status=="created" || $orden->status!="waiting")&&$orden->status!="canceled"): ?>
                <div class="col-md-12 pt-3">
                    <div class="text-center">
                        <a class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelarOrden">Reportar cancelación de la orden</a>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="cancelarOrden" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cancelarOrdenLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="cancelarOrdenLabel">Cancelar la orden</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Al reportar la cancelación de la orden, esta se cerrará inmediatamente y no permitirá ingresar el resto de valores. Por favor reporta la razón de por qué se canceló la orden.
                                </p>
                                <div class="mb-3">
                                    <label class="form-label">Motivo de cancelación</label>
                                    <textarea class="form-control" rows="3" wire:model="reasonCancel"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" wire:click="cancelarOrden()">Cancelar orden</button>
                            </div>
                            </div>
                        </div>
                        </div>
                </div>
                <?php elseif($orden->status=="canceled"): ?>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de cancelación</h5>
                    <p class="card-text">Esta es la información relacionada con la cancelación de la orden</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de cancelación</th>
                                <td><?php echo e($orden->cancel_date); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién canceló la orden?</th>
                                <td><?php echo e($orden->canceler_info->name." (".$orden->canceler_info->id." - ".$orden->canceler_info->username.")"); ?></td>
                            </tr>

                            <tr>
                                <th scope="row">Razón de cancelación</th>
                                <td><?php echo e($orden->cancellation_reason); ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <br>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\inventario\order-view.blade.php ENDPATH**/ ?>