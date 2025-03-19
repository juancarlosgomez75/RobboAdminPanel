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
                    <ul class="progressbar @if($orden->status=="canceled") cancelled @endif">
                        <li>
                            <div class="icon-circle">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span>Creada</span>
                        </li>
                        <li @if($orden->status=="created") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span>
                                @if($orden->status=="created")
                                Preparando
                                @else
                                Preparado
                                @endif
                            </span>
                        </li>
                        <li @if($orden->status=="prepared") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <span>
                                @if($orden->status=="prepared")
                                Esperando guía
                                @elseif($orden->status!="created")
                                Guía generada
                                @else
                                Generación de guía
                                @endif
                            </span>
                        </li>
                        <li @if($orden->status=="waiting") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <span>
                                @if($orden->status=="waiting")
                                Enviando
                                @elseif($orden->status=="sended")
                                Enviado
                                @else
                                Envío
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de creación</h5>
                    <p class="card-text">Esta es la información relacionada con la creación de la orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre de destinatario</th>
                                <td>{{$orden->name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Teléfono</th>
                                <td>{{$orden->phone}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Ciudad</th>
                                <td>{{$orden->city}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Dirección</th>
                                <td>{{$orden->address}}</td>
                            </tr>

                            <tr>
                                <th scope="row">¿Asociado a estudio?</th>
                                <td>
                                    @if($orden->study_id)
                                    <a href="{{route("estudio.ver",$orden->study_id)}}">Ver estudio</a>
                                    @else
                                    No
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha de creación</th>
                                <td>{{$orden->created_at}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Creador</th>
                                <td>{{$orden->creator_info->name." (".$orden->creator_info->id." - ".$orden->creator_info->username.")"}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Comentarios</th>
                                <td>{{$orden->creation_notes}}</td>
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
                            @foreach (json_decode($orden->creation_list) as $index=>$pd)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$pd->name}}</td>
                                    <td>{{$pd->amount}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($orden->status=="created")
                    @if(!$preparando)
                    <div class="col-md-12 pt-2">
                        <div class="text-center">
                            <a class="btn btn-outline-primary" wire:click="iniciarAlistamiento()">Reportar alistamiento de orden</a>
                        </div>   
                    </div>
                    @else
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
                                @if(!empty($preparacion_list))
                                @foreach ($preparacion_list as $index=>$prod)
                                <tr>
                                    <td>a</td>
                                    <td>{{$prod["name"]}}</td>
                                    <td>{{$prod["amount"]}}</td>
                                    <td>{{$prod["firmware"]??"No"}}</td>
                                    <td>
                                        <a class="btn btn-outline-danger btn-sm" wire:click="removeProduct({{$index}})">Eliminar</a>
                                    </td>
                                </tr>
                                
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5">
                                        Aún no has alistado productos
                                    </td>
                                </tr>
                                @endif
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
                                                @foreach (json_decode($orden->creation_list) as $index=>$pd)
                                                <option value="{{$pd->id}}">{{$pd->name}}</option>
                                                @endforeach
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


                    @endif
                @else
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de alistamiento</h5>
                    <p class="card-text">Esta es la información relacionada con la creación de la orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de alistamiento</th>
                                <td>{{$orden->preparation_date}}</td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién alistó?</th>
                                <td>{{$orden->preparator_info->name." (".$orden->preparator_info->id." - ".$orden->preparator_info->username.")"}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Comentarios</th>
                                <td>{{$orden->preparation_notes}}</td>
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
                            @foreach (json_decode($orden->preparation_list) as $index=>$pd)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$pd->name}}</td>
                                    <td>{{$pd->amount}}</td>
                                    <td>{{$pd->firmware??"No"}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if($orden->status=="prepared")
                    @if(!$enviando)
                    <div class="col-md-12 pt-2">
                        <div class="text-center">
                            <a class="btn btn-outline-primary" wire:click="iniciarEnvio()">Reportar guía de envío</a>
                        </div>   
                    </div>
                    @else
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
                                    @foreach ($mensajerias as $mensajeria)
                                        <option value="{{$mensajeria->id}}">{{$mensajeria->name}}</option>
                                    @endforeach
                                    
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
                    @endif
                @elseif($orden->status!="prepared" && $orden->status!="created" && $orden->status!="canceled")
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de reporte de guía</h5>
                    <p class="card-text">Esta es la información relacionada con la generación de la guía de seguimiento.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de generación</th>
                                <td>{{$orden->enlist_date}}</td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién reportó la guía?</th>
                                <td>{{$orden->enlister_info->name." (".$orden->enlister_info->id." - ".$orden->enlister_info->username.")"}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Empresa</th>
                                <td>{{$orden->courier_info->name}}</td>
                            </tr>


                            <tr>
                                <th scope="row">Guía de seguimiento</th>
                                <td><a href="#">{{$orden->tracking}}</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                @endif

                @if($orden->status=="waiting")
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
                @elseif($orden->status!="prepared" && $orden->status!="created" && $orden->status!="waiting" && $orden->status!="canceled")
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de envío</h5>
                    <p class="card-text">Esta es la información relacionada con el envío del paquete</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de generación</th>
                                <td>{{$orden->send_date}}</td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién reportó el envío?</th>
                                <td>{{$orden->sender_info->name." (".$orden->sender_info->id." - ".$orden->sender_info->username.")"}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
    <br>
</div>